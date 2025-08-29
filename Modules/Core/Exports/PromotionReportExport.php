<?php

namespace Modules\Core\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Core\Entities\Item\PaidItemHistory;

class PromotionReportExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $relation = ['item'];
        return PaidItemHistory::with($relation)
            ->latest()
            ->get();
    }

    public function getStatus($start_date, $end_date)
    {
        $today_date = date('Y-m-d H:i:s');
        //        $start_date = date($start);
        //        $end_date = date($end);
        $status = 0;
        if ($today_date >= $start_date && $today_date <= $end_date)
            $status = 'on going';
        if ($today_date > $start_date && $today_date > $end_date)
            $status = 'completed';
        if ($today_date < $start_date && $today_date < $end_date)
            $status = 'not yet started';
        return $status;
    }

    public function map($paidItem): array
    {
        $startDate = date('Y-m-d H:i:s', strtotime($paidItem->start_date));
        $endDate = date('Y-m-d H:i:s', strtotime($paidItem->end_date));
        $status = $this->getStatus($startDate, $endDate);

        return [
            $paidItem->item ? $paidItem->item->title : "",
            date('Y-m-d H:i:s', strtotime($paidItem->start_date)),
            date('Y-m-d H:i:s', strtotime($paidItem->end_date)),
            $status,
            empty($paidItem->amount) ? '0' : $paidItem->amount,
            $paidItem->payment_method
        ];
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Item", "Start Date", "End Date", "Status", "Amount", "Payment Method"];
    }
}
