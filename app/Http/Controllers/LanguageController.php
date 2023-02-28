<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\LanguageResource;
use App\Models\Language;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(LanguageResource::collection(Language::all()), 200);
    }
}
