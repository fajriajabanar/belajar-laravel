<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info('user sedang menampilkan semua data user');
        return response()->json([
            "data" => User::all()
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function total_users()
     {
         Log::info('user sedang menampilkan semua total user');
         return response()->json([
             'totaluser' => User::count()
         ]);
     }
   }
