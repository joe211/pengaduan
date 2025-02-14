<?php

namespace App\Http\Livewire\Tenagakerja;

use Livewire\Component;

class FilterTenagaKerjaPenyerapan extends Component
{
    public $tahun;
    public $tahun2;

    public function render()
    {
        return view('livewire.tenagakerja.filter-tenaga-kerja-penyerapan');
    }

    public function filter()
    {
        $this->emitTo('tenagakerja.tenaga-kerja-penyerapan-modul', 'reloadTable',  $this->tahun, $this->tahun2);
    }
}
