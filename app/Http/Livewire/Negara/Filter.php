<?php

namespace App\Http\Livewire\Negara;

use Livewire\Component;
use App\Models\JenisBerjangka;

class Filter extends Component
{
    public $tahun;
    public $tw;

    public function mount()
    {
        $this->tahun = date('Y');
        // $currentMonth = now()->month;

        // if ($currentMonth >= 1 && $currentMonth <= 3) {
        //     $this->tw = 1;
        // } elseif ($currentMonth >= 4 && $currentMonth <= 6) {
        //     $this->tw = 2;
        // } elseif ($currentMonth >= 7 && $currentMonth <= 9) {
        //     $this->tw = 3;
        // } else {
        //     $this->tw = 4;
        // }
        // dd($this->tw);
    }

    public function render()
    {
        $tws = JenisBerjangka::get();
        return view('livewire.negara.filter', ['tws' => $tws]);
    }

    public function filter()
    {
        $this->emitTo('negara.negara-modul', 'reloadTable', $this->tahun, $this->tw);
    }
}
