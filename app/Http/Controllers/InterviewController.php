<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function show($id)
    {
        $interview = Interview::findOrFail($id);
        return view('interviews.show', compact('interview'));
    }
}
