<?php

namespace Modules\Core\Database\factories;

use Carbon\Carbon;
use App\Models\User;
use Modules\Core\Entities\Item;
use Modules\Core\Entities\Favourite;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavouriteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Core\Entities\Favourite::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'item_id' => Item::all()->random(),
            'user_id' => User::all()->random(),
            'added_user_id' => 1,
            'added_date' => Carbon::now(),
        ];
    }
}

