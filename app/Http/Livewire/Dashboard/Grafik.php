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

class Grafik extends Component
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

        if ($currentMonth >= 1 && $currentMonth <= 3) {
            $this->tw = '1';
        } elseif ($currentMonth >= 4 && $currentMonth <= 6) {
            $this->tw = '2';
        } elseif ($currentMonth >= 7 && $currentMonth <= 9) {
            $this->tw = '3';
        }
        $this->getChartPMAPMDN();
        $this->getChartPMA();
        $this->getChartPMDN();

        // dd($this->tw);
        // dd($this->PmdnClass);

    }

    public function render()
    {
        return view('livewire.dashboard.grafik');
    }

    private function loadData()
    {
       
        // dd($this->PmdnClass);
        // $this->dataset = $this->getChartPMAPMDN();
    }

    private function getChartPMAPMDN()
    {
        $query1 = detailpma::query()
        ->leftJoin('kotas as kota1', 'kota1.id', 'detailpmas.kota_id')
        ->leftJoin('pmas', 'pmas.id', 'detailpmas.pma_id')
        ->select('kota1.nama as nama_kota', 'pmas.tahun as tahun', 'pmas.jenis_berjangka_id as jenis_berjangka_id',
                DB::raw('SUM(detailpmas.tambahan_investasi * pmas.kurs) as tambahan_investasi'), 
                DB::raw('SUM(detailpmas.jumlah_proyek) as jumlah_proyek'),
                DB::raw('SUM(detailpmas.jumlah_tki) as jumlah_tki'),
                DB::raw('SUM(detailpmas.jumlah_tka) as jumlah_tka'))
        ->groupBy('nama_kota');

        $query2 = detailpmdn::query()
            ->leftJoin('kotas as kota1', 'kota1.id', 'detailpmdns.kota_id')
            ->leftJoin('pmdns', 'pmdns.id', 'detailpmdns.pmdn_id')
            ->select('kota1.nama as nama_kota', 'pmdns.tahun as tahun', 'pmdns.jenis_berjangka_id as jenis_berjangka_id',
                    DB::raw('SUM(detailpmdns.tambahan_investasi) as tambahan_investasi'),
                    DB::raw('SUM(detailpmdns.jumlah_proyek) as jumlah_proyek'),
                    DB::raw('SUM(detailpmdns.jumlah_tki) as jumlah_tki'),
                    DB::raw('SUM(detailpmdns.jumlah_tka) as jumlah_tka'))
            ->orderBy('tambahan_investasi', 'desc')
            ->groupBy('nama_kota');

        // $query = $query2->union($query1);
        $combinedQuery = DB::table(DB::raw("({$query1->toSql()}) as detailpma_union"))
            ->mergeBindings($query1->getQuery()) // To merge bindings
            ->union($query2->getQuery());

        // Wrap the combined result as a subquery and apply GROUP BY
        $query = DB::table(DB::raw("({$combinedQuery->toSql()}) as combined_result"))
            // ->mergeBindings($combinedQuery->getQuery())
            ->select('nama_kota', 
                DB::raw('SUM(tambahan_investasi) as tambahan_investasi'),
                DB::raw('SUM(jumlah_proyek) as jumlah_proyek'),
                DB::raw('SUM(jumlah_tki) as jumlah_tki'),
                DB::raw('SUM(jumlah_tka) as jumlah_tka'))
            ->orderBy('tambahan_investasi', 'desc')
            ->groupBy('nama_kota')
            ->get();
        
        // $this->cek = $query->get();
        $this->pmapmdn = $query;
        
        $this->datapmapmdn = $this->getChart($this->pmapmdn);
        // return $result;
    }

    private function getChartPMA()
    {
        $query = detailpma::query()
                ->rightJoin('kotas as kota1', 'kota1.id', 'detailpmas.kota_id')
                ->leftJoin('pmas', 'pmas.id', 'detailpmas.pma_id')
                ->select('kota1.nama as nama_kota', 
                        DB::raw('SUM(detailpmas.tambahan_investasi * pmas.kurs) as tambahan_investasi'),
                        DB::raw('SUM(detailpmas.jumlah_proyek) as jumlah_proyek'),
                        DB::raw('SUM(detailpmas.jumlah_tki) as jumlah_tki'),
                        DB::raw('SUM(detailpmas.jumlah_tka) as jumlah_tka'))
                ->orderBy('tambahan_investasi', 'desc')
                ->where('kota1.provinsi_id', 4)
                ->groupBy('nama_kota')->get();
        
        // $this->cek = $query->get();
        $this->pma = $query;
        
        $this->datapma = $this->getChart($this->pma);
        // return $result;
    }
    
    private function getChartPMDN()
    {
        $query = detailpmdn::query()
                ->leftJoin('kotas as kota1', 'kota1.id', 'detailpmdns.kota_id')
                ->leftJoin('pmdns', 'pmdns.id', 'detailpmdns.pmdn_id')
                ->select('kota1.nama as nama_kota', 
                        DB::raw('SUM(detailpmdns.tambahan_investasi) as tambahan_investasi'),
                        DB::raw('SUM(detailpmdns.jumlah_proyek) as jumlah_proyek'),
                        DB::raw('SUM(detailpmdns.jumlah_tki) as jumlah_tki'),
                        DB::raw('SUM(detailpmdns.jumlah_tka) as jumlah_tka'))
                ->orderBy('tambahan_investasi', 'desc')
                ->groupBy('nama_kota')->get();
        
        // $this->cek = $query->get();
        $this->pmdn = $query;
        
        $this->datapmdn = $this->getChart($this->pmdn);
        // return $result;
    }

    private function getChart($value)
    {
        $data = [];

        foreach ($value as $kb) {
            $data[] = [
                'value' => $kb->tambahan_investasi,
                'name' => $kb->nama_kota,
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
