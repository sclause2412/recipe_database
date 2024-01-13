<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

class RecipeIcon extends Component
{

    public $phosphor;

    /**
     * Create a new component instance.
     */
    public function __construct(public string $name)
    {
        $this->phosphor = true;
        if (view()->exists('components.recipe-icon.' . $this->name)) {
            $this->phosphor = false;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.recipe-icon.base');
    }
}
