<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Iptvusers;
use App\Role;
use App\SubscriptionPackage;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        abort_unless(\Gate::allows('user_access'), 403);

        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        abort_unless(\Gate::allows('user_create'), 403);

        $roles = Role::all()->pluck('title', 'id');

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        abort_unless(\Gate::allows('user_create'), 403);

        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_unless(\Gate::allows('user_edit'), 403);

        $roles = Role::all()->pluck('title', 'id');

        $user->load('roles');

        return view('admin.users.edit', compact('roles', 'user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        abort_unless(\Gate::allows('user_edit'), 403);

        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        abort_unless(\Gate::allows('user_show'), 403);

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_unless(\Gate::allows('user_delete'), 403);

        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    }




    /***
     *
     * IPTV APIS
     * Responsible Shahab
     */

    /**
     * funcntion  responsible for receivig macadress
     * ==.receive macadress
     * ==> get m3u file against mac
     */

    /**
     * funcntion  responsible for receivig macadress
     * ==.receive macadress
     * ==> get m3u file against mac
     */

    public function getList(Request $request,Iptvusers $user){
        $user->ValidateUser($request);
        if($user->IsUserValidationFailed())
        {
            return response()->json([
                'period' => null,
                'MacAdress' => null,
                'file' => null
            ],200);

        }else{
            //return the m3u files else
            $user = $user::select()->where('mac_address',$request->input('MacAdress'))->first();
            $detail = $user;
            dd(Carbon::parse($detail->expiry_date)->toString() ."  ".Carbon::now()->toString());
            $period = Carbon::parse($detail->expiry_date)->diffInDays(Carbon::now());
            if($period <=7 ) {
                //getting the associated m3u file
                $link = $user->m3ufile;
                //if user is newly created then the difference will be zero so no need to update
                if($period != 0)
                    $user->period=$period;
                $user->save();
                return response()->json([
                    'MacAdress' => $user->mac_address,
                    'period' => $user->period,
                    'file' =>   $link ? json_decode(\FileOperations::ConvertM3U($link->mfile)):null
                ], 200);
            }else{
                return response(['error' => 'subscription timed out'],422);
            }
        }

    }

    public function RetreiveUser($request){
        $user = new Iptvusers();
        $user = $user::where('mac_address',$request->MacAdress)->first();
        return $user;
    }

    public function CheckUserInM3UFile($user)
    {
        $m3ufile = $user->m3ufile;
        if($m3ufile==null)
        {
            //donot exist in the database
            return false;
        }
        return true;
    }


    public function addm3u(Request $request,M3UFile $file,User $reader){
        $validator = Validator::make($request->all(),[
            'file' => 'required',
            'mac_Adress' => 'required|exists:iptvusers,mac_address'
        ]);
        if(!$validator->fails()) {
            //retreive the user against macadress
            $user=$this->RetreiveUser($request);
            //check for already existing user in the m3ufile table
            //if the user has something already then it will nont be null
            if ($this->CheckUserInM3UFile($user) == true) {
                //update the user data in dataase
                //read the m3u file
                // return $reader->TextFileToArray($request->file);
                $path=\FileOperations::ConvertM3U($request->file);
                //fetching the m3u entity
                $m3u = $user->m3ufile()->first();
                //updating the file
                $m3u->File = $path;
                //storing the file path
                if ($status = $m3u->save()) {
                    return response()->json($status, 200);
                } else {
                    return response()->json($status, 422);
                }

            }
            //if there is no user id in the m3ufiles then we will create a new row
            $path=\FileOperations::ConvertM3U($request->file);
            $file->UserId = $user->id;
            $file->File = $path;
            if ($status=$file->save()) {
                return response()->json($status, 200);
            } else {
                return response()->json($file, 422);
            }
        }else{

            return response()->json($validator->errors(),422);
        }
    }
    public function get_all_user()
    {
        return User::all();
    }
    public function UserExpirayDate(Request $request,Iptvusers $user){
        $user->ValidateUser($request);
        if($user->IsUserValidationFailed())
            $user->SendVallidationErrorMessageResposne();
       else{
            $user=$user->GetUser($request->input("MacAdress"))->expiry_date;
            return response()->json(['expiry'=>$user],200);
        }
    }

    public function AboutChannels(Request $request,Iptvusers $user)
    {
        $user->VallidateUserWithChannelId($request);
        if($user->IsUserValidationFailed())
            $user->SendVallidationErrorMessageResposne();
        else{
            return response()->json(\FileOperations::xml($request->id,$user),200);
        }
    }

    public function IncreseExpiryDate(Request $request,Iptvusers $user,SubscriptionPackage $package)
    {
        $user->VallidateUserWithAmount($request);
        if($user->IsUserValidationFailed())
        $user->SendVallidationErrorMessageResposne();
        else{
            $user=$user->GetUser($request->MacAdress);
            $package = $package->GetPackage($request);
            $user->expiry_date=Carbon::parse($user->expiry_date)->addDays($package->DayIncrease);
            if($user->save())
                return response()->json(['message'=>'Account Subscription is increased by '.$package->MonthIncrease.' month/s'],200);
            else
                return response()->json(['message'=>'Account Subscription increase failed'],422);
        }
    }




}
