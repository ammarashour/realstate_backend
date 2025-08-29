<?php

namespace Modules\Core\Entities;

use App\Models\PsModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DynamicLink extends PsModel
{
    use HasFactory;

    public $timestamps = false;
    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';
    protected $table = 'psx_dynamic_links';
    protected $fillable = [
        'short_code',
        'parameters',
        'type',
        'added_date',
        'added_user_id',
        'updated_date',
        'updated_user_id',
        'updated_flag',
    ];

    const tableName = 'psx_dynamic_links';
    const shortCode = 'short_code';
    const type = 'type';
    const parameters = 'parameters';
    const addedUserId = "added_user_id";
    const updatedUserId = "updated_user_id";
}
