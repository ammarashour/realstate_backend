<?php

namespace Modules\Core\Entities\AvailableCurrency;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Database\factories\AvailableCurrencyFactory;

class AvailableCurrency extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'is_default', 'currency_symbol', 'currency_short_form', 'name', 'status', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = 'psx_available_currencies';

    const CREATED_AT = 'added_date';

    const UPDATED_AT = 'updated_date';

    const tableName = 'psx_available_currencies';

    const id = 'id';

    const status = 'status';

    const currencyShortForm = 'currency_short_form';

    const currencySymbol = 'currency_symbol';

    const name = 'name';

    const isDefault = 'is_default';

    const addedUserId = 'added_user_id';

    protected static function newFactory()
    {
        return AvailableCurrencyFactory::new();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function authorizations($abilities = [])
    {
        return collect(array_flip($abilities))->map(function ($index, $ability) {
            return Gate::allows($ability, $this);
        });
    }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->authorizations(['update', 'delete', 'create']),
        );
    }
}
