<?php

namespace Modules\Core\Entities\Configuration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'setting_env', 'setting', 'ref_selection'];

    protected $table = "psx_settings";

    const tableName = 'psx_settings';
    const id = 'id';
    const settingEnv = 'setting_env';
    const setting = 'setting';
    const refSelection = 'ref_selection';

    protected static function newFactory(){}

}
