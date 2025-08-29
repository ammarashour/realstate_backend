<?php

namespace Modules\Core\Entities\Localization;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Gate;

class MobileLanguage extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'symbol', 'language_code', 'country_code', 'name', 'code', 'status', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_mobile_languages";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_mobile_languages";
    const status = "status";
    const id = 'id';
    const enable = 'enable';
    const mobileLanguageId = 'mobile_language_id';
    const symbol = 'symbol';
    const name = 'name';
    const languageCode = 'language_code';
    const countryCode = 'country_code';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\MobileLanguageFactory::new();
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
