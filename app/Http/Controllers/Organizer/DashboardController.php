<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\ViewModels\OrganizerDashboardViewModel;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $viewModel = new OrganizerDashboardViewModel(Auth::id());
        
        return view('organizer.dashboard', $viewModel->toArray());
    }
}
