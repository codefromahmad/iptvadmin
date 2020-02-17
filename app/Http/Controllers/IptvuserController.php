<?php
namespace App\Http\Controllers;

use App\Iptvusers;
use DB;
use App\M3ufiles;
use App\Epgfiles;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;


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
        // When There is EPG file and M3U link
        if ($request->post('epg_link') == null && $request->file('m3ufile') == null) {
            $validatedData = $request->validate([
                'mac_address' => ['required', 'regex:/^(([0-9a-fA-F]{2}-){5}|([0-9a-fA-F]{2}:){5})[0-9a-fA-F]{2}$/', 'max:17', 'min:17'],
                'epgfile' => 'required',
                'm3u_link' => 'required',
            ]);
            $user = Iptvusers::where('mac_address', $request->post('mac_address'))->first();
            if ($user) {
                $file = [];
                $file['epg']['file'] = $request->file('epgfile');
                $file['epg']['name'] = $user->id . '.' . $file['epg']['file']->getClientOriginalExtension();
                $file['epg']['path'] = $file['epg']['file']->storeAs('epg', $file['epg']['name']);
                DB::table('epgfiles')
                    ->where('user_id', $user->id)
                    ->update(['efile' => $file['epg']['path']]);
                DB::table('m3ufiles')
                    ->where('user_id', $user->id)
                    ->update(['mfile' => $request->post('m3u_link')]);

                return redirect()->back()->with('update-success', 'Success : Updated with m3u_link and epgfile successfully.');
            } else {
                $user = Iptvusers::create([
                    'mac_address' => $request->post('mac_address')
                ]);

                $file['epg']['file'] = $request->file('epgfile');
                $file['epg']['name'] = $user->id . '.' . $file['epg']['file']->getClientOriginalExtension();
                $file['epg']['path'] = $file['epg']['file']->storeAs('epg', $file['epg']['name']);

                Epgfiles::create([
                    'user_id' => $user->id,
                    'efile' => $file['epg']['path']
                ]);

                M3ufiles::create([
                    'user_id' => $user->id,
                    'mfile' => $request->post('m3u_link')
                ]);

                return redirect()->back()->with('insert-success', 'Success : Registered with m3u_link and epgfile successfully.');
            }
        }
        // When There is M3U file and EPG link

        if ($request->post('m3u_link') == null && $request->file('epgfile') == null) {
            $validatedData = $request->validate([
                'mac_address' => ['required', 'regex:/^(([0-9a-fA-F]{2}-){5}|([0-9a-fA-F]{2}:){5})[0-9a-fA-F]{2}$/', 'max:17', 'min:17'],
                'm3ufile' => 'required',
                'epg_link' => 'required',
            ]);
            $user = Iptvusers::where('mac_address', $request->post('mac_address'))->first();
            if ($user) {
                $file = [];
                $file['m3u']['file'] = $request->file('m3ufile');
                $file['m3u']['name'] = $user->id . '.' . $file['m3u']['file']->getClientOriginalExtension();
                $file['m3u']['path'] = $file['m3u']['file']->storeAs('m3u', $file['m3u']['name']);
                DB::table('m3ufiles')
                    ->where('user_id', $user->id)
                    ->update(['mfile' => $file['m3u']['path']]);
                DB::table('epgfiles')
                    ->where('user_id', $user->id)
                    ->update(['efile' => $request->post('epg_link')]);

                return redirect()->back()->with('update-success', 'Success : Updated with epg_link and m3ufile successfully.');
            } else {
                $user = Iptvusers::create([
                    'mac_address' => $request->post('mac_address')
                ]);

                $file = [];
                $file['m3u']['file'] = $request->file('m3ufile');
                $file['m3u']['name'] = $user->id . '.' . $file['m3u']['file']->getClientOriginalExtension();
                $file['m3u']['path'] = $file['m3u']['file']->storeAs('m3u', $file['m3u']['name']);

                M3ufiles::create([
                    'user_id' => $user->id,
                    'mfile' => $file['m3u']['path']
                ]);

                Epgfiles::create([
                    'user_id' => $user->id,
                    'efile' => $request->post('epg_link')
                ]);

                return redirect()->back()->with('insert-success', 'Success : Registered with epg_link and m3ufile successfully.');
            }
        }

        // When both are links
        if ($request->file('epgfile') == null && $request->post('m3ufile') == null) {
            $validatedData = $request->validate([
                'mac_address' => ['required', 'regex:/^(([0-9a-fA-F]{2}-){5}|([0-9a-fA-F]{2}:){5})[0-9a-fA-F]{2}$/', 'max:17', 'min:17'],
                'epg_link' => 'required',
                'm3u_link' => 'required',
            ]);
            $user = Iptvusers::where('mac_address', $request->post('mac_address'))->first();
            if ($user) {
                DB::table('m3ufiles')
                    ->where('user_id', $user->id)
                    ->update(['mfile' => $request->post('m3u_link')]);
                DB::table('epgfiles')
                    ->where('user_id', $user->id)
                    ->update(['efile' => $request->post('epg_link')]);
                return redirect()->back()->with('update-success', 'Success : Updated both links successfully.');
            } else {
                $user = Iptvusers::create([
                    'mac_address' => $request->post('mac_address')
                ]);

                M3ufiles::create([
                    'user_id' => $user->id,
                    'mfile' => $request->post('m3u_link')
                ]);
                Epgfiles::create([
                    'user_id' => $user->id,
                    'efile' => $request->post('epg_link')
                ]);

                return redirect()->back()->with('insert-success', 'Success : Registered with both link successfully.');
            }
        }

