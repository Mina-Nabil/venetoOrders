<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Datatable extends Component
{
    public $id;
    public $title;
    public $subtitle;
    public $cols;
    public $items;
    public $atts;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id, $title, $subtitle, $cols, $items, $atts)
    {
        $this->id = $id;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->cols = $cols;
        $this->items = $items;
        $this->atts = $atts;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.datatable');
    }
}
