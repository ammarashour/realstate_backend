<?php

namespace Modules\Core\Entities;

use App\Models\User;
use Modules\Core\Entities\Localization\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubcategoryLanguageString extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'subcategory_id', 'language_id', 'key', 'value', 'added_user_id', 'added_date', 'updated_user_id', 'updated_date', 'updated_flag', 'created_at', 'updated_at'
    ];

    protected $table = 'psx_sub_category_language_strings';
    const tableName = 'psx_sub_category_language_strings';
    const id = "id";
    const subcategoryId = 'subcategory_id';
    const languageId = "language_id";
    const key = "key";
    const value = "value";
    const addedUserId = 'added_user_id';

    public function language(){
        return $this->belongsTo(Language::class);
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
