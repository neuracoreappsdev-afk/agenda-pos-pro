<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommercialPlan;
use App\Models\Package;

class CommercialPlanController extends Controller
{
    public function create()
    {
        if (!session('admin_session')) return redirect('admin');
        
        $services = Package::where('active', 1)->get();
        return view('admin/planes/create', compact('services'));
    }

    public function store(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');

        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|numeric',
            'validity_days' => 'required|integer',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        
        // Handle benefits/services included
        if ($request->has('included_services')) {
            $data['benefits_json'] = [
                'services' => $request->input('included_services')
            ];
        }

        CommercialPlan::create($data);

        return redirect('admin/informes/listado-planes')->with('success', 'Plan comercial creado exitosamente');
    }

    public function edit($id)
    {
        if (!session('admin_session')) return redirect('admin');
        
        $plan = CommercialPlan::find($id);
        $services = Package::where('active', 1)->get();
        return view('admin/planes/create', compact('plan', 'services'));
    }

    public function update(Request $request, $id)
    {
        if (!session('admin_session')) return redirect('admin');

        $plan = CommercialPlan::find($id);
        
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        
        if ($request->has('included_services')) {
            $data['benefits_json'] = [
                'services' => $request->input('included_services')
            ];
        }

        $plan->update($data);

        return redirect('admin/informes/listado-planes')->with('success', 'Plan actualizado');
    }
}
