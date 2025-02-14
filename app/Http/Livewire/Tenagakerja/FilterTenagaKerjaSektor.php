<?php

namespace App\Http\Livewire\Tenagakerja;

use Livewire\Component;
use App\Models\detailpmdn;
use App\Models\detailpma;
use App\Models\pma;
use App\Models\pmdn;
use App\Models\JenisBerjangka;
use App\Models\kota;
use DB;

class FilterTenagaKerjaSektor extends Component
{
    public $tahun;
    public $data;
    public $realisasi;
    public $tw;

    public function mount()
    {
        $this->tahun = date('Y');
        
        $currentMonth = now()->month;

    }

    public function render()
    {
        $tws = JenisBerjangka::get();
  
        return view('livewire.tenagakerja.filter-tenaga-kerja-sektor', ['tws' => $tws]);
    }

    public function filter()
    {
        $this->emitTo('tenagakerja.tenaga-kerja-sektor-modul', 'reloadTable',  $this->tahun, $this->tw);

    }
}