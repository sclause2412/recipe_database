<?php

namespace App\Models;

use App\Actions\Models\UserStamps;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UserStamps;
    use HasUlids;

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

}
