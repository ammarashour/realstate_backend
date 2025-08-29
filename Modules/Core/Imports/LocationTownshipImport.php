<?php

namespace Modules\Core\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Modules\Core\Entities\CoreImage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Entities\Location\LocationCity;
use Maatwebsite\Excel\Concerns\Importable;
use Modules\Core\Entities\Location\LocationTownship;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LocationTownshipImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
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
            $location_city_id = LocationCity::where('name', $row['city_name'])->first()->id;
            // save township
            $township = new LocationTownship();
            $township->name = $row['name'];
            $township->location_city_id = $location_city_id;
            $township->lat = $row['lat'];
            $township->lng = $row['lng'];
            $township->ordering = !empty($row['ordering']) ? $row['ordering'] : 1;
            $township->status = isset($row['status']) && ($row['status'] === 0 || $row['status'] === 1) ? $row['status'] : 1;

            $township->added_user_id = Auth::user()->id;
            $township->save();
        }
    }

    /**
     * Validation
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|unique:psx_location_townships,name,',
            'lat' => 'required|numeric|max:90|min:-90',
            'lng' => 'required|numeric|max:180|min:-180',
            'city_name' => 'required|exists:psx_location_cities,name',
        ];
    }

    /**
     * custom validation attributes
     * @return array
     */
    public function customValidationAttributes()
    {
        return [
            'name' => 'township name',
            'lat' => 'latitude',
            'lng' => 'longitude'
        ];
    }
}
