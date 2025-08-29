<?php

namespace Modules\Core\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Modules\Core\Entities\AvailableCurrency\AvailableCurrency;

class AvailableCurrencyImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            // save currency
            $currency = new AvailableCurrency();
            $currency->name = $row['name'];
            $currency->currency_short_form = $row['currency_short_form'];
            $currency->currency_symbol = $row['currency_symbol'];
            $currency->status = $row['status'];
            $currency->is_default = $row['is_default'];
            $currency->added_user_id = Auth::user()->id;
            $currency->save();
        }
    }

    /**
     * Validation
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            "currency_short_form" => "required",
            "currency_symbol" => "required",
            "status" => "required",
            "is_default" => "required",
        ];
    }
}
