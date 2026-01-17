<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payslip;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $agency = app('currentAgency');
        $month = $request->query('month', date('Y-m'));

        $employeeCount = Employee::where('agency_id', $agency->id)->count();
        $ticketCount = Ticket::where('agency_id', $agency->id)->count();
        $approvedPayslips = Payslip::whereHas('employee', function ($q) use ($agency) {
            $q->where('agency_id', $agency->id);
        })->where('status', 'approved')->where('month', $month)->count();

        $ticketSales = Ticket::where('agency_id', $agency->id)
            ->whereBetween('issue_date', [$month.'-01', date('Y-m-t', strtotime($month.'-01'))])
            ->selectRaw('SUM(fare + tax) as total_sales, SUM(profit_loss) as total_profit')
            ->first();

        return view('admin.dashboard', [
            'title' => 'Admin Dashboard',
            'currentAgency' => $agency,
            'month' => $month,
            'employeeCount' => $employeeCount,
            'ticketCount' => $ticketCount,
            'approvedPayslips' => $approvedPayslips,
            'totalSales' => $ticketSales->total_sales ?? 0,
            'totalProfit' => $ticketSales->total_profit ?? 0,
        ]);
    }
}
