<?php

namespace App\Models;

use App\Actions\Models\UserStamps;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecipeStep extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UserStamps;
    use HasUlids;

    protected $touches = ['recipe'];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

}
