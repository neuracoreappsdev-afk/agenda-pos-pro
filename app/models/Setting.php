<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    
    protected $fillable = [
        'key', 'value', 'type', 'category', 'label', 
        'description', 'options', 'validation_rules', 'order', 'is_public'
    ];

    protected $casts = [
        'options' => 'array',
        'validation_rules' => 'array',
        'is_public' => 'boolean',
    ];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        try {
            $setting = static::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }

            // Handle different types
            switch ($setting->type) {
                case 'boolean':
                    return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
                case 'number':
                    return is_numeric($setting->value) ? (float)$setting->value : $default;
                case 'json':
                    return json_decode($setting->value, true) ?? $default;
                default:
                    return $setting->value ?? $default;
            }
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value, $type = 'text', $category = null, $label = null, $description = null)
    {
        try {
            // Convert value based on type
            if (is_array($value)) {
                $value = json_encode($value);
            } elseif (is_bool($value)) {
                $value = $value ? '1' : '0';
            }

            $data = [
                'value' => $value,
                'type' => $type
            ];
            
            if ($category) $data['category'] = $category;
            if ($label) $data['label'] = $label;
            if ($description) $data['description'] = $description;

            return static::updateOrCreate(
                ['key' => $key],
                $data
            );
        } catch (\Exception $e) {
            \Log::error("Error saving setting: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all settings by category
     */
    public static function getByCategory($category)
    {
        try {
            return static::where('category', $category)
                ->orderBy('order')
                ->get()
                ->mapWithKeys(function ($setting) {
                    return [$setting->key => static::get($setting->key)];
                });
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Get all settings as array
     */
    public static function getAll()
    {
        return static::all()->mapWithKeys(function ($setting) {
            return [$setting->key => static::get($setting->key)];
        })->toArray();
    }

    /**
     * Check if a setting exists
     */
    public static function has($key)
    {
        return static::where('key', $key)->exists();
    }

    /**
     * Delete a setting
     */
    public static function forget($key)
    {
        return static::where('key', $key)->delete();
    }

    /**
     * Get formatted value for display
     */
    public function getFormattedValueAttribute()
    {
        switch ($this->type) {
            case 'boolean':
                return $this->value ? 'Sí' : 'No';
            case 'json':
                $decoded = json_decode($this->value, true);
                return $decoded ? json_encode($decoded, JSON_PRETTY_PRINT) : $this->value;
            default:
                return $this->value;
        }
    }
}
