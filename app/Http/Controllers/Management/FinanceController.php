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

    public function exportPdf()
    {
        $viewModel = Auth::user()->role === 'admin' 
            ? new FinanceViewModel() 
            : new OrganizerFinanceViewModel(Auth::id());
            
        $data = $viewModel->toArray();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.global-finance-pdf', [
            'data' => $data,
            'role' => Auth::user()->role
        ]);

        return $pdf->stream('global-financial-report.pdf');
    }

    public function exportCsv()
    {
        $viewModel = Auth::user()->role === 'admin' 
            ? new FinanceViewModel() 
            : new OrganizerFinanceViewModel(Auth::id());
            
        $data = $viewModel->toArray();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=global_financial_report.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($data) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Total Revenue', $data['totalRevenue'] ?? 0]);
            fputcsv($file, ['Revenue This Month', $data['revenueThisMonth'] ?? 0]);
            fputcsv($file, ['Revenue Last Month', $data['revenueLastMonth'] ?? 0]);
            fputcsv($file, ['Pending Orders Value', $data['pendingOrdersValue'] ?? 0]);
            fputcsv($file, ['Average Order Value', $data['avgOrderValue'] ?? 0]);
            fputcsv($file, ['Completed Orders', $data['totalOrdersCompleted'] ?? 0]);
            fputcsv($file, ['Pending Orders', $data['totalOrdersPending'] ?? 0]);
            fputcsv($file, ['Canceled Orders', $data['totalOrdersCanceled'] ?? 0]);
            fputcsv($file, ['Total Tickets Sold', $data['totalTicketsSold'] ?? 0]);
            fputcsv($file, ['Total Tickets Scanned', $data['totalTicketsScanned'] ?? 0]);
            fputcsv($file, ['Growth Percent (%)', $data['growthPercent'] ?? 0]);
            fputcsv($file, []);

            fputcsv($file, ['Category', 'Revenue']);
            if (isset($data['revenueByCategory'])) {
                foreach ($data['revenueByCategory'] as $cat) {
                    fputcsv($file, [$cat['name'], $cat['revenue']]);
                }
            }
            fputcsv($file, []);

            fputcsv($file, ['Ticket Type', 'Total Sold', 'Revenue']);
            if (isset($data['ticketTypeBreakdown'])) {
                foreach ($data['ticketTypeBreakdown'] as $tt) {
                    fputcsv($file, [$tt->name, $tt->total_sold, $tt->total_revenue]);
                }
            }
            fputcsv($file, []);

            fputcsv($file, ['Top Event Title', 'Status', 'Total Revenue', 'Completed Orders']);
            if (isset($data['topEvents'])) {
                foreach ($data['topEvents'] as $event) {
                    fputcsv($file, [$event['title'], $event['status'], $event['total_revenue'], $event['completed_orders']]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
