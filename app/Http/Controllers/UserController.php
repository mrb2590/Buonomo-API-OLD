<?php

namespace App\Http\Controllers;

use App\Http\Resources\Models\User as UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.api');
    }

    /**
     * Fetch the current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\Models\User
     */
    public function fetch(Request $request)
    {
        return new UserResource($request->user());
    }
}
