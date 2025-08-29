<?php

namespace Modules\Core\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SearchHistory extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'keyword', 'user_id', 'type', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_search_histories";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = 'psx_search_histories';
    const id = 'id';
    const userId = 'user_id';
    const keyword = 'keyword';
    const type = 'type';
    const isHomePageSearch = "is_home_page_search";

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\SearchHistoryFactory::new();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

}
