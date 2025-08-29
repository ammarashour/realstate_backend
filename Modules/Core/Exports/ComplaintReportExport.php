<?php


namespace Modules\Core\Exports;

use Modules\Core\Entities\Item\ComplaintItem;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ComplaintReportExport implements FromCollection, WithHeadings, WithMapping, WithCustomCsvSettings
{
    use Exportable;

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'use_bom' => false,
            'output_encoding' => 'ISO-8859-1',
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ComplaintItem::with(['item', 'reported_user', 'reported_item_status'])
        ->latest()
        ->get();
    }

    public function map($item) : array {
        return [
            $item->item?$item->item->title:'',
            $item->reported_user?$item->reported_user->name:'',
            $item->text_note,
            $item->reported_item_status?$item->reported_item_status->title:'',
            $item->added_date->format('M, d Y H:i:s A'),
        ];
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Item", "User Name", "Description", "Status", "Date"];
    }

}
