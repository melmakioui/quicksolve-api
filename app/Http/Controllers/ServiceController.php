<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            'advantageTraductionOne' => 'required|string',
            'advantageTwo' => 'required|string',
            'advantageTraductionTwo' => 'required|string',
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

        $advantageOneNames = [
            'advantage' => $request->advantageOne,
            'traduction' => $request->advantageTraductionOne
        ];

        $lang = 1;

        foreach ($advantageOneNames as $key => $value) {
            $advantageLanguage = new AdvantageLanguage();
            $advantageLanguage->advantage_id = $advantageOne->id;
            $advantageLanguage->name = $advantageOneNames[$key];
            $advantageLanguage->language_id = $lang++;
            $advantageLanguage->save();
        }

        $advantageTwo = new Advantage();
        $advantageTwo->service_id = $service->id;
        $advantageTwo->save();

        $advantageTwoNames = [
            'advantage' => $request->advantageTwo,
            'traduction' => $request->advantageTraductionTwo
        ];

        $lang = 1;

        foreach ($advantageOneNames as $key => $value) {
            $advantageLanguage = new AdvantageLanguage();
            $advantageLanguage->advantage_id = $advantageTwo->id;
            $advantageLanguage->name = $advantageTwoNames[$key];
            $advantageLanguage->language_id = $lang++;;
            $advantageLanguage->save();
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
            'advantageTraductionOne' => 'required|string',
            'advantageTwo' => 'required|string',
            'advantageTraductionTwo' => 'required|string',
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

        $advantageOneNames = [
            'advantage' => $request->advantageOne,
            'traduction' => $request->advantageTraductionOne
        ];

        $advantageTwoNames = [
            'advantage' => $request->advantageTwo,
            'traduction' => $request->advantageTraductionTwo
        ];

        $lang = 1;

        foreach ($advantageOneNames as $key => $value) {
            $advantageLanguage = AdvantageLanguage::where('advantage_id', $advantageOne->id)
                ->where('language_id', $lang)->get()->first();
            $advantageLanguage->name = $advantageOneNames[$key];
            $advantageLanguage->save();
            $lang++;
        }

        $lang = 1;

        foreach ($advantageTwoNames as $key => $value) {
            $advantageLanguage = AdvantageLanguage::where('advantage_id', $advantageTwo->id)
                ->where('language_id', $lang)->get()->first();
            $advantageLanguage->name = $advantageTwoNames[$key];
            $advantageLanguage->save();
            $lang++;
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
