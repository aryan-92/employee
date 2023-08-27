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
        $emp = Employee::all();
        return view('emplyoee.index',compact('emp'));
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
        if ($request->ajax()) {

        return Datatables::of($data)

            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                //dd($instance);
                if (!empty($request->get('department'))) {
                    $instance->collection = $instance->collection->where('department', $request->get('department'));
                }
                if (!empty($request->get('job'))) {
                    $instance->collection = $instance->collection->where('job_title', $request->get('job'));
                }
                if (!empty($request->get('sname'))) {
                    $instance->collection = $instance->collection->where('name', $request->get('sname'));
                }
            })

            ->rawColumns(['action'])
            ->make(true);
        }
        return response()->json($data, 200);
    }

    public function student_list(Request $request)
    {



        $student = StudentDetails::where('department_id', '1')->where('batch_year', '!=', 'null')->where('clg_id', auth()->user()->clg_user_id)->get(['batch_year', 'id']);
        //dd($student);
        $collection = collect($student);
        //dd($collection);
        $all_batch_year = $collection->unique('batch_year');





        // datatable code ========

        if ($request->ajax()) {

            $year = now()->year;
            $month = now()->month;
            $ug_totsem = 8;
            $student = StudentDetails::where('department_id', '1')->where('batch_year', '!=', 'null')->where('clg_id', auth()->user()->clg_user_id)->get(['batch_year', 'id']);
            $collection = collect($student);

            foreach ($student as $key => $value) {
                //print($value);
                $explode = explode('-', $value->batch_year);
                $batch[] = $explode[0];

                $batch_totl = $year - $batch[$key];
                if ($month > 6) {
                    $semister_left = ($ug_totsem - ($batch_totl * 2)) - 1;
                    $sem_strt_frm = ($ug_totsem - $semister_left) + 1;
                } else {
                    $semister_left = $ug_totsem - ($batch_totl * 2);
                    $sem_strt_frm = ($ug_totsem - $semister_left) + 1;
                }

                $semi_name = [];
                if ($semister_left != 0) {
                    for ($i = $sem_strt_frm; $i <= $ug_totsem; $i++) {
                        if ($i % 2 != 0) {
                            //print $i ;
                            $semi_name[] = $i;
                        }
                    }
                }
                $value['stu_completed'] = $batch_totl;
                $value['semister_left'] = $semister_left;
                $value['semister_name'] = $semi_name;
                $batch_totl2[] = $value;
            }

            $eligible_student = collect($batch_totl2);

            $student_list = $eligible_student->filter(function ($value, $key) {
                return  $value['stu_completed'] < 4;
            });
            $student_details2 = [];
            foreach ($student_list as $key => $value) {

                $student_id = $value->id;
                // if ($request->status == '') {
                //     $student_details = StudentDetails::where('id', $student_id)->get(['clg_id', 'department_id', 'course_id', 'name', 'batch_year']);
                // } else {
                //     $student_details = StudentDetails::where('id', $student_id)->where('batch_year', $request->status)->get(['clg_id', 'department_id', 'course_id', 'name', 'batch_year']);
                // }

                $student_details = StudentDetails::where('id', $student_id)
                    ->get(['clg_id', 'department_id', 'course_id', 'name', 'batch_year']);


                foreach ($student_details as $key2 => $item) {
                    $item->stu_completed = $value->stu_completed;
                    $item->semister_left = $value->semister_left;
                    $item->semister_name = $value->semister_name;
                    $item->batch_year = $value->batch_year;
                    $item->student_id = $value->id;
                    $student_details2[] = $item;
                }
            }



            return DataTables::of($student_details2)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('batch_year'))) {
                        $instance->collection = $instance->collection->where('batch_year', $request->get('batch_year'));
                    }
                    if (!empty($request->get('sem_id'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains($row['semister_name'][0], $request->get('sem_id')) ? true : false;
                        });
                    }
                    if (!empty($request->get('stu_name'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            //return Str::contains($row['name'], $request->get('stu_name')) ? true : false;
                            return  Str::contains(Str::lower($row['name']), Str::lower($request->get('stu_name'))) ? true : false;
                        });
                    }
                })

                ->addColumn('semester', function ($row) {
                    $semester = $row['semister_name'][0];
                    $semester = $semester . ' ' . 'Semester';
                    return $semester;
                })
                ->addColumn('department', function ($row) {

                    $department = StudentDetails::join('course_fors', 'course_fors.id', '=', 'student_details.department_id')
                        ->where('student_details.department_id', $row['department_id'])
                        ->get(['course_fors.course_for']);
                    $department = $department[0]->course_for;
                    return $department;
                })
                ->addColumn('course', function ($row) {
                    $course = StudentDetails::join('courses', 'courses.id', '=', 'student_details.course_id')
                        ->where('student_details.course_id', $row['course_id'])
                        ->get(['courses.name']);
                    $course = $course[0]->name;
                    return $course;
                })
                ->rawColumns(['action', 'semester', 'department'])
                ->make(true);
        }
        // end datable code ===================
        return view('exam.student_list', compact('all_batch_year'));
    }
    public function employee(Request $request, $id)
    {
        //return $request;
        $data = Employee::where('id', $id)->first();
        return response()->json($data, 200);
    }

    public function empEdit(Request $request)
    {
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
        //return $employee;
        return response()->json('success', 200);
    }
    public function delEmp($id)
    {

        return view('emplyoee.delete');
    }
    public function empDelete(Request $request)
    {
        //return 1;
        //$id = $request;
        //return $request->id;
        $employee = Employee::where('id', $request->id)->first();
        $employee->delete();
        return response()->json('success', 200);
    }
}
