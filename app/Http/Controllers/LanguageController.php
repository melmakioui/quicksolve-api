<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdvantageResource;
use App\Http\Resources\DepartmentResource;
use Illuminate\Http\Request;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\SpaceResource;
use App\Http\Resources\WebPageResource;
use App\Models\DepartmentLanguage;
use App\Models\Language;
use App\Models\SpaceLanguage;
use App\Models\AdvantageLanguage;
use App\Models\WebPage;


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

    public function getFieldsToTranslate (Request $request){

        $langCode = Language::where('name','es')->first()->id;

        $deparments = DepartmentLanguage::where('language_id',$langCode)->get();
        $spaces = SpaceLanguage::where('language_id',$langCode)->get();
        $advantages = AdvantageLanguage::where('language_id',$langCode)->get();
        $webpage = WebPage::all();

        $fieldsToTranslate = [
            'department' => $deparments,
            'space' => $spaces,
            'advantages'=> $advantages,
            'webpage' => WebPageResource::collection($webpage)
        ];

        return response()->json($fieldsToTranslate, 200);
    }
}
