<?php

namespace App\IServices;

use Illuminate\Http\Request;

interface IReportService
{
    public function getMonthlyReport($month);
}