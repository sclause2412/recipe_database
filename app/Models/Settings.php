<?php

namespace App\Models;

use App\Actions\Models\UserStamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Settings extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UserStamps;

    protected $fillable = ['project_id', 'user_id', 'setting', 'data'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public static function get(?User $user, ?Project $project, $setting, $default = null)
    {
        $ent = static::where('user_id', $user?->id)->where('project_id', $project?->id)->where('setting', $setting)->first();
        if (is_null($ent))
            return $default;
        return unserialize($ent->data);
    }

    public static function set(?User $user, ?Project $project, $setting, $data)
    {
        $ent = static::where('user_id', $user?->id)->where('project_id', $project?->id)->where('setting', $setting)->first();
        if (is_null($ent)) {
            static::create(['user_id' => $user?->id, 'project_id' => $project?->id, 'setting' => $setting, 'data' => serialize($data)]);
        } else {
            $ent->data = serialize($data);
            $ent->save();
        }
    }
}
