<?php

namespace Modules\Core\Entities\CustomizeTheme;

use App\Models\PsModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThemeScreen extends PsModel
{
    use HasFactory;

    protected $table = "psx_theme_screens";
    protected $keyType = 'string';
    public $incrementing = false;
    protected $timestamp = false;
    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';
    const name = 'name';
    const platform_id = "platform_id";
    const is_publish = "is_publish";
    protected $fillable = [
        'id',
        'name',
        'platform_id',
        'is_publish',
        'added_date',
        'added_user_id',
        'updated_date',
        'updated_user_id',
        'updated_flag'
    ];

    public function theme_platforms()
    {
        return $this->belongsTo(ThemePlatform::class, self::platform_id);
    }
}
