<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ViewModels\DashboardViewModel;

class DashboardController extends Controller
{
    public function index()
    {
        $viewModel = new DashboardViewModel();
        
        return view('admin.dashboard', $viewModel->toArray());
    }
}
