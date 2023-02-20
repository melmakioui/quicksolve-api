<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartmentLanguage;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Models\Language;
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
        $department = Department::where('department_id', $id)
            ->where('language_id', Language::where('name', 'es')->get()->first()->id)
            ->get()->first();

        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        return response()->json($department, 200);
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

        $department = new Department();
        $department->type = $request->type;
        $department->save();

        $departmentLanguageEs = DepartmentLanguage::create([
            'department_id' => $department->id,
            'language_id' => Language::where('name', 'es')->get()->first()->id,
            'name' => $request->name,
        ])->save();

        $departmentLanguageEn = DepartmentLanguage::create([
            'department_id' => $department->id,
            'language_id' => Language::where('name', 'en')->get()->first()->id,
            'name' => $request->traduction,
        ])->save();


        if (!$departmentLanguageEs || !$departmentLanguageEn) {
            DB::rollBack();
            return response()->json(['message' => 'Error creating department'], 500);
        }

        DB::commit();
        return response()->json([$departmentLanguageEs, $departmentLanguageEn], 200);
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'type' => 'required|string',
            'name' => 'required|string',
            'traduction' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::beginTransaction();

        $department = Department::find($request->id);
        $department->type = $request->type;
        $department->save();

        $departmentLanguageEs = DepartmentLanguage::where('department_id', $request->id)
            ->where('language_id', Language::where('name', 'es')->get()->first()->id)
            ->get()->first();
        $departmentLanguageEs->name = $request->name;
        $departmentLanguageEs->save();

        $departmentLanguageEn = DepartmentLanguage::where('department_id', $request->id)
            ->where('language_id', Language::where('name', 'en')->get()->first()->id)
            ->get()->first();
        $departmentLanguageEn->name = $request->traduction;
        $departmentLanguageEn->save();


        if (!$departmentLanguageEn || !$departmentLanguageEn) {
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

        $department = Department::find($request->id);
        $departmentLangs = DepartmentLanguage::where('department_id', $request->id)->get();

        $departmentLangs->each(function ($departmentLang) {
            $departmentLang->delete();
        });

        $department->delete();

        if (!$department) {
            DB::rollBack();
            return response()->json(['message' => 'Error deleting department'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Department deleted'], 200);
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
            return response()->json(['message' => 'Departments not found'], 404);
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
            return response()->json(['message' => 'Error updating department type'], 500);
        }

        return response()->json(['message' => 'Department type updated'], 200);
    }
}
