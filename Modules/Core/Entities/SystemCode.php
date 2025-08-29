<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemCode extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $table = "psx_system_codes";

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $dates = [
        'start_date',
        'end_date'
    ];

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\FreeTrialDurationFactory::new();
    }
}
