<?php

namespace Modules\Core\Http\Services;

use App\Http\Services\PsService;
use Modules\Core\Entities\Favourite;

class FeedbackService extends PsService
{

    public function getFavourites($relation = null){
        $favourites = Favourite::when($relation, function ($q, $relation){
                                    $q->with($relation);
                                })
                                ->latest()->get();
        return $favourites;
    }

    public function index(){
        $favouriteRelation = ['item', 'user'];
        $favourites = $this->getFavourites($favouriteRelation);
        $dataArr = [
            'favourites' => $favourites
        ];
        return $dataArr;
    }
}
