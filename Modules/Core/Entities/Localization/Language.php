<?php

namespace Modules\Core\Entities\Localization;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\Localization\LanguageString;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Language extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'symbol', 'name', 'status', 'is_publish', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_languages";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_languages";
    const id = "id";
    const status = "status";
    const symbol = "symbol";
    const name = "name";
    const isPublish = 'is_publish';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\LanguageFactory::new();
    }

    public function language_string(){
        return $this->hasMany(LanguageString::class);
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
