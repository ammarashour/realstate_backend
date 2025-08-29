<?php

namespace Modules\Core\Entities\Item;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\Financial\ItemCurrency;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'title', 'price', 'currency_id', 'post_count', 'status', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_packages";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = 'psx_packages';
    const id = 'id';
    const title = 'title';
    const price = 'price';
    const currency_id = 'currency_id';
    const post_count = 'post_count';
    const status = 'status';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\Package::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function currency(){
        return $this->belongsTo(ItemCurrency::class, 'currency_id', 'id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function authorizations($abilities = []){
        return collect(array_flip($abilities))->map(function ($index, $ability){
            return Gate::allows($ability, $this);
        });
    }

//    public function toArray()
//    {
//        return parent::toArray() + [
//                'authorizations' => $this->authorizations(['update','delete','create'])
//            ];
//    }

}
