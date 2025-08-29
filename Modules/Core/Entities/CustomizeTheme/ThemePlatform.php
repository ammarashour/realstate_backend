<?php

namespace Modules\Core\Entities\CustomizeTheme;

use App\Models\PsModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThemePlatform extends PsModel
{
    use HasFactory;

    protected $table = "psx_theme_platform";
    public $incrementing = false;
    protected $timestamp = false;
    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';
    const id = 'id';
    protected $fillable = [
        'id',
        'name',
        'added_date',
        'added_user_id',
        'updated_date',
        'updated_user_id',
        'updated_flag'
    ];

    public function theme_screen()
    {
        return $this->hasMany(ThemeScreen::class, ThemeScreen::platform_id);
    }
}
