<?php

namespace App\Models;

use App\Actions\Models\UserStamps;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UserStamps;
    use HasUlids;


    public function recipeingredients(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function recipes(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->recipeingredients->pluck('recipe')->unique();
            }
        );
    }
}
