<?php

namespace Modules\Core\Entities\Configuration;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\CoreImage;

class BackendSetting extends Model
{
    use HasFactory;

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    // protected $fillable = ['id', 'sender_name', 'vendor_setting', 'sender_email', 'receive_email', 'fcm_api_key','map_key', 'app_token', 'topics', 'topics_fe', 'smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_encryption', 'email_verification_enabled', 'user_social_info_override', 'landscape_width', 'potrait_height', 'square_height', 'landscape_thumb_width', 'potrait_thumb_height', 'square_thumb_height', 'landscape_thumb2x_width', 'potrait_thumb2x_height', 'square_thumb2x_height', 'landscape_thumb3x_width', 'potrait_thumb3x_height', 'square_thumb3x_height', 'dun_link_key', 'dyn_link_url', 'dyn_link_package_name', 'dyn_link_domain', 'dyn_link_deep_url', 'ios_boundle_id', 'ios_appstore_id', 'backend_version_id', 'slow_moving_item_limit','date_format', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];
    protected $fillable = [
        "sender_name",
        "sender_email",
        "receive_email",
        "fcm_api_key",
        "map_key",
        "app_token",
        "topics",
        "topics_fe",
        "smtp_host",
        "smtp_port",
        "smtp_user",
        "smtp_pass",
        "smtp_encryption",
        "email_verification_enabled",
        "user_social_info_override",
        "landscape_width",
        "potrait_height",
        "square_height",
        "landscape_thumb_width",
        "potrait_thumb_height",
        "square_thumb_height",
        "landscape_thumb2x_width",
        "potrait_thumb2x_height",
        "square_thumb2x_height",
        "landscape_thumb3x_width",
        "potrait_thumb3x_height",
        "square_thumb3x_height",
        "dyn_link_key",
        "dyn_link_url",
        "dyn_link_package_name",
        "dyn_link_domain",
        "dyn_link_deep_url",
        "ios_boundle_id",
        "ios_appstore_id",
        "backend_version_no",
        "slow_moving_item_limit",
        "search_item_limit",
        "search_user_limit",
        "search_category_limit",
        "date_format",
        "watermask_image_size",
        "font_size",
        "position",
        "upload_setting",
        "opacity",
        "watermask_title",
        "is_watermask",
        "watermask_angle",
        "padding",
        "is_google_map",
        "is_open_street_map",
        "fe_setting",
        "vendor_setting",
        'added_date',
        'added_user_id',
        'updated_date',
        'updated_user_id',
        'updated_flag'
    ];

    protected $table = "psx_backend_settings";
    const tableName = "psx_backend_settings";
    const id = 'id';
    const mapKey = 'map_key';
    const isGoogleMap = 'is_google_map';
    const isOpenStreetMap = 'is_open_street_map';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\BackendSettingFactory::new();
    }

    public function backend_logo() {
        return $this->hasOne(CoreImage::class, 'img_parent_id', 'id')->where('img_type', 'backend-logo');
    }

    public function fav_icon() {
        return $this->hasOne(CoreImage::class, 'img_parent_id', 'id')->where('img_type', 'fav-icon');
    }

    public function backend_login_image() {
        return $this->hasOne(CoreImage::class, 'img_parent_id', 'id')->where('img_type', 'backend-login-image');
    }

    public function backend_meta_image() {
        return $this->hasOne(CoreImage::class, 'img_parent_id', 'id')->where('img_type', 'backend-meta-image');
    }

    public function backend_water_mask_image() {
        return $this->hasOne(CoreImage::class, 'img_parent_id', 'id')->where('img_type', 'backend-water-mask-image');
    }
    public function water_mask_background() {
        return $this->hasOne(CoreImage::class, 'img_parent_id', 'id')->where('img_type', 'water-mask-background');
    }
    public function water_mask_background_org() {
        return $this->hasOne(CoreImage::class, 'img_parent_id', 'id')->where('img_type', 'water-mask-background-original');
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
