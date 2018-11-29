<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shiftgroup;
use Illuminate\Support\Facades\Auth;
class ShiftgroupsController extends Controller
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
        if(Auth::user()->is_admin!=1) {
            return redirect('home');
        }

        $shiftgroups = Shiftgroup::all();
        //Add active assignments
        foreach($shiftgroups as $shiftgroup) {
            $actives = 0;
            $shifts = $shiftgroup->shifts;
            foreach($shifts as $shift) {
                $assignments = $shift->activeAssignments;
                $actives += count($assignments);
            }
            $shiftgroup->actives = $actives;
        }
        return view('shiftgroups.index', compact('shiftgroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->is_admin!=1) {
            return redirect('home');
        }
        return view('shiftgroups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::user()->is_admin!=1) {
            return redirect('home');
        }
        $request->validate([
            'shiftgroupname' => 'required|max:50|unique:shiftgroups,name',
            'shiftgroupsubtitle' => 'max:100',
            'shiftgroupdescription' => 'max:255'
        ]);
        $newshiftgroup = new Shiftgroup([
            'name' => $request->get('shiftgroupname'),
            'subtitle'=> $request->get('shiftgroupsubtitle'),
            'description'=> $request->get('shiftgroupdescription')
        ]);
        $newshiftgroup->save();
        return redirect('shiftgroups')->with('success', 'Die Schichtgruppe wurde hinzugefügt.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Auth::user()->is_admin!=1) {
            return redirect('home');
        }
        index();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->is_admin!=1) {
            return redirect('home');
        }
        $shiftgroup = Shiftgroup::find($id);
        return view('shiftgroups.edit', compact('shiftgroup'));
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
        if(Auth::user()->is_admin!=1) {
            return redirect('home');
        }
        $shiftgroup = Shiftgroup::find($id);
        $request->validate([
            'shiftgroupname' => 'required|max:50|unique:shiftgroups,name,'.$shiftgroup->id,
            'shiftgroupsubtitle' => 'max:100',
            'shiftgroupdescription' => 'max:255'
        ]);

        $shiftgroup->name = $request->get('shiftgroupname');
        $shiftgroup->subtitle = $request->get('shiftgroupsubtitle');
        $shiftgroup->description = $request->get('shiftgroupdescription');
        $shiftgroup->save();
        return redirect('shiftgroups')->with('success', 'Schichtgruppe aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::user()->is_admin!=1) {
            return redirect('home');
        }
        /*
        * LÖSCHEN NICHT VORGESEHEN!
        $shiftgroup = Shiftgroup::find($id);
        $shifts = $shiftgroup->shifts;
        foreach($shifts as $shift) {
            $delete = Shift::find($shift->id);
            $delete->destroy();
        }
        $shiftgroup->delete();

        return redirect('shiftgroups')->with('success', 'Schichtgruppe erfolgreich entfernt.');
        */
        
    }
}
