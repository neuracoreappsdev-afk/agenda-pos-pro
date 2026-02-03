<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\Specialty;
use App\Models\ClientType;
use App\Models\Setting;

class ConfigurationApiController extends Controller
{
    // ==========================================
    // FORMAS DE PAGO
    // ==========================================
    
    public function getPaymentMethods()
    {
        if (!session('admin_session')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $methods = PaymentMethod::orderBy('name')->get();
        return response()->json($methods);
    }
    
    public function storePaymentMethod(Request $request)
    {
        if (!session('admin_session')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:20',
            'active' => 'boolean',
            'requires_approval' => 'boolean',
        ]);
        
        $method = PaymentMethod::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Método de pago creado exitosamente',
            'data' => $method
        ]);
    }
    
    public function updatePaymentMethod(Request $request, $id)
    {
        if (!session('admin_session')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $method = PaymentMethod::findOrFail($id);
        $method->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Método de pago actualizado',
            'data' => $method
        ]);
    }
    
    public function deletePaymentMethod($id)
    {
        if (!session('admin_session')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        PaymentMethod::destroy($id);
        
        return response()->json([
            'success' => true,
            'message' => 'Método eliminado'
        ]);
    }
    
    // ==========================================
    // ESPECIALIDADES
    // ==========================================
    
    public function getSpecialties()
    {
        if (!session('admin_session')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $specialties = Specialty::orderBy('order')->get();
        return response()->json($specialties);
    }
    
    public function storeSpecialty(Request $request)
    {
        if (!session('admin_session')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:500',
        ]);
        
        $specialty = Specialty::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Especialidad creada exitosamente',
            'data' => $specialty
        ]);
    }
    
    public function updateSpecialty(Request $request, $id)
    {
        if (!session('admin_session')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $specialty = Specialty::findOrFail($id);
        $specialty->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Especialidad actualizada',
            'data' => $specialty
        ]);
    }
    
    public function deleteSpecialty($id)
    {
        if (!session('admin_session')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        Specialty::destroy($id);
        
        return response()->json([
            'success' => true,
            'message' => 'Especialidad eliminada'
        ]);
    }
    
    // ==========================================
    // TIPOS DE CLIENTES
    // ==========================================
    
    public function getClientTypes()
    {
        if (!session('admin_session')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $types = ClientType::withCount('clients')->get();
        return response()->json($types);
    }
    
    public function storeClientType(Request $request)
    {
        if (!session('admin_session')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:20',
            'default_discount' => 'nullable|numeric|min:0|max:100',
            'auto_apply_discount' => 'boolean',
        ]);
        
        $type = ClientType::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Tipo de cliente creado exitosamente',
            'data' => $type
        ]);
    }
    
    public function updateClientType(Request $request, $id)
    {
        if (!session('admin_session')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $type = ClientType::findOrFail($id);
        $type->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Tipo de cliente actualizado',
            'data' => $type
        ]);
    }
    
    public function deleteClientType($id)
    {
        if (!session('admin_session')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        ClientType::destroy($id);
        
        return response()->json([
            'success' => true,
            'message' => 'Tipo eliminado'
        ]);
    }
}
