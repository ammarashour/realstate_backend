<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DashboardRecentRegisteredUser extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $table = "psx_dashboard_recent_registered_users";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\DashboardRecentRegisteredUserFactory::new();
    }
}
