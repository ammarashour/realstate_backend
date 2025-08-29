<?php

namespace Modules\Core\Imports;

use Illuminate\Support\Collection;
use Modules\Core\Entities\Location\LocationCity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Modules\Core\Entities\CoreImage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LocationCityImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // save city
            $city = new LocationCity();
            $city->name = $row['name'];
            $city->lat = $row['lat'];
            $city->lng = $row['lng'];
            $city->ordering = !empty($row['ordering']) ? $row['ordering'] : 1;
            $city->status = isset($row['status']) && ($row['status'] == 0 || $row['status'] == 1) ? $row['status'] : 1;

            $city->added_user_id = Auth::user()->id;
            $city->save();
        }
    }

    /**
     * Validation
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|unique:psx_location_cities,name,',
            'lat' => 'required|numeric|max:90|min:-90',
            'lng' => 'required|numeric|max:180|min:-180',
        ];
    }

    /**
     * custom validation attributes
     * @return array
     */
    public function customValidationAttributes()
    {
        return [
            'name' => 'city name',
            'lat' => 'latitude',
            'lng' => 'longitude',
        ];
    }
}
