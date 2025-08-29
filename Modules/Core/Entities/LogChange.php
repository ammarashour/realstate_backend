<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogChange extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'delay', 'added_date', 'updated_date'];

    protected $table = "psx_log_changes";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\ApiCallSettingFactory::new();
    }
}
