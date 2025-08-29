<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DomainChange extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'domain_name', 'sub_folder'];

    protected $table = "psx_domain_changes";



    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\DomainChange::new();
    }
}
