<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function details() 
    { 
        if (Auth::user() == null)
        {
            $data = ['error'=>'Unauthorised'];

            return response()->json($data, 401); 
        }
        $user = Auth::user();
        return response()->json($user, 200); 
    } 
}
