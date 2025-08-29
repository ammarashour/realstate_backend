<?php

namespace Modules\Core\Entities\Item;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\Item;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Gate;

class PaidItemHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id', 'item_id', 'start_date', 'start_timestamp', 'end_date', 'end_timestamp', 'amount', 'payment_method', 'status', 'razor_id', 'purchased_id', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_paid_item_histories";

    protected $dates = ['start_date', 'end_date'];

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_paid_item_histories";
    const id = "id";
    const itemId = "item_id";
    const startDate = "start_date";
    const startTimestamp = "start_timestamp";
    const endDate = "end_date";
    const endTimestamp = "end_timestamp";
    const amount = "amount";
    const paymentMethod = "payment_method";
    const razorId = "razor_id";
    const purchasedId = "purchased_id";
    const status = "status";
    const addedDate = 'added_date';
    const promotedDays = 'promoted_days';
    const addedUserId = "added_user_id";
    const deletedAt = "deleted_at";

    protected static function newFactory()
    {
        // return \Modules\ItemPromotion\Database\factories\PaidItemHistoryFactory::new();
    }

    public static function t($key)
    {
        return PaidItemHistory::tableName.'.'.$key;
    }

    public function item()
    {
        return $this->belongsTo(Item::class)->with('category', 'subcategory','city','township','owner');
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function authorizations($abilities = []){
        return collect(array_flip($abilities))->map(function ($index, $ability){
            return Gate::allows($ability, $this);
        });
    }

//   public function toArray()
//   {
//       return parent::toArray() + [
//               'authorizations' => $this->authorizations(['update','delete','create'])
//           ];
//   }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->authorizations(['update','delete','create']),
        );
    }
}
