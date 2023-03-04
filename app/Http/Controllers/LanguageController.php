<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdvantageResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\IncidenceStateResource;
use Illuminate\Http\Request;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\SpaceResource;
use App\Http\Resources\WebPageResource;
use App\Models\DepartmentLanguage;
use App\Models\Language;
use App\Models\SpaceLanguage;
use App\Models\AdvantageLanguage;
use App\Models\WebPage;
use App\Models\WebPageLanguage;
use Illuminate\Support\Facades\Validator;
use App\Models\IncidenceStateLanguage;
use Illuminate\Support\Facades\DB;


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
        $states = IncidenceStateLanguage::where('language_id',$langCode)->get();
        $webpage = WebPage::all();
        
        $fieldsToTranslate = [
            'departments' => $deparments,
            'spaces' => $spaces,
            'states' => IncidenceStateResource::collection($states),
            'advantages'=> $advantages,
            'webpage' => WebPageResource::collection($webpage),
        ];

        return response()->json($fieldsToTranslate, 200);
    }


    public function store (Request $request) {

        DB::transaction(function () use ($request) {
            $lang = Language::create([
                'name' => $request->code,
                'language' => $request->language,
            ]);
         
            //Departments
            foreach($request->translatedDeps as $department) {
                $departmentLang = DepartmentLanguage::create([
                  'name' => $department['name'],
                  'language_id' => $lang->id,
                  'department_id' => $department['department_id'],
                ]);
            }
    
            // // //Spaces
            foreach($request->translatedSpa as $space) {
                $spaceLang = SpaceLanguage::create([
                  'name' => $space['name'],
                  'language_id' => $lang->id,
                  'space_id' => $space['space_id'],
                ]);
            }
    
            // // //Advantages
            foreach($request->translatedAdv as $advantage) {
                $advantageLang = AdvantageLanguage::create([
                  'name' => $advantage['name'],
                  'language_id' => $lang->id,
                  'advantage_id' => $advantage['advantage_id'],
                ]);
            }
    
            // //Webpage
            foreach($request->fields as $field) {
                $webpageLang = WebPageLanguage::create([
                  'content' => $field['name'],
                  'language_id' => $lang->id,
                  'webpage_id' => $field['webpage_id'],
                ]);
            }
    
            //Incidences state
            foreach($request->translatedSta as $state) {
                $incidenceLang = IncidenceStateLanguage::create([
                  'status_name' => $state['name'],
                  'language_id' => $lang->id,
                  'incidence_state_id' => $state['incidence_state_id'],
                ]);
            }
    
            if (!$lang) {
                return response()->json(['message' => 'Language not created'], 400);
            }
    
        });

        return response()->json(['message' => $request->language], 201);
    }
}
