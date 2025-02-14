<?php

namespace App\Http\Livewire\Negaraasal;

use Livewire\Component;
use App\Models\detailpmdn;
use App\Models\detailpma;
use App\Models\pma;
use App\Models\pmdn;
use App\Models\JenisBerjangka;
use App\Models\kota;
use DB;


class Filternegara extends Component
{
    public $tahun;
    public $data;
    public $realisasi;
    // Filters
    public $tw;

    public function mount()
    {
        $this->tahun = date('Y');
        $currentMonth = now()->month;

        if ($currentMonth >= 1 && $currentMonth <= 3) {
            $this->tw = '1';
        } elseif ($currentMonth >= 4 && $currentMonth <= 6) {
            $this->tw = '2';
        } elseif ($currentMonth >= 7 && $currentMonth <= 9) {
            $this->tw = '3';
        }
    }

    public function render()
    {
        // $datas = Realisasi::get('type')->groupBy('type')->all();
        $tws = JenisBerjangka::get();
        // dd($tw);

        return view('livewire.negaraasal.filternegara', ['tws' => $tws]);
    }

    public function filter()
    {
        $this->emitTo('negaraasal.negara-modul', 'reloadTable', $this->data, $this->tahun, $this->tw);
    }
}
