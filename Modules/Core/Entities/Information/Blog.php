<?php

namespace Modules\Core\Entities\Information;

use App\Models\PsModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Database\Factories\Information\BlogFactory;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Entities\Location\LocationCity;

class Blog extends PsModel
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'description',  'location_city_id', 'shop_id', 'status', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = 'psx_blogs';

    const CREATED_AT = 'added_date';

    const UPDATED_AT = 'updated_date';

    const tableName = 'psx_blogs';

    const id = 'id';

    const name = 'name';

    const description = 'description';

    const locationCityId = 'location_city_id';

    const status = 'status';

    const addedDate = 'added_date';

    const addedUserId = 'added_user_id';

    protected static function newFactory()
    {
        return BlogFactory::new();
    }

    public function city()
    {
        return $this->belongsTo(LocationCity::class, 'location_city_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function cover()
    {
        return $this->hasOne(CoreImage::class, 'img_parent_id', 'id')->where('img_type', 'blog');
    }
}
