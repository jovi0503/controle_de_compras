<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [ 'uuid', 'name', 'exercicio', 'data', 'instituto_id', 'coordination_id', 'macro_id', 'user_id', 'status', 'e_visivel','efetivado_por_id' ];
    protected $casts = [ 'data' => 'date' ];
    public function getRouteKeyName() { return 'uuid'; }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) { $model->uuid = Str::uuid(); }
        });
    }

  
    public function efetivadoPor(): BelongsTo {return $this->belongsTo(User::class, 'efetivado_por_id');}
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function instituto(): BelongsTo { return $this->belongsTo(Instituto::class); }
    public function coordination(): BelongsTo { return $this->belongsTo(Coordination::class); }
    public function macro(): BelongsTo { return $this->belongsTo(Macro::class); }
    
    public function items(): HasMany
    {
        return $this->hasMany(ItemCompra::class);
    }
}