<?php

namespace Modules\Core\Entities\Utilities;

use App\Models\PsModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivatedFileName extends PsModel
{
    use HasFactory;

    protected $fillable = [];

    protected $table = "psx_activated_file_names";

    const id = "id";
    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\ActivatedFileNameFactory::new();
    }
}
