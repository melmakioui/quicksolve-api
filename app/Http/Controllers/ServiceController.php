<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Resources\ServiceResource;
use App\Models\Advantage;
use App\Models\AdvantageLanguage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all()->load('advantages');
        return response()->json(ServiceResource::collection($services));
    }

    public function show($id)
    {
        $service = Service::where('service_id', $id)->get()->first();

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        return response()->json($service);
    }



}
