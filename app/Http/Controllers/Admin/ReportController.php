<?php

namespace App\Http\Controllers\Admin;

use App\Export\MonthlyExport;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Report\ProfitReportRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Response;
use App\IServices\IReportService;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private $reportService;

    public function __construct(
        IReportService $reportService
    )
    {
        $this->middleware(['auth', 'role:admin|owner']);
        $this->reportService = $reportService;
    }

    public function getMonthlyReport(Request $request)
    {
        $month = $request->query('month');
        if ($month == null) {
            $month = Carbon::now()->format('m/Y');
        }
        $reports = $this->reportService->getMonthlyReport($month);

        return view('admin.reports.monthly_report.index', [
            'month' => $month,
            'reports' => $reports,
        ]);
    }

    public function exportMonthlyExcelReport(Request $request)
    {
        return Excel::download(new MonthlyExport, 'MonthlyExcelExport.xlsx');
    }
}
