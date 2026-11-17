<?php

namespace App\Http\Controllers;

use Redirect;
use Response;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\Equipment\EquipmentUpdateRequest;
use App\Http\Requests\Equipment\EquipmentStoreRequest;
use App\Http\Requests\Equipment\ServeEquipmentRequest;

use App\IServices\IEquipmentService;
use App\Models\Equipment;
use App\Models\Room;

class EquipmentController extends Controller
{

    private $equipmentService;

    public function __construct(
        IEquipmentService $equipmentService
    )
    {
        $this->middleware('auth');
        $this->equipmentService = $equipmentService;
    }

    public function index(Request $request)
    {
        $equipments = $this->equipmentService->getAllEquipmentWithPagination($request);
        return view('equipment.index', [
            'equipments' => $equipments
        ]);
    }

    public function create()
    {
        return view('equipment.create');
    }

    public function store(EquipmentStoreRequest $request)
    {
        $equipment = $this->equipmentService->create($request, true);
        return Redirect('equipment');
    }

    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        $rooms = Room::where('branch_id' , session('current_branch_id'))->orderBy('id')->get();
        return view('equipment.edit', [ 
            'rooms'=> $rooms,
            'equipment' => $equipment
        ]);
    }

    public function update(EquipmentUpdateRequest $request, $id)
    {
        $equipment = $this->equipmentService->updateEquipment($request, $id);
        return Redirect('equipment');
    }

    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->delete();
        return Response::json();
    }
}
