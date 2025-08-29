<?php

namespace Modules\Core\Entities\Item;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\Core\Entities\Item;
use Modules\Core\Entities\CoreImage;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Entities\Item\ComplaintItemStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Gate;

class ComplaintItem extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'item_id', 'reported_user_id', 'text_note', 'reported_item_status_id', 'added_date', 'added_user_id', 'updated_date', 'updated_user_id', 'updated_flag'];

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    const id = "id";
    const reportedUserId = "reported_user_id";
    const itemId = "item_id";
    const textNote = "text_note";
    const reportedItemStatusId = "reported_item_status_id";
    const addedDate = 'added_date';

    protected $table = 'psx_item_reports';

    protected static function newFactory()
    {
        // return \Modules\Core\Database\factories\ComplaintItemFactory::new();
    }


    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function reported_user()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function reported_item_status()
    {
        return $this->belongsTo(ComplaintItemStatus::class);
    }

    public function cover()
    {
        return $this->hasMany(CoreImage::class, 'img_parent_id', 'item_id')->where('img_type', 'item');
    }

    public function video()
    {
        return $this->hasMany(CoreImage::class, 'img_parent_id', 'item_id')->where('img_type', 'item-video');
    }

    public function icon()
    {
        return $this->hasMany(CoreImage::class, 'img_parent_id', 'item_id')->where('img_type', 'item-video-icon');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function authorizations($abilities = [])
    {
        return collect(array_flip($abilities))->map(function ($index, $ability) {
            return Gate::allows($ability, $this);
        });
    }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->authorizations(['update', 'delete', 'create']),
        );
    }
}
