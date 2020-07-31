<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SimpleChart extends Component
{

    public $chartTitle;
    public $chartSubtitle;
    public $graphs;
    public $totals;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($chartTitle, $chartSubtitle='', $graphs, $totals=[]){
        $this->chartTitle = $chartTitle;
        $this->chartSubtitle = $chartSubtitle;
        $this->graphs = $graphs;
        $this->totals = $totals;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.simple-chart');
    }
}
