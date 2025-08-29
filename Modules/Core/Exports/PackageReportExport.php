<?php

namespace Modules\Core\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Core\Entities\Item\PackageBoughtTransaction;

class PackageReportExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $relation = ['user', 'package'];
        return PackageBoughtTransaction::
        leftJoin('psx_payment_infos','psx_package_bought_transactions.package_id','=','psx_payment_infos.id')
        ->leftjoin('users','psx_package_bought_transactions.user_id','=','users.id')
        ->select('psx_package_bought_transactions.*','users.name as user_name','psx_payment_infos.value')
        ->with($relation)
        ->latest()
        ->get();
    }

    public function map($item) : array {
        return [
            $item->user?$item->user->name:'',
            $item->package?$item->package->value:'',
            $item->price,
            $item->payment_method,
            $item->added_date->format('Y/m/d'),
        ];
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["User", "Package", "Amount", "Payment Method", "Date"];
    }

}
