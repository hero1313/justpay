<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use Illuminate\Http\Request;

class PusherController extends Controller
{
    public function broadcast(Request $request){
        broadcast(new PusherBroadcast(123))->toOthers();
        return view('broadcast');
    }

    public function recieve(Request $request){
        return view('recieve');
    }
}
