<?php

namespace App\Http\Controllers\Admin;


use DB;
use App\Epgfiles;
use App\Iptvusers;
use App\M3ufiles;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Permission;
use Illuminate\Support\Facades\File;


class HomeController extends controller{


    public function index()
    {
        return view('home');
    }

    public function iptvcreate()
    {
        return view('admin.iptvuser.create');
    }

    public  function storeiptv(Request $request){

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

        return redirect('/admin/iptvuser')->with('insert-success', 'Success : Registration done successfully.');

    }
    public function iptvuser(){

        $data = DB::table('iptvusers')
            ->join('epgfiles', 'iptvusers.id', '=', 'epgfiles.user_id')
            ->join('m3ufiles', 'iptvusers.id', '=', 'm3ufiles.user_id')
            ->select('iptvusers.*', 'epgfiles.efile', 'm3ufiles.mfile')
            ->get();

        return view('admin.Iptvuser.index', compact('data'));

    }

    public function showiptv($iptv){


        $data['iptv'] = Iptvusers::where('id', '=' ,$iptv)->first();
        $data['m3u'] = M3ufiles::where('user_id', '=', $iptv)->first();
        $data['epg'] = Epgfiles::where('user_id', '=', $iptv)->first();


        return view('admin.iptvuser.show', compact('data'));

    }

    public function editiptv($iptv){


        $data['iptv'] = Iptvusers::where('id', '=' ,$iptv)->first();
//        $data['m3u'] = M3ufiles::where('user_id', '=', $iptv)->first();
//        $data['epg'] = Epgfiles::where('user_id', '=', $iptv)->first();


        return view('admin.iptvuser.edit', compact('data'));

    }

    public  function updateiptv(Request $request){
        $i = 7;
        $request->validate([
            'period' => 'required','integer',
        ]);
        $id = $request->post('id');

        $selection = Iptvusers::find($id);
        $data = $selection->update($request->all());

        if($data){
            return redirect('/admin/iptvuser')->with('update-success', 'Updated : Record updated successfully.');
        }
        else{
            dd('dob gay');
        }

    }

    public function destroyiptv(Request $request)
    {
        $iptv = $request->id;


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
//
//        if ($done) {
//            echo "yes done";
//        } else {
//            echo "not done";
//        }
//        if(file_exists($image_path)){
//            //File::delete($image_path);
//            File::delete( $image_path);
//        }
//        $news->delete();
//        return redirect('admin/dashboard')->with('message','خبر موفقانه حذف  شد');

//        $m3u = M3ufiles::where('user_id', '=', $iptv)->first();
//        $epg = Epgfiles::where('user_id', '=', $iptv)->first();
//        $data = Iptvusers::where('id', '=' ,$iptv)->first();
//
//        if($data != null && $m3u != null && $epg != null){
//            $epg->delete();
//            $m3u->delete();
//            $data->delete();
//        }
//
        return redirect()->back()->with('delete-success', 'Success : One Record deleted Successfully.');
    }
    public function massDestroy(MassDestroyUserRequest $request)
    {
        dd('I am there');
        Iptvusers::whereIn('id', request('ids'))->delete();
        M3ufiles::whereIn('user_id', request('ids'))->delete();
        Epgfiles::whereIn('user_id', request('ids'))->delete();
        return response(null, 204);
    }
}
