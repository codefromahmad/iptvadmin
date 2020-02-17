<?php

namespace App\Http\Controllers\Admin;


use DB;
use App\News;
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

    public function dashboard()
    {
        $all_users = Iptvusers::all();
        $users = Iptvusers::all()->where('period','');
        $news  = News::all();

        return view('admin.dashboard.home',['news'=>$news,'users'=>$users,'all_users'=>$all_users]);
    }

//    public function index()
//    {
//        return view('admin.dashboard.home');
//    }

    public function news()
    {
        abort_unless(\Gate::allows('permission_access'), 403);

        $data = News::all();

        return view('admin.news.index',compact('data'));
    }

    public function createnews()
    {
        return view('admin.news.create');
    }

    public function storenews(Request $request)
    {

        $validatedData = $request->validate([
            'title' => ['required' , 'min:10'],
            'description' => ['required' , 'min:50']
        ]);

        $news = News::create([
            'title' => $request->post('title'),
            'description' => $request->post('description')
        ]);
        return redirect('admin/news')->with('update-success', 'Success : Files updated successfully.');
    }


    public function update_news(Request $request)
    {
        News::findOrFail($request->id)->update($request->all());
        return redirect('/admin/news')->with('update-success', 'Updated : News updated successfully.');
    }


    public function editnews($id)
    {
        $news = News::where('id', '=' ,$id)->first();

        return view('admin.news.edit', compact('news'));
    }


    public function destroy_news(Request $request)
    {
        News::destroy($request->id);
        return redirect()->back()->with('delete-success', 'Success : Files updated successfully.');
    }

    public function shownews($id)
    {
        $news = News::where('id', '=' ,$id)->first();
        return view('admin.news.show',compact('news'));
    }

    public function iptvcreate()
    {
        return view('admin.iptvuser.create');
    }

    public function storeiptv(Request $request)
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

    public function iptvuser(){

        $data = DB::table('iptvusers')
            ->join('epgfiles', 'iptvusers.id', '=', 'epgfiles.user_id')
            ->join('m3ufiles', 'iptvusers.id', '=', 'm3ufiles.user_id')
            ->select('iptvusers.*', 'epgfiles.efile', 'm3ufiles.mfile')
            ->get();

        return view('admin.iptvuser.index', compact('data'));

    }

    public function showiptv($iptv){

        $data['iptv'] = Iptvusers::where('id', '=' ,$iptv)->first();
        $data['m3u'] = M3ufiles::where('user_id', '=', $iptv)->first();
        $data['epg'] = Epgfiles::where('user_id', '=', $iptv)->first();


        return view('admin.iptvuser.show', compact('data'));

    }

    public function editiptv($iptv){


        $data['iptv'] = Iptvusers::where('id', '=' ,$iptv)->first();

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
