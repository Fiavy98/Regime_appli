<?php

namespace App\Controllers;

class UserController extends BaseController
{
    public function dashboard()
    {
        return view('dashboard');
    }
}
