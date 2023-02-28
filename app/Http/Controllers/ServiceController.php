<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Service;
use App\Http\Resources\ServiceResource;
use App\Models\Advantage;
use App\Models\AdvantageLanguage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all()->load('advantages');
        return response()->json(ServiceResource::collection($services));
    }

    public function show($id)
    {
        $service = Service::where('id', $id)->get()->first();

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        return response()->json(ServiceResource::make($service));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|numeric',
            'tax' => 'required|numeric',
            'advantageOne' => 'required|string',
            'advantageOneLangs' => 'required|array',
            'advantageTwo' => 'required|string',
            'advantageTwoLangs' => 'required|array',
        ],);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::beginTransaction();

        $service = new Service();
        $service->name = $request->name;
        $service->price = $request->price;
        $service->tax = $request->tax;
        $service->save();

        $advantageOne = new Advantage();
        $advantageOne->service_id = $service->id;
        $advantageOne->save();


        $advantageEs = new AdvantageLanguage();
        $advantageEs->advantage_id = $advantageOne->id;
        $advantageEs->language_id = Language::where('name', 'es')->get()->first()->id;
        $advantageEs->name = $request->advantageOne;
        $advantageEs->save();

        $advantageTwo = new Advantage();
        $advantageTwo->service_id = $service->id;
        $advantageTwo->save();

        $advantageTwoEs = new AdvantageLanguage();
        $advantageTwoEs->advantage_id = $advantageTwo->id;
        $advantageTwoEs->language_id = Language::where('name', 'es')->get()->first()->id;
        $advantageTwoEs->name = $request->advantageTwo;
        $advantageTwoEs->save();

        foreach($request->advantageOneLangs as $key => $value) {
            if (!Language::where('name', $key)->get()->first()) continue;
            $advantageOneLang = new AdvantageLanguage();
            $advantageOneLang->advantage_id = $advantageOne->id;
            $advantageOneLang->language_id = Language::where('name', $key)->get()->first()->id;
            $advantageOneLang->name = $value;
            $advantageOneLang->save();
        }

        foreach($request->advantageTwoLangs as $key => $value) {
            if (!Language::where('name', $key)->get()->first()) continue;
            $advantageTwoLang = new AdvantageLanguage();
            $advantageTwoLang->advantage_id = $advantageTwo->id;
            $advantageTwoLang->language_id = Language::where('name', $key)->get()->first()->id;
            $advantageTwoLang->name = $value;
            $advantageTwoLang->save();
        }

        if (!$service) {
            DB::rollBack();
            return response()->json(['message' => 'Service not created'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Service created successfully', 'service' => ServiceResource::make($service)], 200);
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'tax' => 'required|numeric',
            'advantageOne' => 'required|string',
            'advantageTwo' => 'required|string',
            'advantageOneLangs' => 'required|array',
            'advantageTwoLangs' => 'required|array',
        ],);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $service = Service::find($request->id);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        DB::beginTransaction();

        $service->name = $request->name;
        $service->price = $request->price;
        $service->tax = $request->tax;
        $service->save();

        $advantageOne = Advantage::where('service_id', $service->id)->get()->first();
        $advantageTwo = Advantage::where('service_id', $service->id)->get()->last();

        if (!$advantageOne || !$advantageTwo) {
            DB::rollBack();
            return response()->json(['message' => 'Advantage not found'], 404);
        }

        foreach($request->advantageOneLangs as $key => $value) {
            if (!Language::where('name', $key)->get()->first()) continue;
            $advantageOneLang = AdvantageLanguage::where('advantage_id', $advantageOne->id)->where('language_id', Language::where('name', $key)->get()->first()->id)->get()->first();
            if (!$advantageOneLang) {
                $advantageOneLang = new AdvantageLanguage();
                $advantageOneLang->advantage_id = $advantageOne->id;
                $advantageOneLang->language_id = Language::where('name', $key)->get()->first()->id;
            }
            $advantageOneLang->name = $value;
            $advantageOneLang->save();
        }

        foreach($request->advantageTwoLangs as $key => $value) {
            if (!Language::where('name', $key)->get()->first()) continue;
            $advantageTwoLang = AdvantageLanguage::where('advantage_id', $advantageTwo->id)->where('language_id', Language::where('name', $key)->get()->first()->id)->get()->first();
            if (!$advantageTwoLang) {
                $advantageTwoLang = new AdvantageLanguage();
                $advantageTwoLang->advantage_id = $advantageTwo->id;
                $advantageTwoLang->language_id = Language::where('name', $key)->get()->first()->id;
            }
            $advantageTwoLang->name = $value;
            $advantageTwoLang->save();
        }

        if (!$service) {
            DB::rollBack();
            return response()->json(['message' => 'Service not updated'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Service updated successfully', 'service' => ServiceResource::make($service)], 200);
    }


    public function destroy(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ],);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::beginTransaction();

        $service = Service::find($request->id);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        $advantage = Advantage::where('service_id', $service->id)->get();

        if (!$advantage) {
            return response()->json(['message' => 'Advantage not found'], 404);
        }

        foreach ($advantage as $advantage) {
            $advantageLanguage = AdvantageLanguage::where('advantage_id', $advantage->id)->get();

            if (!$advantageLanguage) {
                DB::rollBack();
                return response()->json(['message' => 'Advantage language not found'], 404);
            }

            foreach ($advantageLanguage as $advantageLanguage) {
                $advantageLanguage->delete();
            }
            $advantage->delete();
        }

        $advantage->delete();
        $service->delete();

        DB::commit();
        return response()->json(['message' => 'Service deleted successfully'], 200);
    }
}
