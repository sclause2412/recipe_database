<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sort;
    public $dir;

    protected $queryString = ['sort', 'dir'];

    public function sortBy($field)
    {
        $field = strtolower($field);
        if ($field === $this->sort) {
            $this->dir = $this->dir == 'asc' ? 'desc' : 'asc';
        } else {
            $this->dir = 'asc';
        }
        $this->sort = in_array($field, ['email', 'firstname', 'lastname', 'verified', 'active', '2fa', 'admin']) ? $field : 'name';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (!in_array($this->dir, [null, 'asc', 'desc'])) {
            $this->dir = 'asc';
        }

        $users = User::search(['name', 'email', 'firstname', 'lastname'], $this->search);

        switch ($this->sort) {
            case null:
                break;
            case 'verified':
                $users = $users->orderByRaw('ISNULL(`email_verified_at`) ' . $this->dir);
                break;
            case '2fa':
                $users = $users->orderByRaw('ISNULL(`two_factor_confirmed_at`) ' . $this->dir);
                break;
            case 'admin':
            case 'active':
                $users = $users->orderBy($this->sort, $this->dir == 'asc' ? 'desc' : 'asc');
                break;
            default:
                $users = $users->orderBy($this->sort, $this->dir);
                break;
        }

        $users = $users->orderBy('name', 'asc');

        return view('livewire.users.index', ['users' => $users->paginate(10)]);
    }
}
