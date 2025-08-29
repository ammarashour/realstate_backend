<?php

namespace Modules\Core\Entities\Utilities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CacheKey extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'base_key1', 'base_key2', 'base_key3', 'added_date', 'updated_date'];

    protected $table = 'psx_cache_keys';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    const tableName = 'psx_cache_keys';
    const CREATED_AT = 'added_date';
    const UPDATED_AT = 'updated_date';
    const id = 'id';
    const baseKey1 = 'base_key1';
    const baseKey2 = 'base_key2';
    const baseKey3 = 'base_key3';
}
