<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Redirects to profile page
     *
     * @param null
     */
    public function index()
    {
        return view('user.profile');
    }


   
}
