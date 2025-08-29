<?php

namespace Modules\Core\Entities\Utilities;

use App\Models\PsModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UiType extends PsModel
{
    use HasFactory;

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    protected $fillable = [];

    protected $table = "psx_ui_types";

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\UiTypeFactory::new();
    }

    public function customize_header(){
        return $this->hasMany(CustomField::class);
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
