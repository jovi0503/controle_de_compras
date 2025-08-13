<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Representa um item individual dentro de uma Solicitação de Compra.
 *
 * @property int $id
 * @property string $uuid
 * @property int $compra_id
 * @property int $material_id
 * @property int $quantidade
 * @property string $dec
 * @property float|null $valor_unitario
 * @property float|null $valor_total
 * @property-read \App\Models\Compra $compra
 * @property-read \App\Models\Material $material
 */
class ItemCompra extends Model
{
    use HasFactory;

    /**
     *
     * @var string
     */
    protected $table = 'item_compras';

    /**
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'compra_id',
        'material_id',
        'quantidade',
        'dec',
        'valor_unitario',
        'valor_total',
    ];

    /**
     *
     * @var array<string, string>
     */
    protected $casts = [
        'valor_unitario' => 'decimal:2',
        'valor_total' => 'decimal:2',
    ];

    /**
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    

   
    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

   
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}