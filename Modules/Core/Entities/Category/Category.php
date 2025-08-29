<?php

namespace Modules\Core\Entities\Category;

use App\Models\User;
use Modules\Core\Entities\Touch;
use Illuminate\Support\Facades\DB;
// use Modules\Core\Database\factories\CategoryFactory;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Entities\CoreImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Modules\Core\Entities\Financial\TransactionCount;
use Modules\Core\Http\Facades\LanguageFacade;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\Core\Entities\Localization\CategoryLanguageString;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;
use Modules\Core\Entities\Item\Item;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'ordering', 'status', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = 'psx_categories';

    const CREATED_AT = 'added_date';

    const UPDATED_AT = 'updated_date';

    const tableName = 'psx_categories';

    const name = 'name';

    const id = 'id';

    const ordering = 'ordering';

    const status = 'status';

    const addedDate = 'added_date';

    const addedUserId = 'added_user_id';

    const updatedDate = 'updated_date';

    const updatedUserId = 'updated_user_id';

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\Category\CategoryFactory::new();
    }

    public static function t($key)
    {
        return Category::tableName.'.'.$key;
    }

    public function subcategory()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function item()
    {
        return $this->hasMany(Item::class);
    }

    public function category_touch()
    {
        return $this->hasMany(Touch::class, 'type_id', 'id')->where('type_name', 'Category');
    }

    public function categoryLanguageString()
    {
        // return $this->hasMany(CategoryLanguageString::class, 'key', 'name');
        $conds = ['symbol' => $this->getLanguageSymbol()];
        $languageId = LanguageFacade::get(null, $conds)->id;
        return $this->hasOne(CategoryLanguageString::class, 'key', 'name')->where('language_id', $languageId);
    }

    private function getLanguageSymbol()
    {
        if(!empty(request()->query('language_symbol'))){
            return request()->query('language_symbol');
        }

        if(isset($_COOKIE['activeLanguage'])){
            return $_COOKIE['activeLanguage'];
        }
        
        return Session::get('applocale') ?? 'en';
    }

    public function itemCount()
    {
        return $this->hasMany(Item::class, 'category_id', 'id')->whereIn(Item::status, [1, 4]);
    }

    public function defaultPhoto()
    {
        return $this->hasOne(CoreImage::class, 'img_parent_id', 'id')->where('img_type', 'category-cover');
    }

    public function defaultIcon()
    {
        return $this->hasOne(CoreImage::class, 'img_parent_id', 'id')->where('img_type', 'category-icon');
    }

    public function icon()
    {
        return $this->hasOne(CoreImage::class, 'img_parent_id')
            ->where('img_type', 'category-icon');
    }

    public function cover()
    {
        return $this->hasOne(CoreImage::class, 'img_parent_id')
            ->where('img_type', 'category-cover');
    }

    public function transaction()
    {
        return $this->hasMany(TransactionCount::class);
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

    public function languageString()
    {
        return $this->hasOne(CategoryLanguageString::class) //->where('language_id', '=', '1')
            ->where('key', 'category_name_00008');
    }

    public function scopeWithLanguageString($query, $language = '1')
    {
        return $query->select(
            'psx_categories.*',
            DB::raw('COALESCE(psx_category_language_strings.value, psx_categories.name) as name'),
            'psx_categories.name as key'
        )
            ->leftJoin('psx_category_language_strings', function ($join) use ($language) {
                $join->on('psx_categories.name', '=', 'psx_category_language_strings.key')
                    ->where('psx_category_language_strings.language_id', '=', $language);
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
            get: fn ($value) => $this->authorizations(['update', 'delete', 'create']),
        );
    }
}
