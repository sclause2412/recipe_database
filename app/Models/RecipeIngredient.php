<?php

namespace App\Models;

use App\Actions\Models\UserStamps;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecipeIngredient extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UserStamps;
    use HasUlids;

    protected static function booted(): void
    {
        static::creating(function (RecipeIngredient $ingredient) {
            if (!is_null($ingredient->reference)) {
                return;
            }

            $reference = str_pad(substr(preg_replace('/[^a-z]/', '', replace_umlaut(strtolower($ingredient->ingredient?->name))), 0, 3), 3, 'x');
            $i = 1;
            while ($ingredient->recipe?->ingredients()->where('reference', $reference . str_pad($i, 2, '0', STR_PAD_LEFT))->exists()) {
                $i++;
            }
            $ingredient->reference = $reference . str_pad($i, 2, '0', STR_PAD_LEFT);
        });
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }






}
