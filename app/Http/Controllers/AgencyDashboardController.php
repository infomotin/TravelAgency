<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payslip;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AgencyDashboardController extends Controller
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

        $dailyTicketStats = Ticket::where('agency_id', $agency->id)
            ->whereBetween('issue_date', [$month.'-01', date('Y-m-t', strtotime($month.'-01'))])
            ->selectRaw('DATE(issue_date) as day, COUNT(*) as tickets, SUM(fare + tax) as sales')
            ->groupBy(DB::raw('DATE(issue_date)'))
            ->orderBy('day')
            ->get();

        $ticketByAirline = Ticket::where('agency_id', $agency->id)
            ->whereBetween('issue_date', [$month.'-01', date('Y-m-t', strtotime($month.'-01'))])
            ->selectRaw('airline_id, COUNT(*) as tickets')
            ->groupBy('airline_id')
            ->with('airline')
            ->get();

        $dailyLabels = $dailyTicketStats->pluck('day')->map(fn ($d) => date('d', strtotime($d)))->all();
        $dailyTicketCounts = $dailyTicketStats->pluck('tickets')->all();
        $dailySales = $dailyTicketStats->pluck('sales')->all();

        $airlineLabels = $ticketByAirline->map(fn ($row) => optional($row->airline)->name ?: 'Unknown')->all();
        $airlineTicketCounts = $ticketByAirline->pluck('tickets')->all();

        return view('agency.dashboard', [
            'title' => 'Agency Dashboard',
            'currentAgency' => $agency,
            'month' => $month,
            'employeeCount' => $employeeCount,
            'ticketCount' => $ticketCount,
            'approvedPayslips' => $approvedPayslips,
            'totalSales' => $ticketSales->total_sales ?? 0,
            'totalProfit' => $ticketSales->total_profit ?? 0,
            'dailyLabels' => $dailyLabels,
            'dailyTicketCounts' => $dailyTicketCounts,
            'dailySales' => $dailySales,
            'airlineLabels' => $airlineLabels,
            'airlineTicketCounts' => $airlineTicketCounts,
        ]);
    }
}