// When both are files
        if ($request->post('epg_link') == null && $request->post('m3u_link') == null) {
            $validatedData = $request->validate([
                'mac_address' => ['required', 'regex:/^(([0-9a-fA-F]{2}-){5}|([0-9a-fA-F]{2}:){5})[0-9a-fA-F]{2}$/', 'max:17', 'min:17'],
                'm3ufile' => 'required',
                'epgfile' => 'required',
            ]);
            $user = Iptvusers::where('mac_address', $request->post('mac_address'))->first();
            if ($user) {
                $file = [];
                $file['m3u']['file'] = $request->file('m3ufile');
                $file['m3u']['name'] = $user->id . '.' . $file['m3u']['file']->getClientOriginalExtension();
                $file['m3u']['path'] = $file['m3u']['file']->storeAs('m3u', $file['m3u']['name']);

                $file['epg']['file'] = $request->file('epgfile');
                $file['epg']['name'] = $user->id . '.' . $file['epg']['file']->getClientOriginalExtension();
                $file['epg']['path'] = $file['epg']['file']->storeAs('epg', $file['epg']['name']);

                return redirect()->back()->with('update-success', 'Success : Updated both files successfully.');
            } else {
                $user = Iptvusers::create([
                    'mac_address' => $request->post('mac_address')
                ]);

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

                return redirect()->back()->with('insert-success', 'Success : Registered with both files successfully.');
            }
        }
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
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $validatedData = $request->validate([
            'mac_address' => ['required','regex:/^(([0-9a-fA-F]{2}-){5}|([0-9a-fA-F]{2}:){5})[0-9a-fA-F]{2}$/' , 'max:17' , 'min:17'],
        ]);

        $user = Iptvusers::where('mac_address', $request->post('mac_address') )->first();
        if($user){
            $iptv = $user->id;

            $data = Iptvusers::findOrFail($iptv);

            $epg = Epgfiles::where('user_id', '=', $iptv)->first();
            $m3u = M3ufiles::where('user_id', '=', $iptv)->first();
            $epg_path = storage_path("app/" . $epg->efile);
            $m3u_path = storage_path("app/" . $m3u->mfile);

            if (file_exists($m3u_path) && file_exists($epg_path)){
                File::delete($m3u_path);
                File::delete($epg_path);
                $epg->delete();
                $m3u->delete();
                $data->delete();
            }

            return redirect()->back()->with('delete-success', 'Success : Files updated successfully.');
        }else{
            dd('behtreen ni hoya');
        }
    }
}
