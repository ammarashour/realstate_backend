<?php

namespace Modules\Core\Database\factories\Location;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Entities\Location\LocationTownship;
use Modules\Core\Entities\Location\LocationCity;

class LocationTownshipFactory extends Factory
{
    protected $model = LocationTownship::class;

    public function definition()
    {
        return [
            'name' => $this->faker->city,
            'location_city_id' => LocationCity::factory(),
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
            'ordering' => 0,
            'status' => 0,
            'added_user_id' => 1,
        ];
    }
}