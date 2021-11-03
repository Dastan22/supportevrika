<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Issue extends Model
{
    use HasFactory;
    use SoftDeletes;

    const STATUS_TYPE_PERFORMED = 0;
    const STATUS_TYPE_NEW = 1;
    const STATUS_TYPE_DURING = 2;

    protected $fillable = [
        'initiator_name',
        'text',
        'image_src',
        'initiator_contact',
        'status',
        'initiator_anydesk',
        'dispatcher_id',
        'category_id',
        'taken_at',
    ];

    public function getStatusTypeNameAttribute (): string
    {
        switch ($this->attributes['status'] ?? null) {
            case self::STATUS_TYPE_NEW: return "Новая";
            case self::STATUS_TYPE_PERFORMED: return "Выполненная";
            case self::STATUS_TYPE_DURING: return "Выполняемая";
            default: return 'Not found';
        }
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['date_after'] ?? false, function($query, $date_after){
            $query->whereDate('created_at', '>=', $date_after);
        } );

        $query->when($filters['date_before'] ?? false, function($query, $date_before){
            $query->whereDate('created_at', '<=', $date_before);
        } );

        $query->when($filters['search'] ?? false, fn($query, $search) =>
        $query->where(fn($query) =>
        $query->where('text', 'like', '%'. $search .'%')
            ->orWhere('initiator_name', 'like', '%'. $search .'%')
        )
        );

        $query->when($filters['status'] ?? false, function($query, $status){
            $query->where('status', $status);
        } );

        $query->when($filters['category_id'] ?? false, function($query, $category_id){
            $query->where('category_id', $category_id);
        } );
    }


    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function updateHistory(){

    }




}


