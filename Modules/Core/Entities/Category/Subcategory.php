<?php

namespace Modules\Core\Entities\Category;

use App\Models\User;
use Modules\Core\Entities\Item;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Entities\CoreImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Session;
use Modules\Core\Database\factories\Category\SubcategoryFactory;
use Modules\Core\Entities\SubcategoryLanguageString;
use Modules\Core\Http\Facades\LanguageFacade;

class Subcategory extends Model
{

    use HasFactory;

    protected $fillable = ['id', 'name','category_name', 'category_id', 'ordering', 'status', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_subcategories";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const status = "status";
    const id = 'id';
    const tableName = 'psx_subcategories';
    const name = 'name';
    const categoryId = "category_id";
    const addedDate = 'added_date';
    const ordering = 'ordering';
    const addedUserId = 'added_user_id';
    const updatedUserId = 'updated_user_id';

    protected static function newFactory()
    {
        return SubcategoryFactory::new();
    }

    public static function t($key) {
        return Subcategory::tableName. "." . $key;
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function defaultPhoto(){
        return $this->hasOne(CoreImage::class, 'img_parent_id','id')->where('img_type','subCategory-cover');
    }

    public function defaultIcon(){
        return $this->hasOne(CoreImage::class, 'img_parent_id','id')->where('img_type','subCategory-icon');
    }

    public function subCategoryLanguageString()
    {
        // return $this->hasMany(CategoryLanguageString::class, 'key', 'name');
        $conds = ['symbol' => $this->getLanguageSymbol()];
        $languageId = LanguageFacade::get(null, $conds)->id;
        return $this->hasOne(SubcategoryLanguageString::class, 'key', 'name')->where('language_id', $languageId);
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

    public function icon() {
        return $this->hasOne(CoreImage::class, 'img_parent_id')
                    ->where('img_type', 'subCategory-icon');
    }

    public function cover() {
        return $this->hasOne(CoreImage::class, 'img_parent_id')
                    ->where('img_type', 'subCategory-cover');
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

    public function authorizations($abilities = []){
        return collect(array_flip($abilities))->map(function ($index, $ability){
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
            get: fn ($value) => $this->authorizations(['update','delete','create']),
        );
    }

}
