<?php


namespace Modules\Core\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Core\Entities\Support\Contact;

class ContactUsMessageExport implements FromCollection, WithHeadings
{

    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = Contact::select('contact_name', 'contact_email', 'contact_phone', 'contact_message', 'added_date')
                ->latest()
                ->get();
        return $data;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Name", "Email", "Phone","Message", "Date"];
    }

}
