<?php

namespace Modules\Core\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\CoreImage;

class PsxLandingPage extends Model
{
    use HasFactory;

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';
    const id = 'id';

    protected $fillable = ['id','title','description','default_landing_page_color','gps_link','ios_link','yt_link','fb_link','tw_link'];

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\PsxLandingPageFactory::new();
    }

    public function landing_logo() {
        return $this->hasMany(CoreImage::class, 'img_parent_id')->where('img_type', 'landing-logo');
    }

    public function landing_cover() {
        return $this->hasMany(CoreImage::class, 'img_parent_id')->where('img_type', 'landing-cover');
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
