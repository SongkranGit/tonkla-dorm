<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\IServices\IReportService;
use App\IServices\IRoomCalculationService;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use App\Models\RoomStayHistory;
use App\Models\Room;
use Log;

class ReportService implements IReportService
{
    
    private $roomCalculationService;

    public function __construct(
        IRoomCalculationService $roomCalculationService
    )
    {
        $this->roomCalculationService = $roomCalculationService;
    }
    
    public function getMonthlyReport($month)
    {  
        $reports = [];
        $rooms = Room::orderBy('sort_order')->get();
        $start = Carbon::createFromFormat('d/m/Y H:i:s', '1/'.$month.' 00:00:00');
        $end = Carbon::createFromFormat('d/m/Y H:i:s', '1/'.$month.' 00:00:00')->endOfMonth();
        $dateInterval = \DateInterval::createFromDateString('1 day');
        $datePeriod = new \DatePeriod($start, $dateInterval, $end);
        $dates = collect();
        $total = 0;
        foreach ($datePeriod as $date) 
        {
            $dates->push($date);
        }
        foreach($rooms as $room)
        { 
            $reportItem = [];
            foreach ($datePeriod as $date) 
            {
                $roomPrintedResult = $this->getRoomPrintedResult($room->id, $date, 'room_printed');
                $roomCheckinPrintedResult = $this->getRoomPrintedResult($room->id, $date, 'room_printed_checkin');
                $roomCheckOutPrintedResult = $this->getRoomPrintedResult($room->id, $date, 'room_printed_checkout');
                
                $total += $roomPrintedResult['sumTotal'];
                $total += $roomCheckinPrintedResult['sumTotal'];
                $total += $roomCheckinPrintedResult['sumTotal'];

                array_push($reportItem,[
                    'date' => $date,
                    'items' => [
                        'roomPrintedResult' => $roomPrintedResult['result'],
                        'roomCheckinPrintedResult' => $roomCheckinPrintedResult['result'],
                        'roomCheckOutPrintedResult' => $roomCheckOutPrintedResult['result'],
                    ]
                ]);
            }
            array_push($reports,[
                'room_id' => $room->id,
                'room_no' => $room->room_no,
                'report_item' => $reportItem
            ]);
        }
        return [
            'reports' => $reports,
            'total' => $total,
            'dates' => $dates,
        ];
    }

    private function getRoomPrintedResult($id, $date, $type) 
    {
        $result = [];
        $sumTotal = 0;
        $printed = Activity::select(['id','properties','created_at','updated_at'])
                    ->where('subject_id',$id)
                    ->where('log_name',$type)
                    ->whereDate('created_at', '=', $date->format('Y-m-d'))
                    ->get();

        if ($printed != null && sizeof($printed) > 0) 
        {
            foreach($printed as $key => $item)
            {
                $activityRoom = json_decode($item->properties);
                if(isset($activityRoom->currentCheckedInRoomStayHistroy) 
                    && $activityRoom->currentCheckedInRoomStayHistroy != null)
                {
                    $total = 0;
                    
                    $activityRoom->currentCheckedInRoomStayHistroy->room = $activityRoom->room;
                    if ($type == 'room_printed')
                    {
                        $itemResult = $this->roomCalculationService->calculateTotal($activityRoom->currentCheckedInRoomStayHistroy);
                        $total = $itemResult['calculatedTotal'];
                    }
                    else if ($type =='room_printed_checkout')
                    {
                        $itemResult = $this->roomCalculationService->calculatePrintCheckout($activityRoom->currentCheckedInRoomStayHistroy, $activityRoom->currentCheckedInRoomStayHistroy->id);
                        $total = $itemResult['calculatedTotal'];
                    }
                    else if ($type =='room_printed_checkin')
                    {
                        $itemResult = $this->roomCalculationService->calculatePrintCheckin($activityRoom->currentCheckedInRoomStayHistroy);
                        $total = $itemResult['calculatedTotal'];
                    }

                    $sumTotal += $total;

                    array_push($result,[
                        'total' => $total,
                        'date' => $item->created_at,
                    ]);
                }
            }
        }

        return [
            'result' => $result,
            'sumTotal' => $sumTotal
        ];
    }
}