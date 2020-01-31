<?php

namespace App;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Iptvusers extends Model
{
    protected $table = "iptvusers";
    protected $fillable = ['mac_address','period'];

    public function m3ufile(){
        return $this->hasOne(M3ufiles::class,'user_id','id');
    }

    public function epgfile(){
        return $this->hasOne(Epgfiles::class, 'user_id', 'id');
    }


    /**
     * Shahab responsible
     */


    public function ValidateUser(\Illuminate\Http\Request $request){
        $validator = Validator::make($request->all(),[
            'MacAdress' => ['required','exists:iptvusers,mac_address' , 'max:17' , 'min:17'],
        ]);
        $this->validator=$validator;
    }

    public function IsUserValidationFailed()
    {
        if($this->validator != null)
            return $this->validator->fails();
    }
    public function GetValidationError()
    {
        return $this->validator->errors();
    }
    public function VallidateUserWithChannelId(\Illuminate\Http\Request $request)
    {
        $validator = Validator::make($request->all(),[
            'MacAdress' => ['required','exists:iptvusers,mac_address','regex:/^(([0-9a-fA-F]{2}-){5}|([0-9a-fA-F]{2}:){5})[0-9a-fA-F]{2}$/' , 'max:17' , 'min:17'],
            'ChannelId' => 'required'
        ]);
        $this->validator=$validator;
    }
    public function VallidateUserWithAmount(\Illuminate\Http\Request $request)
    {
        $validator = Validator::make($request->all(),[
            'MacAdress' => ['required','exists:iptvusers,mac_address','regex:/^(([0-9a-fA-F]{2}-){5}|([0-9a-fA-F]{2}:){5})[0-9a-fA-F]{2}$/' , 'max:17' , 'min:17'],
            'Amount' => 'required|exists:subscription_package,amount'
        ]);
        $this->validator=$validator;
    }

    public function GetUser($MacAdress)
    {
        return (new $this())::where('mac_address',$MacAdress)->first();
    }
    public function SendVallidationErrorMessageResposne(){
        return response()->json($this->GetValidationError(),422);
    }
    public function GetExpiryDate($MacAdress){
        return  Carbon::parse($this->GetUser($MacAdress)->expiry_date);
    }
}
