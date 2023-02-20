<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Space;
use App\Models\SpaceLanguage;
use App\Http\Resources\SpaceResource;
use App\Models\Language;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SpaceController extends Controller
{

    public function index()
    {
        return response()->json(SpaceResource::collection(Space::all()));
    }

    public function show($id)
    {
        $space = SpaceLanguage::where('space_id', $id)
            ->where('language_id', Language::where('name', 'es')->get()->first()->id)
            ->get()->first();

        if (!$space) {
            return response()->json(['message' => 'Space not found'], 404);
        }

        return response()->json($space);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'traduction' => 'required|string',
        ],);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::beginTransaction();

        $space = new Space();
        $space->save();

        $spaceLanguageEs = SpaceLanguage::create([
            'space_id' => $space->id,
            'language_id' => Language::where('name', 'es')->get()->first()->id,
            'name' => $request->name,
        ])->save();

        $spaceLanguageEn = SpaceLanguage::create([
            'space_id' => $space->id,
            'language_id' => Language::where('name', 'en')->get()->first()->id,
            'name' => $request->traduction,
        ])->save();

        if (!$spaceLanguageEs || !$spaceLanguageEn) {
            DB::rollBack();
            return response()->json(['message' => 'Error creating space'], 500);
        }

        DB::commit();
        return response()->json([$spaceLanguageEs, $spaceLanguageEn], 200);
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => 'required|string',
            'traduction' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::beginTransaction();

        $spaceLanguageEs = SpaceLanguage::where('space_id', $request->id)
            ->where('language_id', Language::where('name', 'es')->get()->first()->id)
            ->get()->first();

        $spaceLanguageEn = SpaceLanguage::where('space_id', $request->id)
            ->where('language_id', Language::where('name', 'en')->get()->first()->id)
            ->get()->first();

        $spaceLanguageEs->name = $request->name;
        $spaceLanguageEn->name = $request->traduction;
        $spaceLanguageEs->save();
        $spaceLanguageEn->save();

        if (!$spaceLanguageEs || !$spaceLanguageEn) {
            DB::rollBack();
            return response()->json(['message' => 'Error updating space'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Space updated'], 200);
    }

    public function destroy(Request $request)
    {

        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $space = Space::find($request->id);
        $spaceLanguages = SpaceLanguage::where('space_id', $request->id)->get();

        $spaceLanguages->each(function ($spaceLanguage) {
            $spaceLanguage->delete();
        });

        $space->delete();

        if (!$space) {
            DB::rollBack();
            return response()->json(['message' => 'Error deleting space'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Space deleted'], 200);
    }
}
