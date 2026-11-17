<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\IServices\IRoomApprovalService;
use App\Models\RoomApproval;
use Illuminate\Http\Request;

class RoomApprovalController extends Controller
{
    private $roomApprovalService;

    public function __construct(IRoomApprovalService $roomApprovalService)
    {
        $this->roomApprovalService = $roomApprovalService;
    }

    public function index()
    {
        $roomApprovals = $this->roomApprovalService->getAllRequestForApprovals();
        return view('admin.approval.index', compact('roomApprovals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $roomId = $request->room_id ;
        $requestCheckingType = $request->request_checking_type;
        $this->roomApprovalService->requestForApprove($roomId , $requestCheckingType);
        return response()->json(['response' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $roomId)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function approve(Request $request, $roomId)
    {
        if($roomId != null && $request->has('checking_type')){
            $roomApproval = RoomApproval::where('room_id' , $roomId)->first();
            if($roomApproval != null){
                if($request->checking_type === 'check_in'){
                    $roomApproval->is_checkin_approved = true;
                    $roomApproval->is_request_approve_checkin = false;
                }else{
                    $roomApproval-> is_checkout_approved = true;
                    $roomApproval->is_request_approve_checkout = false;
                }
                $roomApproval->save();
            }
        }

        return response()->json(['response' => 'success' ]);
    }

    public function resetStatus(Request $request, $roomId)
    {
        if($roomId != null && $request->has('checking_type')){
            $roomApproval = RoomApproval::where('room_id' , $roomId)->first();
            if($roomApproval != null){
                if($request->checking_type === 'check_in'){
                    $roomApproval->is_checkin_approved = false;
                }else{
                    $roomApproval-> is_checkout_approved = false;
                }
                $roomApproval->save();
            }
        }

        return response()->json(['response' => 'success' ]);
    }


    public function requestForApprove(Request $request)
    {
        $roomId = $request->room_id ;
        $requestCheckingType = $request->request_checking_type;
        $this->roomApprovalService->requestForApprove($roomId , $requestCheckingType);
        return response()->json(['response' => 'success' , 'roomId'=> $roomId]);
    }
}
