<?php

namespace Modules\Core\Entities\Financial;

use App\Models\PsModel;
use App\Models\User;
use Modules\Core\Entities\Item;
use App\Traits\VendorAuthorizationTrait;
use Modules\Core\Constants\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Database\factories\Financial\ItemCurrencyFactory;

class ItemCurrency extends PsModel
{
    use HasFactory, VendorAuthorizationTrait;

    protected $fillable = ['id', 'currency_symbol', 'currency_short_form', 'status', 'is_default','added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_currencies";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_currencies";
    const id = 'id';
    const isDefault = "is_default";
    const status = "status";
    const currencyShortForm = 'currency_short_form';
    const currencySymbol = 'currency_symbol';
    const addedUserId = 'added_user_id';

    public function __construct() {
        $this->vendorModule = Constants::vendorCurrencyModule;
    }

    protected static function newFactory()
    {
        return ItemCurrencyFactory::new();
    }

    public static function t($key) {
        return ItemCurrency::tableName. "." . $key;
    }

    public function item(){
        return $this->hasMany(Item::class);
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }

}
