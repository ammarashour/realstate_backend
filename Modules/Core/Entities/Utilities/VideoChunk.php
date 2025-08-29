<?php

namespace Modules\Core\Entities\Utilities;

use App\Models\PsModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class VideoChunk extends PsModel
{
    use HasFactory;

    protected $table = 'psx_video_chunks'; // Define table name
    public $incrementing = false; // Disable auto-incrementing ID
    protected $keyType = 'string'; // Use string (UUID) as primary key
    protected $fillable = ['id', 'file_name', 'offset', 'added_date','added_user_id','updated_date','updated_user_id','updated_flag'];
    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';
    
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function upload()
    {
        return $this->belongsTo(VideoUpload::class, 'file_name', 'file_name');
    }
}
