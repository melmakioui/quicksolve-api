<?php

namespace App\Http\Controllers;

use App\Http\Resources\IncidenceResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Incidence;
use App\Models\UserIncidence;

class IncidenceController extends Controller
{
    private const INCIDENCE_STATE_WAITING = 1;
    private const  INCIDENCE_STATE_IN_PROGRESS = 2;
    private const  INCIDENCE_STATE_SOLVED = 3;
    private const  INCIDENCE_TYPE_CANCEL = 4;
    public function index()
    {
        $incidences = Incidence::all()->load('userIncidences.user');
        return response()->json(IncidenceResource::collection($incidences),200);
    }

    public function showIncidencesByState(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idState' => 'required|integer',
        ],);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $incidences = Incidence::where('incidence_state_id', $request->idState)->get()->load('userIncidences.user');

        if (!$incidences) {
            return response()->json(['message' => 'Incidences not found'], 404);
        }

        return response()->json(IncidenceResource::collection($incidences),200);
    }


    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ],);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $incidence = Incidence::find($request->id)->load('userIncidences.user');

        if (!$incidence) {
            return response()->json(['message' => 'Incidence not found'], 404);
        }
        
        return response()->json(new IncidenceResource($incidence),200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'department_id' => 'required|integer',
            'space_id' => 'required|integer',
        ],);

        DB::beginTransaction();

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $incidence = Incidence::find($request->id);

        if (!$incidence) {
            return response()->json(['message' => 'Incidence not found'], 404);
        }

        $updated = $incidence->update([
            'title' => $request->title,
            'description' => $request->description,
            'department_id' => $request->department_id,
            'space_id' => $request->space_id,
        ]);

        if(!$updated){
            DB::rollBack();
            return response()->json(['error' => 'Error creating incidence'], 500);
        }

        DB::commit();

        return response()->json([
            'message' => 'Incidence created', 
            "result" => new IncidenceResource($incidence)], 200);
    }

    public function changeState(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'stateId' => 'required|integer',
        ],);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $incidence = Incidence::find($request->id);

        if (!$incidence) {
            return response()->json(['message' => 'Incidence not found'], 404);
        }

        $incidence->incidence_state_id = $request->stateId;
        
        if($request->stateId == self::INCIDENCE_STATE_SOLVED || 
        $request->stateId == self::INCIDENCE_TYPE_CANCEL){
            $incidence->date_end = now();
        }

        $incidence->save();

        $incidenceUpdated = Incidence::find($request->id)->load('userIncidences.user');

        return response()->json(['message' => 'Incidence state changed', 
        'Incidence Updated' => IncidenceResource::make($incidenceUpdated)], 200);
    }


    public function changeTech(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'techId' => 'required|integer',
        ],);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $userIncidence = UserIncidence::where('incidence_id', $request->id);

        if (!$userIncidence) {
            return response()->json(['message' => 'User incidence not found'], 404);
        }

        if( $userIncidence->load('user')->user->type != "TECH" ){
            return response()->json(['message' => 'User is not a technician'], 404);
        }

        $userIncidence->tech_id = $request->techId;
        $userIncidence->save();

        $incidenceUpdated = Incidence::find($request->id)->load('userIncidences.user');

        return response()->json(['message' => 'Incidence tech changed', 
        'Incidence Updated' =>  IncidenceResource::make($incidenceUpdated),200]);
    }

}
