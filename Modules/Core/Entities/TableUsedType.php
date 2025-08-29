<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TableUsedType extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $table = "psx_table_used_types";

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\TableUsedTypeFactory::new();
    }
}
