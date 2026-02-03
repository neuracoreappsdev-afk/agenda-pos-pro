<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FacturacionController extends Controller
{

    /**
     * Emite una factura electrónica (Simulación DIAN)
     */
    public function emitir(Request $request)
    {
        if (!session('admin_session')) {
            return response()->json(['success' => false, 'error' => 'No autorizado'], 401);
        }

        $saleId = $request->input('sale_id');
        $sale = \App\Models\Sale::find($saleId);

        if (!$sale) {
            return response()->json(['success' => false, 'error' => 'Venta no encontrada']);
        }

        try {
            // 1. Validar datos del negocio
            $nit = \App\Models\Setting::get('business_nit');
            if (!$nit) throw new \Exception("El negocio no tiene NIT configurado.");

            // 2. Simular generación XML (UBL 2.1)
            $cufe = hash('sha384', $sale->id . $nit . date('YmdHis') . 'DIAN_COLOMBIA');
            
            // 3. Simular Tiempo de Respuesta DIAN (1-2 seg)
            sleep(1); 
            
            // 4. Guardar "estado" en la venta (si tuvieramos columna, por ahora simulamos retorno)
            // $sale->dian_status = 'approved';
            // $sale->dian_cufe = $cufe;
            // $sale->save();

            return response()->json([
                'success' => true,
                'message' => 'Factura Electrónica Emitida Exitosamente (DIAN)',
                'cufe' => $cufe,
                'dian_xml_url' => url('facturacion/download-xml/' . $sale->id)
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Error DIAN: ' . $e->getMessage()]);
        }
    }
}
