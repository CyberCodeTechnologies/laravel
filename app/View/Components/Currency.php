<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Services\CurrencyService;

class Currency extends Component
{
    public $amount;
    public $currency;
    public $showCode;
    public $class;
    public $convertToCurrent;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $amount,
        $currency = null,
        $showCode = false,
        $class = '',
        $convertToCurrent = true
    ) {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->showCode = $showCode;
        $this->class = $class;
        $this->convertToCurrent = $convertToCurrent;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.currency');
    }
}
