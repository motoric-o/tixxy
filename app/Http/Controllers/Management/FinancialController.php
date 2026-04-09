<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\ViewModels\DashboardViewModel;
use App\ViewModels\OrganizerDashboardViewModel;
use Illuminate\Support\Facades\Auth;

class FinancialController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $viewModel = new DashboardViewModel();
            return view('admin.financial', $viewModel->toArray());
        }
        
        $viewModel = new OrganizerDashboardViewModel(Auth::id());
        return view('organizer.financial', $viewModel->toArray());
    }
}
