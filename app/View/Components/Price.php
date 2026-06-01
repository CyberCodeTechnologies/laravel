<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Price extends Component
{
    public $model;
    public $class;
    public $showCode;
    public $convertToCurrent;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $model,
        $class = '',
        $showCode = false,
        $convertToCurrent = true
    ) {
        $this->model = $model;
        $this->class = $class;
        $this->showCode = $showCode;
        $this->convertToCurrent = $convertToCurrent;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.price');
    }
}
