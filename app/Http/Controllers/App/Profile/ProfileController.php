<?php

namespace App\Http\Controllers\App\Profile;

use App\Http\Controllers\Controller;

/**
 * Class ProfileController
 * @package App\Http\Controllers\App\Profile
 */
class ProfileController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('app.profile.profile');
    }
}
