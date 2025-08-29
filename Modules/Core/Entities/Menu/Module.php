<?php

namespace Modules\Core\Entities\Menu;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\Core\Database\factories\Menu\ModuleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'title', 'route_name', 'menu_id', 'lang_key', 'sub_menu_id', 'is_not_from_sidebar','status', 'updated_user_id'];

    protected $table = "psx_modules";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';
    const title = 'title';
    const menuId = "menu_id";
    const subMenuId = "sub_menu_id";
    const status = "status";
    const routeName = "route_name";
    const isNotFromSidebar = 'is_not_from_sidebar';

    protected static function newFactory()
    {
        return ModuleFactory::new();
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

//    public function toArray()
//    {
//        return parent::toArray() + [
//                'authorizations' => $this->authorizations(['update','delete','create'])
//            ];
//    }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->authorizations(['update','delete','create']),
        );
    }
}
