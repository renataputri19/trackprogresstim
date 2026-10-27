<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A team composition saved from the OMEGA "Susunan Tim" page. It applies to
 * its own period and to every later period until a newer row takes over;
 * with no row at all the roster falls back to config/omega.php.
 */
class OmegaPeriodRoster extends Model
{
    protected $fillable = [
        'period',
        'teams',
        'members',
        'updated_by',
    ];

    protected $casts = [
        'teams' => 'array',
        'members' => 'array',
    ];

    /**
     * The composition in force for $period: its own row, else the latest
     * earlier one. Periods look like "2026-Q3", so string order is time order.
     */
    public static function effectiveFor(string $period): ?self
    {
        return static::query()
            ->where('period', '<=', $period)
            ->orderByDesc('period')
            ->first();
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
