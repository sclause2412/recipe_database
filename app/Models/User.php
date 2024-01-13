<?php

namespace App\Models;

use App\Actions\Models\UserStamps;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use App\Actions\Files\HasProfilePhoto;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;
    use UserStamps;
    use HasUlids;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'admin' => 'bool',
        'active' => 'bool'
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtolower($value)
        );
    }

    public function elevated(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->_iselevated(),
            set: fn ($value) => $value ? $this->_elevate() : $this->_unelevate()
        );
    }

    private function _iselevated()
    {
        if (Auth::user() != $this) {
            return false;
        }
        if (!$this->admin) {
            return false;
        }
        if (is_null($this->two_factor_confirmed_at)) {
            return false;
        }
        if (session('elevatedadmin_' . $this->id) > time()) {
            return true;
        }
        return false;
    }

    private function _elevate()
    {
        if (Auth::user() != $this) {
            return false;
        }
        if (!$this->admin) {
            return false;
        }
        if (is_null($this->two_factor_confirmed_at)) {
            return false;
        }
        session(['elevatedadmin_' . $this->id => time() + 3600 * 2]);
        return true;
    }

    private function _unelevate()
    {
        session(['elevatedadmin_' . $this->id => null]);
        return true;
    }

    public function elevatetime()
    {
        if (session('elevatedadmin_' . $this->id) > time()) {
            $diff = session('elevatedadmin_' . $this->id) - time();
            return $diff;
        }

        return 0;
    }

    public function rights(): HasMany
    {
        return $this->hasMany(Right::class);
    }

    private function _getrights()
    {
        $rights = $this->rights;

        $userrights = [];

        foreach ($rights as $right) {
            $userrights[$right->project_id ?? 'global'][$right->right] = $right->write;
        }

        return $userrights;
    }

    public function get_right(string $right, Project|string $project = null): int
    {
        $userrights = $this->_getrights();

        $key = $project instanceof Project ? $project->id : ($project ?? 'global');

        if (!isset($userrights[$key])) {
            return 0;
        }

        if (!isset($userrights[$key][$right])) {
            return 0;
        }

        if ($userrights[$key][$right]) {
            return 2;
        }
        return 1;
    }

    public function set_right(string $right, int $level, Project|string $project = null): void
    {
        if ($level < 0 || $level > 2) {
            return;
        }

        $current = $this->get_right($right, $project);

        if ($current > 0 && $level == 0) {
            //Delete
            $this->rights()->where('project_id', $project instanceof Project ? $project->id : $project)->where('right', $right)->delete();
            $this->refresh();
        }

        if ($current == 0 && $level > 0) {
            //Create
            $mright = new Right();
            $mright->project_id = $project instanceof Project ? $project->id : $project;
            $mright->right = $right;
            $mright->write = $level == 2;
            $this->rights()->save($mright);
            $this->refresh();
        }

        if ($current > 0 && $level > 0 && $current != $level) {
            //Update
            $ents = $this->rights()->where('project_id', $project instanceof Project ? $project->id : $project)->where('right', $right)->get();
            foreach ($ents as $ent) {
                $ent->write = $level == 2;
                $ent->save();
            }
            $this->refresh();
        }
    }

    public function can_read($rights, Project|string $project = null)
    {
        if (!is_array($rights)) {
            $rights = [$rights];
        }

        if ($this->elevated) {
            return true;
        }

        $userrights = $this->_getrights();

        $key = $project instanceof Project ? $project->id : ($project ?? 'global');

        if (!isset($userrights[$key])) {
            return false;
        }

        foreach ($rights as $right) {
            if (isset($userrights[$key][$right])) {
                return true;
            }
        }

        return false;
    }

    public function can_write($rights, Project|string $project = null)
    {
        if (!is_array($rights)) {
            $rights = [$rights];
        }

        if ($this->elevated) {
            return true;
        }

        $userrights = $this->_getrights();

        $key = $project instanceof Project ? $project->id : ($project ?? 'global');

        if (!isset($userrights[$key])) {
            return false;
        }

        foreach ($rights as $right) {
            if (isset($userrights[$key][$right]) && $userrights[$key][$right]) {
                return true;
            }
        }
        return false;
    }

    public function has_project(Project|string $project = null)
    {
        if ($this->elevated) {
            return true;
        }

        $userrights = $this->_getrights();

        $key = $project instanceof Project ? $project->id : $project;
        $count = count($userrights) - isset($userrights['global']) ? 1 : 0;

        if (is_null($project)) {
            return $count > 0;
        } else {
            return isset($userrights[$key]);
        }
    }

    public function current(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->is(Auth::user())
        );
    }

    public function fullname(): Attribute
    {
        return Attribute::make(
            get: function () {
                $name = trim($this->firstname . ' ' . $this->lastname);
                if ($name == '') {
                    $name = $this->name;
                }
                return $name;
            }
        );
    }

    public function extendedname(): Attribute
    {
        return Attribute::make(
            get: function () {
                $name = trim($this->firstname . ' ' . $this->lastname);
                if ($name == '') {
                    $name = $this->name;
                } else {
                    $name .= ' (' . $this->name . ')';
                }
                return $name;
            }
        );
    }

    public function logoutEverywhere()
    {
        DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', $this->getAuthIdentifier())
            ->delete();
    }
}
