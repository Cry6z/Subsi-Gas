<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockLog extends Model
{
    use HasFactory;

    protected $table = 'stocks_log';

    public $timestamps = false;

    public const UPDATED_AT = null;

    protected $fillable = [
        'location_id',
        'change_amount',
        'note',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'change_amount' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
