<?php

namespace Modules\Core\Entities\Configuration;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Casts\Attribute;

class SystemConfig extends Model
{
    use HasFactory;

    protected $fillable = [ "id", "lat", "lng", "is_approved_enable", "is_sub_location", "is_thumb2x_3x_generate", "is_sub_subscription", "is_paid_app", "free_ad_post_count", "is_block_user", "max_img_upload_of_item", "ad_type", "promo_cell_interval_no", "is_promote_enable", "one_day_per_price", "added_date", "added_user_id", "updated_date", "updated_user_id", "updated_flag"];

    protected $table = "psx_system_configs";

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const tableName = 'psx_system_configs';
    const id = 'id';
    const lat = 'lat';
    const lng = 'lng';
    const isApprovedEnabled = 'is_approved_enable';
    const isSubLocation = 'is_sub_location';
    const isThumb2x3xGenerate = 'is_thumb2x_3x_generate';
    const isSubscription = 'is_sub_subscription';
    const isPaidApp = 'is_paid_app';
    const freeAdPostCount = 'free_ad_post_count';
    const isBlockUser = 'is_block_user';
    const maxImgUploadOfItem = 'max_img_upload_of_item';
    const adType = 'ad_type';
    const promoCellIntervalNo = 'promo_cell_interval_no';
    const oneDayPerPrice = 'one_day_per_price';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\SystemConfigFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function authorizations($abilities = []){
        return collect(array_flip($abilities))->map(function ($index, $ability){
            return Gate::allows($ability, $this);
        });
    }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->authorizations(['update','delete','create']),
        );
    }
}
