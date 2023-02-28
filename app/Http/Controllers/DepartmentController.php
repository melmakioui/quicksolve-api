<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartmentLanguage;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Models\Incidence;
use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class DepartmentController extends Controller
{
    public function index()
    {
        return response()->json( DepartmentResource::collection(Department::all()), 200);
    }

    public function show($id)
    {
        $departmentType = Department::where('id', $id)->pluck('type')->first(); 
        $department = DepartmentLanguage::where('department_id', $id)
            ->where('language_id', Language::where('name', 'es')->get()->first()->id)
            ->get()->first();
        $department["type"] = $departmentType;

        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        return response()->json($department, 200);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'type' => 'required|string',
            'languages' => 'required|array',
        ],);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (DepartmentLanguage::where('name', $request->name)->get()->first()) {
            return response()->json(['message' => 'Department already exists', 'success' => false], 400);
        }

        DB::beginTransaction();

        $department = new Department();
        $department->type = $request->type;
        $department->save();

        $departmentLanguage = DepartmentLanguage::create([
            'department_id' => $department->id,
            'language_id' => Language::where('name', 'es')->get()->first()->id,
            'name' => $request->name,
        ])->save();

        foreach($request->languages as $key => $value){
            if(!Language::where('name', $key)->get()->first()) continue;
            $departmentLanguage = DepartmentLanguage::create([
                'department_id' => $department->id,
                'language_id' => Language::where('name', $key)->get()->first()->id,
                'name' => $value,
            ])->save();
        }

        if (!$departmentLanguage) {
            DB::rollBack();
            return response()->json(['message' => 'Error creating department', 'success' => false], 500);
        }

        DB::commit();
        return response()->json(['message' => 'Added succesfully', 'success' => true], 200);
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'type' => 'required|string',
            'name' => 'required|string',
            'languages' => 'required','array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::beginTransaction();

        $department = Department::find($request->id);

        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        } else{
            $department->type = $request->type;
            $department->save();
        }
        
        $departmentLanguageEs = DepartmentLanguage::where('department_id', $request->id)
            ->where('language_id', Language::where('name', 'es')->get()->first()->id)
            ->get()->first();
        $departmentLanguageEs->name = $request->name;
        $departmentLanguageEs->save();

            foreach ($request->languages as $key => $value) {    
                if(!Language::where('name', $key)->get()->first()) continue;
                
                $departmentLanguage = DepartmentLanguage::where('department_id', $request->id)
                    ->where('language_id', Language::where('name', $key)->get()->first()->id)
                    ->get()->first();
                if(!$departmentLanguage) continue;
                $departmentLanguage->name = $value;
                $departmentLanguage->save();
            }

        if (!$departmentLanguageEs) {
            DB::rollBack();
            return response()->json(['message' => 'Error updating department'], 500);
        }

        DB::commit();
        return response()->json(['message' => 'Department updated'], 200);
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


        $incidence = Incidence::all()->where('department_id', $request->id)
            ->each(function ($incidence) {
                $incidence->department_id = null;
                $incidence->save();
        });

        $users = User::all()->where('department_id', $request->id)
            ->each(function ($user) {
                $user->department_id = null;
                $user->save();
        });

        $department = Department::find($request->id);
        $departmentLangs = DepartmentLanguage::where('department_id', $request->id)->get();

        $departmentLangs->each(function ($departmentLang) {
            $departmentLang->delete();
        });

        $department->delete();

        if (!$department) {
            DB::rollBack();
            return response()->json(['message' => 'Error deleting department', 'success' => false], 500);
        }

        DB::commit();
        return response()->json(['message' => 'Department deleted', 'success' => true], 200);
    }

    public function getByDepartmentByType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $departments = Department::where('type', $request->type)->get()->load('departmentLangs');

        if (!$departments) {
            return response()->json(['message' => 'Departments with type' . $request->type .'not found'], 404);
        }

        return response()->json(DepartmentResource::collection($departments), 200);
    }

    public function updateDepartmentType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $department = Department::find($request->id);
        $department->type = $request->type;
        $department->save();

        if (!$department) {
            return response()->json(['message' => 'Error updating department type', 'success' => false], 500);
        }

        return response()->json(['message' => 'Department type updated', 'success' => true], 200);
    }
}
