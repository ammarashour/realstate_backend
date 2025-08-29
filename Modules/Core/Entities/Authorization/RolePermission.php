<?php

namespace Modules\Core\Entities\Authorization;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RolePermission extends Model
{
    use HasFactory;

    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';

    protected $fillable = [];

    protected $table = "psx_role_permissions";

    const roleId = "role_id";
    const moduleId = "module_id";
    const tableName = "psx_role_permissions";

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\Authorization\RolePermissionFactory::new();
    }

    public function owner(){
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function editor(){
        return $this->belongsTo(User::class, 'updated_user_id');
    }
}
