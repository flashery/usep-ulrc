<?php

namespace App\Http\Controllers;

use App\Bib;
use Illuminate\Http\Request;
use App\BibVolume;
use App\BibMarcTag;
use App\Subject;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BibController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['updateBibViews']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $bibs = Bib::with('marc_tags', 'subjects', 'volumes')->paginate(10);
            return response()->json($bibs);
        }

        return view('bibs');
    }


    public function byCourse(Request $request)
    {
        $bibs = [];

        if ($request->has('course_id')) {

            $course_id = $request->get('course_id');

            $bibs = Bib::with('marc_tags', 'subjects', 'volumes')->whereHas('subjects', function ($q) use ($course_id) {
                $q->where('course_id', $course_id);
            })->get();

            return response()->json($bibs);
        }

        return response()->json(Subject::all());
    }

    public function bySubject(Request $request)
    {
        $subjects = [];

        if ($request->has('id')) {

            $id = $request->get('id');

            $subjects = Subject::where('id', $id)->with('bibs.marc_tags', 'bibs.volumes')->has('bibs')->get();

            return response()->json($subjects);
        }

        return response()->json(Subject::with('bibs.marc_tags', 'bibs.volumes')->has('bibs')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request->get('marc_tags') as $bib_tag) {

            if ($bib_tag['marc_tag'] === '016') {

                $bib_marc_tag =  BibMarcTag::where('marc_tag_id', $bib_tag['id'])->where('value', $bib_tag['value'])->first();

                if ($bib_marc_tag)
                    return response()->json(['error' => 'Bib already exist'], 400);
            }
        }
        // $data = $request->g
        $subject_ids = $request->get('subjects');
        $volumes = $request->get('volumes');
        $marc_tags = $request->get('marc_tags');
        $bib_volumes = [];
        $bib_subjects = [];
        $bib_tags = [];



        foreach ($subject_ids as  $index => $subject_id) {
            if (!$subject_id) {
                unset($subject_ids[$index]);
            } else {
                array_push($bib_subjects, $subject_id);
            }
        }

        foreach ($volumes as $volume) {
            array_push($bib_volumes, new BibVolume($volume));
        }

        foreach ($marc_tags as  $bib_tag) {

            array_push($bib_tags, new BibMarcTag([
                'marc_tag_id' => $bib_tag['id'],
                'value' => isset($bib_tag['value']) ? $bib_tag['value'] : '',
            ]));
        }
        // dd($bib_tags);
        $bib = Bib::create();
        $bib->volumes()->saveMany($bib_volumes);
        $bib->subjects()->sync($bib_subjects);
        $bib->bib_marc_tags()->saveMany($bib_tags);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Bib  $bib
     * @return \Illuminate\Http\Response
     */
    public function show(Bib $bib)
    { }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bib  $bib
     * @return \Illuminate\Http\Response
     */
    public function edit(Bib $bib)
    { }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bib  $bib
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bib $bib)
    {
        $subject_ids = $request->get('subjects');
        $volumes = $request->get('volumes');
        $bib_tags = $request->get('marc_tags');
        $bib_subjects = [];
        $volume_ids = [];
        $bib_tag_ids = [];

        foreach ($subject_ids as  $index => $subject_id) {
            if (!$subject_id) {
                unset($subject_ids[$index]);
            } else {
                array_push($bib_subjects, $subject_id);
            }
        }

        foreach ($bib_tags as $bib_tag) {
            if (!isset($bib_tag['id'])) {
                $bib_tag = $bib->bib_marc_tags()->create([
                    'marc_tag_id' => $bib_tag['marc_tag_id'],
                    'value' => $bib_tag['value'],
                ]);
            } else {
                $bib_tag =  $bib->bib_marc_tags()->create([
                    'marc_tag_id' => $bib_tag['id'],
                    'value' => $bib_tag['value'],
                ]);
            }

            array_push($bib_tag_ids, $bib_tag['id']);
        }

        foreach ($volumes as $volume) {
            if (!isset($volume['id'])) {
                $volume['id'] = $bib->volumes()->max('id') + 1;
            }
            $volume = $bib->volumes()->updateOrCreate(['id' => $volume['id']], $volume);
            array_push($volume_ids, $volume['id']);
        }

        $bib->bib_marc_tags()->whereNotIn('id', $bib_tag_ids)->delete();
        $bib->volumes()->whereNotIn('id', $volume_ids)->delete();
        $bib->subjects()->sync($bib_subjects);
    }


    public function updateBibViews($id)
    {
        $bib = Bib::find($id);
        $bib->views += 1;
        $bib->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bib  $bib
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bib $bib)
    {
        $bib->delete();
    }
}
