<?php

namespace App\Http\Controllers;

use App\News;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function __invoke()
    {
        return view('welcome');
    }

    public function mylist(){

        return view('mylist');
    }

    public function news(){

        $data = News::orderBy('created_at','desc')->paginate(5);

        return view('news',compact('data'));
    }
}
