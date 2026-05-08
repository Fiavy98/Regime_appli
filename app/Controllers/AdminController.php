<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    public function dashboard()
    {
        return view('admin/dashboard');
    }

    public function regimes()
    {
        return view('admin/regimes');
    }

    public function sports()
    {
        return view('admin/sports');
    }

    public function codes()
    {
        return view('admin/codes');
    }
}
