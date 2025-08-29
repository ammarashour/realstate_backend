<?php

namespace Modules\Core\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoreImage extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'img_parent_id', 'img_type', 'img_path', 'img_width', 'img_height', 'img_desc', 'ordering', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    protected $table = "psx_core_images";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = 'id';
    const imgParentId = "img_parent_id";
    const imgType = "img_type";
    const imgWidth = "img_width";
    const imgHeight = "img_height";
    const ordering = "ordering";
    const imgPath = "img_path";
    const imgDesc = "img_desc";

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\CoreImageFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
