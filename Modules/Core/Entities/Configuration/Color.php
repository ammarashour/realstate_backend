<?php

namespace Modules\Core\Entities\Configuration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Color extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'key', 'value', 'title', 'fe_color', 'mb_color', 'added_date'];

    protected $table = "psx_colors";


    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = "psx_colors";
    const id = "id";
    const key = "key";
    const value = "value";
    const title = "title";
    const feColor = "fe_color";
    const mbColor = "mb_color";
    const addedDate = 'added_date';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\ColorFactory::new();
    }
}
