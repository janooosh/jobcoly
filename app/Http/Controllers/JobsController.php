<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use Illuminate\Support\Facades\Auth;
class JobsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->is_admin!='1') {
            return redirect('home');
        }
        
        $jobs = Job::all();
        foreach($jobs as $job) {
            $actives = 0;
            $shifts = $job->shifts;
            foreach($shifts as $shift){
                $actives = count($shift->activeAssignments);
            }
            $job->actives = $actives;
        }
        
        return view('jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->is_admin!='1') {
            return redirect('home');
        }
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::user()->is_admin!='1') {
            return redirect('home');
        }
        $request->validate([
            'jobname' => 'required|unique:jobs,name',
            'jobshort' => 'required|size:2|regex:/^[A-Za-z\s-_]+$/|unique:jobs,short',
            'jobdescription' => 'max:255',
            'jobgutscheine' => 'required|integer|min:0|max:20',
            'jobawe' => 'required|integer|min:0|max:20'
          ]);
          $newjob = new Job([
            'name' => $request->get('jobname'),
            'short'=> $request->get('jobshort'),
            'description'=> $request->get('jobdescription'),
            'gesundheitszeugnis'=>$request->get('jobgesundheitszeugnis'),
            'gutscheine'=>$request->get('jobgutscheine'),
            'awe'=>$request->get('jobawe')
          ]);
          $newjob->save();
          return redirect('jobs')->with('success', 'Der Job wurde hinzugefügt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Auth::user()->is_admin!='1') {
            return redirect('home');
        }
        $jobs = Job::all();
        return view('jobs.index', compact('jobs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->is_admin!='1') {
            return redirect('home');
        }
        $job = Job::find($id);
        return view('jobs.edit', compact('job'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Auth::user()->is_admin!='1') {
            return redirect('home');
        }
        $job = Job::find($id);
        $request->validate([
            'jobname' => 'required|unique:jobs,name,'.$job->id,
            'jobshort' => 'required|size:2|regex:/^[A-Za-z\s-_]+$/|unique:jobs,short,'.$job->id,
            'jobgutscheine' => 'required|integer|min:0|max:20',
            'jobawe' => 'required|integer|min:0|max:20'
          ]);
    
          
          $job->name = $request->get('jobname');
          $job->short = $request->get('jobshort');
          $job->gesundheitszeugnis = $request->get('jobgesundheitszeugnis');
          $job->description = $request->get('jobdescription');
          $job->gutscheine = $request->get('jobgutscheine');
          $job->awe = $request->get('jobawe');
          $job->save(); 
          return redirect('jobs')->with('success', 'Job erledigt.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::user()->is_admin!='1') {
            return redirect('home');
        }
        /* Löschen nicht vorgesehen
        $job = Job::find($id);

        //Delete shifts
        $shifts = $job->shifts;
        foreach($shifts as $shift) {
            $delete = Shift::find($shift->id);
            $delete->destroy();
        }
        $job->delete();
        
     return redirect('jobs')->with('success', 'Job erfolgreich entfernt.'); */
    }
}
