<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Qrcode extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $src,
        public string $type,
        public string $mataUang,
        public string $rp
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.qrcode');
    }
}
