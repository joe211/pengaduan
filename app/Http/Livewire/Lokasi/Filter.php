<?php

namespace App\Http\Livewire\Lokasi;

use Livewire\Component;
use App\Models\detailpmdn;
use App\Models\detailpma;
use App\Models\pma;
use App\Models\pmdn;
use App\Models\JenisBerjangka;
use App\Models\kota;
use DB;


class Filter extends Component
{
    public $tahun;
    public $data;
    public $tw;

    public function mount()
    {
        $this->tahun = date('Y');
        $currentMonth = now()->month;

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
        // $datas = Realisasi::get('type')->groupBy('type')->all();
        $tws = JenisBerjangka::get();
        // dd($tw);
        
        return view('livewire.lokasi.filter', ['tws' => $tws]);
    }

    public function filter()
    {
        $this->emitTo('lokasi.lokasi-modul', 'reloadTable', $this->data, $this->tahun, $this->tw);
    }
}
