<?php

namespace App\Http\Controllers;

use App\Models\CashRegisterSession;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class CashRegisterController extends Controller {

    /**
     * Muestra la vista de apertura de caja
     */
    public function showOpen()
    {
        if (!session('admin_session')) return redirect('admin');

        // Check if already open
        $adminId = session('admin_id');
        if (!\App\User::find($adminId)) {
             $firstUser = \App\User::first();
             $adminId = $firstUser ? $firstUser->id : 1;
        }

        $existingSession = CashRegisterSession::where('user_id', $adminId)
                                            ->where('status', 'open')
                                            ->first();

        if ($existingSession) {
            return redirect('admin/caja/pos'); // Redirect to POS if already open
        }

        return view('admin/caja/apertura');
    }

    /**
     * Procesa la apertura de caja
     */
    public function storeOpen(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');

        $this->validate($request, [
            'opening_amount' => 'required|numeric|min:0'
        ]);

        // Ensure we have a valid user_id that exists in users table (FK constraint)
        $userId = session('admin_id');
        if (!\App\User::find($userId)) {
            // Fallback to the first existing user if the session admin ID is not in users table
            $firstUser = \App\User::first();
            $userId = $firstUser ? $firstUser->id : 1;
        }

        CashRegisterSession::create([
            'user_id' => $userId,
            'opening_amount' => $request->opening_amount,
            'calculated_amount' => $request->opening_amount,
            'difference' => 0,
            'status' => 'open',
            'opened_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect('admin/crear-factura')->with('success', 'Caja abierta correctamente.');
    }

    /**
     * Muestra la vista de cierre de caja (Arqueo)
     */
    public function showClose()
    {
        if (!session('admin_session')) return redirect('admin');

        $adminId = session('admin_id');
        if (!\App\User::find($adminId)) {
             $firstUser = \App\User::first();
             $adminId = $firstUser ? $firstUser->id : 1;
        }
        $session = CashRegisterSession::where('user_id', $adminId)
                                      ->where('status', 'open')
                                      ->first();

        if (!$session) {
            return redirect('admin/caja/apertura')->with('error', 'No hay una caja abierta para cerrar.');
        }

        // Calculate real totals from Sales for this session
        $totalSales = \App\Models\Sale::where('cash_register_session_id', $session->id)->sum('total');
        $calculatedAmount = $session->opening_amount + $totalSales;
        
        return view('admin/caja/cierre', compact('session', 'calculatedAmount', 'totalSales'));
    }

    /**
     * Procesa el cierre de caja
     */
    public function storeClose(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');

        $this->validate($request, [
            'closing_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $adminId = session('admin_id');
        if (!\App\User::find($adminId)) {
             $firstUser = \App\User::first();
             $adminId = $firstUser ? $firstUser->id : 1;
        }

        $session = CashRegisterSession::where('user_id', $adminId)
                                      ->where('status', 'open')
                                      ->firstOrFail();

        // Calculate real totals
        $totalSales = \App\Models\Sale::where('cash_register_session_id', $session->id)->sum('total');
        $calculatedAmount = $session->opening_amount + $totalSales;
        
        $difference = $request->closing_amount - $calculatedAmount;

        $session->update([
            'closing_amount' => $request->closing_amount,
            'calculated_amount' => $calculatedAmount,
            'difference' => $difference,
            'closing_notes' => $request->notes,
            'status' => 'closed',
            'closed_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect('admin/caja/resumen/' . $session->id);
    }

    /**
     * Muestra el resumen de la caja cerrada
     */
    public function showSummary($id)
    {
        if (!session('admin_session')) return redirect('admin');

        $session = CashRegisterSession::with('user')->findOrFail($id);
        
        // Get Sales Breakdown
        $sales = \App\Models\Sale::where('cash_register_session_id', $id)->get();
        $totalSales = $sales->sum('total');
        
        // Group by Payment Method
        $salesByMethod = $sales->groupBy('payment_method')->map(function($row) {
            return $row->sum('total');
        });

        return view('admin/caja/resumen', compact('session', 'sales', 'totalSales', 'salesByMethod'));
    }
}
