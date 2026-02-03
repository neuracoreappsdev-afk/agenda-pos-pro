<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ConsentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created consent form in storage.
     */
    public function store(Request $request) {
        $data = $request->except('_token');
        $data['ip_address'] = $request->ip();
        
        \App\Models\ConsentForm::create($data); // Asumiendo que el modelo ya existe o se creará
        
        return response()->json(['success' => true]);
    }
}
