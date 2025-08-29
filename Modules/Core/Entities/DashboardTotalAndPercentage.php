<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DashboardTotalAndPercentage extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'total', 'percentage', 'type'];

    protected $table = "psx_dashboard_total_and_percentage_results";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\DashboardTotalAndPercentageFactory::new();
    }
}
