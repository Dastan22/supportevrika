<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    const TYPE_NEW = 0;
    const TYPE_PERFORMED= 1;
    const TYPE_DURING=2;

    public function getTypeNameAttribute (): string{
        return match($this->attributes['type'] ?? null) {
            self::TYPE_NEW => 'Новая',
            self::TYPE_PERFORMED => 'Выполненная',
            self::TYPE_DURING => 'Выполняемая',
        };
    }

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
