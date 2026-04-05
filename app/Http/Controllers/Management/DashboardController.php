<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\ViewModels\DashboardViewModel;
use App\ViewModels\OrganizerDashboardViewModel;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $viewModel = new DashboardViewModel();
            return view('admin.dashboard', $viewModel->toArray());
        }
        
        $viewModel = new OrganizerDashboardViewModel(Auth::id());
        return view('organizer.dashboard', $viewModel->toArray());
    }
}
