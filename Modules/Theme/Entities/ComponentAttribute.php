<?php

namespace Modules\Theme\Entities;

use App\Models\PsModel;
use App\Models\User;
use Modules\Core\Entities\Shop;
use Modules\Core\Entities\Location\LocationCity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\CoreImage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Gate;

class ComponentAttribute extends PsModel
{
    use HasFactory;

    protected $table = "psx_component_attributes";
    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    protected $fillable = [
        'id',
        'name',
        'component_id',
        'platform_id',
        'screen_id',
        'theme_id',
        'attributes',
        'added_date',
        'added_user_id',
        'updated_date',
        'updated_user_id',
        'updated_flag'
    ];
    const tableName = "psx_component_attributes";
    const id = "id";

    const componentId = "component_id";
    const themeId = "theme_id";
    const platformId = "platform_id";
    const screenId = "screen_id";

    const addedDate = 'added_date';
    const addedUserId = 'added_user_id';
    protected static function newFactory()
    {
        // return \Modules\Blog\Database\factories\BlogFactory::new();
    }


    public function owner()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function authorizations($abilities = [])
    {
        return collect(array_flip($abilities))->map(function ($index, $ability) {
            return Gate::allows($ability, $this);
        });
    }

    public function toArray()
    {
        return parent::toArray() + [
            'authorizations' => $this->authorizations(['update', 'delete', 'create'])
        ];
    }

    protected function authorization(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->authorizations(['update', 'delete', 'create']),
        );
    }
}
