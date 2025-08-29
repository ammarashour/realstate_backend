<?php

namespace Modules\Core\Entities\Location;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Database\factories\Location\LocationCityFactory;
use Module\Core\Entities\Item;

class LocationCity extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'lat', 'lng', 'ordering', 'status', 'description', 'touch_count', 'is_featured', 'featured_date', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = 'psx_location_cities';

    const CREATED_AT = 'added_date';

    const UPDATED_AT = 'updated_date';

    const status = 'status';

    const tableName = 'psx_location_cities';

    const name = 'name';

    const description = 'description';

    const id = 'id';

    const addedUserId = 'added_user_id';

    protected static function newFactory()
    {
        return LocationCityFactory::new();
    }

    public static function t($key)
    {
        return LocationCity::tableName.'.'.$key;
    }

    public function township()
    {
        return $this->hasMany(LocationTownship::class);
    }

    public function item()
    {
        return $this->hasMany($this->Item::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function cityRelation()
    {
        return $this->hasMany(LocationCityInfo::class);
    }

    public function authorizations($abilities = [])
    {
        return collect(array_flip($abilities))->map(function ($index, $ability) {
            return Gate::allows($ability, $this);
        });
    }

    //    public function toArray()
    //    {
    //        return parent::toArray() + [
    //            'authorizations' => $this->authorizations(['update','delete','create'])
    //        ];
    //    }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->authorizations(['update', 'delete', 'create']),
        );
    }
}
