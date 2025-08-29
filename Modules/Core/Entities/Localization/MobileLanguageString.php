<?php

namespace Modules\Core\Entities\Localization;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Entities\Localization\MobileLanguage;

class MobileLanguageString extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'mobile_language_id', 'key', 'value', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_mobile_language_strings";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';
    const key = 'key';
    const value = 'value';
    const mobileLanguageId = 'mobile_language_id';
    const tableName = "psx_mobile_language_strings";
    const addedUserId = "added_user_id";


    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\MobileLanguageStringFactory::new();
    }

    public function mobileLanguage(){
        return $this->belongsTo(MobileLanguage::class);
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
