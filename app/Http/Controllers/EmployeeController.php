<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use DataTables;
class EmployeeController extends Controller
{
    public function editEmp()
    {
        return view('emplyoee.edit');
    }
    public function addEmp()
    {
        return view('emplyoee.create');
    }
    public function index()
    {
        // $emp = Employee::all();
        return view('emplyoee.index');
    }

    public function Store(Request $request)
    {
        //return $request;
        $date = $request->hire_date;
        $date = Carbon::parse($date)->format('Y-m-d');
        $employee = Employee::create($request->all() + ['hire_date' => $date]);
        $employee = Employee::all();
        //return view('emplyoee.index');
        return response()->json($employee, 200);
    }
    public function empList(Request $request)
    {
        //return $request;
        $data = Employee::all();
        return Datatables::of($data)
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);

        return response()->json($data, 200);
    }
    public function employee(Request $request, $id)
    {
        //return $request;
        $data = Employee::where('id', $id)->first();
        return response()->json($data, 200);
    }

    public function empEdit(Request $request){
        $id = $request->hid;
        $date = $request->hire_date;
        $date = Carbon::parse($date)->format('Y-m-d');

        $employee = Employee::where('id', $id)->first();
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->job_title = $request->job_title;
        $employee->department = $request->department;
        $employee->hire_date = $request->hire_date;
        $employee->save();
        return $employee;
        return response()->json('success', 200);
        
    }
    public function empDelete(Request $request){

    }
}
