<?php

namespace App\Http\Controllers;

use App\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subjects = [];

        if ($request->get('q')) {

            $q = $request->get('q');

            $subjects = Subject::where('name', 'like', "%$q%")->get();

            return response()->json($subjects);
        }

        return response()->json(Subject::all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function byCourse(Request $request)
    {
        $subjects = [];

        if ($request->has('course_id')) {

            $course_id = $request->get('course_id');

            $subjects = Subject::where('course_id', $course_id)->with('bibs.marc_tags','bibs.volumes')->has('bibs')->get();

            return response()->json($subjects);
        }

        return response()->json(Subject::all());
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            'department_id' => $request->get('department')['id'],
            'course_id' => $request->get('course')['id'],
            'code' => $request->get('code'),
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ];

        $subject = Subject::create($data);

        return response()->json($subject);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $success = $subject->update($request->all());

        return response()->json($success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
    }
}
