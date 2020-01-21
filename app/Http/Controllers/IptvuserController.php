<?php
namespace App\Http\Controllers;

use App\Iptvusers;
use App\M3ufiles;
use App\Epgfiles;


use Illuminate\Support\Facades\Storage;
use Illuminate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


class IptvuserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('iptvuser.iptvregister');
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
     * @return string
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'mac_address' => ['required','unique:Iptvusers','regex:/^(([0-9a-fA-F]{2}-){5}|([0-9a-fA-F]{2}:){5})[0-9a-fA-F]{2}$/' , 'max:17' , 'min:17'],
            'm3ufile' => 'required',
            'epgfile' => 'required',
        ]);

        $user = Iptvusers::create([
            'mac_address' => $request->post('mac_address')
       ]);

        if (!$request->hasFile('m3ufile') && !$request->hasFile('epgfile'))
            return false;


        $file = [];
        $file['m3u']['file'] = $request->file('m3ufile');
        $file['m3u']['name'] = $user->id . '.' . $file['m3u']['file']->getClientOriginalExtension();
        $file['m3u']['path'] = $file['m3u']['file']->storeAs('m3u', $file['m3u']['name']);

        $file['epg']['file'] = $request->file('epgfile');
        $file['epg']['name'] = $user->id . '.' . $file['epg']['file']->getClientOriginalExtension();
        $file['epg']['path'] = $file['epg']['file']->storeAs('epg', $file['epg']['name']);

        M3ufiles::create([
            'user_id' => $user->id,
            'mfile' => $file['m3u']['path']
        ]);
        Epgfiles::create([
            'user_id' => $user->id,
            'efile' => $file['epg']['path']
        ]);

        return redirect()->back()->with('success', 'Success : Registration done successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
