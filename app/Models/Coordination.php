<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Coordination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'e_visivel',
        'uuid',
        'instituto_id', 
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

  
    public function instituto(): BelongsTo
    {
        return $this->belongsTo(Instituto::class);
    }
}