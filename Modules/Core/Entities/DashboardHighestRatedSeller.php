<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DashboardHighestRatedSeller extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $table = "psx_dashboard_highest_rated_sellers";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const name = "name";
    const email = "email";
    const phone = "phone";
    const overallRating = "overall_rating";

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\DashboardHighestRatedSellerFactory::new();
    }
}
