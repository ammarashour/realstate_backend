<?php

namespace Modules\Core\Entities\Menu;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Gate;

class VendorModule extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'title', 'lang_key', 'menu_id', 'sub_menu_id','is_not_from_sidebar','status','route_name', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_vendor_modules";
    public $incrementing = false;

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = "id";
    const title = "title";
    const langKey = "lang_key";
    const menuId = "menu_id";
    const subMenuId = "sub_menu_id";
    const isNotFromSidebar = "is_not_from_sidebar";
    const status = "status";
    const routeName = "route_name";
    const addedDate = "added_date";

    const tableName = "psx_vendor_modules";

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\LanguageStringFactory::new();
    }

    public static function t($key)
    {
        return VendorModule::tableName.".".$key;
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
        return collect(array_flip($abilities))->map(function ($index, $ability){
            return Gate::allows($ability, $this);
        });
    }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->authorizations(['update','delete','create']),
        );
    }
}
