<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\detailpmdn;
use App\Models\detailpma;
use App\Models\pma;
use App\Models\pmdn;
use App\Models\JenisBerjangka;
use App\Models\kota;
use DB;

class GrafikNegaraAsal extends Component
{
    public $pmdn;
    public $pma;
    public $pmapmdn;
    public $subTotalJumProyek;
    public $subTotalJumInvestasi;
    public $subTotalJumTki;
    public $subTotalJumTka;
    public $selectedValue;
    public $tahun;
    public $tw;
    public $title;
    protected $listeners = ['reloadTable'];
    
    public $datapmapmdn;
    public $datapma;
    public $datapmdn;
    public array $labels = [];

    public function mount()
    {        
        $currentMonth = now()->month;
        $this->tahun = now()->year -1;

        if ($currentMonth >= 1 && $currentMonth <= 3) {
            $this->tw = '1';
        } elseif ($currentMonth >= 4 && $currentMonth <= 6) {
            $this->tw = '2';
        } elseif ($currentMonth >= 7 && $currentMonth <= 9) {
            $this->tw = '3';
        } elseif ($currentMonth >= 10 && $currentMonth <= 12) {
            $this->tw = '4';
        }
        $this->getChartPMA();

        // dd($this->PmdnClass);

    }

    public function render()
    {
        return view('livewire.dashboard.grafik-negara-asal');
    }

    private function loadData()
    {
       
        // dd($this->PmdnClass);
        // $this->dataset = $this->getChartPMAPMDN();
    }

    private function getChartPMA()
    {
        $query = detailpma::query()
                ->rightJoin('kotas as kota1', 'kota1.id', 'detailpmas.kota_id')
                ->leftJoin('pmas', 'pmas.id', 'detailpmas.pma_id')
                ->select('detailpmas.negara as negara', 
                        DB::raw('SUM(detailpmas.tambahan_investasi * pmas.kurs) as tambahan_investasi'),
                        DB::raw('SUM(detailpmas.jumlah_proyek) as jumlah_proyek'),
                        DB::raw('SUM(detailpmas.jumlah_tki) as jumlah_tki'),
                        DB::raw('SUM(detailpmas.jumlah_tka) as jumlah_tka'))
                ->orderBy('tambahan_investasi', 'desc')
                ->where('pmas.jenis_berjangka_id', $this->tw)
                ->where('pmas.tahun', $this->tahun)
                ->groupBy('negara')->get();
        
        // $this->cek = $query->get();
        $this->pma = $query;
        
        $this->datapma = $this->getChart($this->pma);
        // return $result;
    }

    private function getChart($value)
    {
        $data = [];

        foreach ($value as $kb) {
            $data[] = [
                'value' => $kb->tambahan_investasi,
                'name' => $kb->negara,
                // Add more fields as needed
            ];
        }
        
        // Urutkan array berdasarkan 'value' secara menurun
        usort($data, function ($a, $b) {
            return $b['value'] - $a['value'];
        });
        
        // Ambil 5 data terbesar
        $topFive = array_slice($data, 0, 5);
        
        // Ambil sisanya dan grupkan dengan nama "lainnya"
        $remaining = array_slice($data, 5);
        $othersValue = 0;
        
        foreach ($remaining as $item) {
            $othersValue += $item['value'];
        }
        
        $others = [
            'value' => $othersValue,
            'name' => 'Lainnya',
            // Add more fields as needed
        ];
        
        // Gabungkan dalam data
        $result = array_merge($topFive, [$others]);
        
        return $result;
    }
}
