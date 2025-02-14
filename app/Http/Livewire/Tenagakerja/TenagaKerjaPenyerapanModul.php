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

class TenagaKerjaPenyerapanModul extends Component
{
    public $data;
    public $tahun;
    public $tahun2;
    public $dataset;
    public $title;

    protected $listeners = ['reloadTable'];

    public function mount()
    {

        // $this->loadData( date('Y')-5, date('Y')-1);
    }

    public function render()
    {
        return view('livewire.tenagakerja.tenaga-kerja-penyerapan-modul');
    }

    public function reloadTable($tahun, $tahun2)
    {
        $this->loadData($tahun, $tahun2);  
    }

    private function loadData($tahun, $tahun2)
    {
    
        $results = DB::table('kotas')
                ->select('combined_data.tahun', DB::raw('SUM(COALESCE(combined_data.jumlah_tki, 0)) AS total_tki_pmdn_pma'), DB::raw('SUM(COALESCE(combined_data.jumlah_tka, 0)) AS total_tka_pmdn_pma'))
                ->leftJoinSub(function ($join) use ($tahun, $tahun2) {
                    $join->select(DB::raw('pmdns.tahun AS tahun, detailpmdns.kota_id AS kotaid'), DB::raw('COALESCE(SUM(detailpmdns.jumlah_tki), 0) AS jumlah_tki'), DB::raw('COALESCE(SUM(detailpmdns.jumlah_tka), 0) AS jumlah_tka'))
                        ->from('detailpmdns')
                        ->leftJoin('pmdns', 'pmdns.id', '=', 'detailpmdns.pmdn_id')
                        ->whereBetween('pmdns.tahun', [$tahun, $tahun2])
                        ->groupBy('kota_id', 'pmdns.tahun')
                        ->unionAll(function ($query) use ($tahun, $tahun2) {
                            $query->select(DB::raw('pmas.tahun AS tahun, detailpmas.kota_id AS kotaid'), DB::raw('COALESCE(SUM(detailpmas.jumlah_tki), 0) AS jumlah_tki'), DB::raw('COALESCE(SUM(detailpmas.jumlah_tka), 0) AS jumlah_tka'))
                                ->from('detailpmas')
                                ->leftJoin('pmas', 'pmas.id', '=', 'detailpmas.pma_id')
                                ->whereBetween('pmas.tahun', [$tahun, $tahun2])
                                ->groupBy('kota_id', 'pmas.tahun');
                        });
                }, 'combined_data', 'kotas.id', '=', 'combined_data.kotaid')
                ->where('kotas.provinsi_id', 4)
                ->whereNull('kotas.deleted_at')
                ->groupBy('combined_data.tahun')
                ->orderBy('combined_data.tahun', 'ASC')
                ;
        $this->data = $results->get();
        // $this->data = $this->results;
        // dd($this->data);
        // $this->dataTahun = $this->getDataTahun();
        // $this->dataTKI = $this->getDataTKI();
        // dd($this->dataTKI);
        $this->title = ($tahun2) ? "Diagram Penyerapan TKI & TKA Tahun ".$tahun.' s.d '.$tahun2 : "";

        $this->emit('updateChart', [
            'dataTahun' => $this->getDataTahun(),
            'dataTKI' => $this->getDataTKI(),
            'dataTKA' => $this->getDataTKA(),
        ]);
    }

    private function getDataTahun()
    {
        $data = [];

        foreach ($this->data as $kb) {
            $data[] = $kb->tahun;
        }
        
        return $data;
    }
    
    private function getDataTKI()
    {
        $data = [];

        foreach ($this->data as $kb) {
            $data[] = $kb->total_tki_pmdn_pma;
        }
        
        return $data;
    }
    
    private function getDataTKA()
    {
        $data = [];

        foreach ($this->data as $kb) {
            $data[] = $kb->total_tka_pmdn_pma;
        }
        
        return $data;
    }
    
}
