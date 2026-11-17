<?php

namespace App\Http\Controllers\Admin;

use App\IServices\IApprovalPrivilegeService;
use App\Models\ApprovalPrivilege;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApprovalPrivilegeController extends Controller
{
    private $approvalPrivilegeService;

    public function __construct(IApprovalPrivilegeService $approvalPrivilegeService)
    {
        $this->approvalPrivilegeService = $approvalPrivilegeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $approvalPrivileges = $this->approvalPrivilegeService->getAllApprovalPrivileges();
        return view('admin.privilege.index' , compact('approvalPrivileges') );
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function approve(Request $request, $id)
    {
        if($id != null && $request->has('can_approve')){
            $approvalPrivilege = ApprovalPrivilege::where('user_id' , $id )->first();
            $approvalPrivilege->can_approve = $request->can_approve;
            $approvalPrivilege->save();
        }
        return response()->json(['response' => 'success' ]);
    }
}
