<?php

namespace Modules\Core\Database\factories\Location;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Entities\Location\LocationCityInfo;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class LocationCityInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = LocationCityInfo::class;

    public function definition()
    {
        return [
            "location_city_id" => "1",
            "core_keys_id" => "loc00001",
            "value" => "Testing",
            "ui_type_id" => "uit00001",
            "added_date" => "",
            "added_user_id" => 1,
            "updated_date" => "",
            "updated_user_id" => "",
            "updated_flag" => ""
        ];
    }
}
