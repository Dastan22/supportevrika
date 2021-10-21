<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;
    protected $table = 'issues';
    protected $fillable = [
        'initiator_name',
        'text',
        'image_src',
        'initiator_contact',
        'initiator_anydesk',
        'dispatcher_id',
        'taken_at',
        'category_id',
    ];
}
