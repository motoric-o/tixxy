<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\ViewModels\FinanceViewModel;
use App\ViewModels\OrganizerFinanceViewModel;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $viewModel = new FinanceViewModel();
            return view('admin.finance', $viewModel->toArray());
        }

        $viewModel = new OrganizerFinanceViewModel(Auth::id());
        return view('organizer.finance', $viewModel->toArray());
    }
}
