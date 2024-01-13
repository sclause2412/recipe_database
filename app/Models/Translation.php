<?php

namespace App\Models;

use App\Actions\Models\UserStamps;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Translation extends Model
{
    use HasFactory;
    use UserStamps;
    use HasUlids;
    use SoftDeletes;

    protected $casts = [
        'done' => 'boolean',
    ];
}
