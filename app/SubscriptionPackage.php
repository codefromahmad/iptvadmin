<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SubscriptionPackage extends Model
{
    protected $table = "subscription_package";

    public function GetPackage(Request $request)
    {
        return (new $this())::where('amount',$request->Amount)->first();
    }
}
