<?php

namespace App\Controllers\Back;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('dashboard/index');
    }
}
