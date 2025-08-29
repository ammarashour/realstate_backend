<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Installer extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $table = "psx_installer";

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\InstallerFactory::new();
    }
}
