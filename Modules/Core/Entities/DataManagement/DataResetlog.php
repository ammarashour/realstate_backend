<?php

namespace Modules\Core\Entities\DataManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataResetlog extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $table = "psx_data_reset_logs";

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected static function newFactory()
    {
        return \Modules\DemoDataDeletion\Database\factories\DataResetlogFactory::new();
    }
}
