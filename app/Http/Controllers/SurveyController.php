<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\SurveyResponse;

class SurveyController extends Controller
{
    public function create()
    {
        if (!session('admin_session')) return redirect('admin');
        return view('admin/surveys/create');
    }

    public function store(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');

        $this->validate($request, [
            'title' => 'required',
            'questions' => 'required|array'
        ]);

        $survey = new Survey();
        $survey->title = $request->input('title');
        $survey->description = $request->input('description');
        $survey->questions_json = $request->input('questions');
        $survey->active = $request->has('active');
        $survey->trigger_event = $request->input('trigger_event', 'appointment_finished');
        $survey->delay_minutes = $request->input('delay_minutes', 60);
        $survey->save();

        return redirect('admin/informes/respuestas-encuestas')->with('success', 'Encuesta creada exitosamente');
    }

    public function edit($id)
    {
        if (!session('admin_session')) return redirect('admin');
        $survey = Survey::findOrFail($id);
        return view('admin/surveys/create', compact('survey'));
    }

    public function update(Request $request, $id)
    {
        if (!session('admin_session')) return redirect('admin');
        
        $survey = Survey::findOrFail($id);
        $survey->title = $request->input('title');
        $survey->description = $request->input('description');
        $survey->questions_json = $request->input('questions');
        $survey->active = $request->has('active');
        $survey->trigger_event = $request->input('trigger_event');
        $survey->delay_minutes = $request->input('delay_minutes');
        $survey->save();

        return redirect('admin/informes/respuestas-encuestas')->with('success', 'Encuesta actualizada');
    }
}
