<?php


namespace Modules\Core\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserReportExport implements FromCollection, WithHeadings, WithMapping
{

    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::withCount(['purchased_item'])->orderBy('purchased_item_count', 'desc') // purcahsed item count
                ->with(['roles'])
                ->latest() // registered date
                ->orderBy('overall_rating', 'desc') // user rating
                ->get();
    }


    public function map($user) : array {
        return [
            $user->name,
            $user->email,
            $user->user_phone,
            $user->user_address,
            $user->user_about_me,
            $user->roles?$user->roles[0]->name:'Registered User',
            $user->status,
            $user->is_banned?$user->is_banned:'0',
            $user->overall_rating,
            $user->user_lat,
            $user->user_lng,
            $user->added_date->format('d-M-Y') . ' (' . $user->added_date->diffForHumans() . ')',
        ];
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Name", "Email", "Phone", "Address", "About Me","Role", "Status", "Is Banned", "Overall Rating", "Latitude", "Longitude", "Registered Date"];
    }

}
