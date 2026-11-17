<?php


namespace App\Export;
use App\Models\Room;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class MonthlyExport implements FromCollection
{

    /**
     * @return Collection
     */
    public function collection()
    {
        return  Room::all();
    }


}