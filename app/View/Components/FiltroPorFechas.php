<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FiltroPorFechas extends Component
{
    
    public $resultadoId;

    public function __construct($resultadoId='resultado')
    {
        $this->resultadoId=$resultadoId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.filtro-por-fechas');
    }
}
