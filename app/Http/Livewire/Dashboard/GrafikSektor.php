<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\detailpmdn;
use App\Models\detailpma;
use App\Models\pma;
use App\Models\pmdn;
use App\Models\JenisBerjangka;
use App\Models\kota;
use App\Models\sektor;
use DB;

class GrafikSektor extends Component
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
        $this->getChartPMAPMDN();
        $this->getChartPMA();
        $this->getChartPMDN();

        // dd($this->tw);
        // dd($this->PmdnClass);

    }

    public function render()
    {
        return view('livewire.dashboard.grafik-sektor');
    }

    private function loadData()
    {
       
        // dd($this->PmdnClass);
        // $this->dataset = $this->getChartPMAPMDN();
    }

    private function getChartPMAPMDN()
    {
        $query = sektor::query()
            ->leftJoin(DB::raw('(SELECT
                                    detailpmdns.sektor_id as idsektor,
                                    pmdns.jenis_berjangka_id AS jenis_berjangka_pma,
                                    pmdns.tahun AS tahunpmdn,
                                    COALESCE(SUM(detailpmdns.jumlah_proyek), 0) AS jumlah_proyek,
                                    COALESCE(SUM(detailpmdns.tambahan_investasi), 0) AS tambahan_investasi,
                                    COALESCE(SUM(detailpmdns.jumlah_tki), 0) AS jumlah_tki,
                                    COALESCE(SUM(detailpmdns.jumlah_tka), 0) AS jumlah_tka
                                FROM
                                    detailpmdns
                                    LEFT JOIN pmdns ON pmdns.id = detailpmdns.pmdn_id
                                    WHERE pmdns.tahun = ? AND pmdns.jenis_berjangka_id =?
                                GROUP BY detailpmdns.sektor_id) pm'), 'sektors.id', '=', 'pm.idsektor')
            ->leftJoin(DB::raw('(SELECT
                                    detailpmas.sektor_id as idsektor,
                                    pmas.jenis_berjangka_id AS jenis_berjangka_pma,
                                    pmas.tahun AS tahunpma,
                                    COALESCE(SUM(detailpmas.jumlah_proyek), 0) AS jumlah_proyek,
                                    COALESCE(SUM(detailpmas.tambahan_investasi * pmas.kurs), 0) AS tambahan_investasi,
                                    COALESCE(SUM(detailpmas.jumlah_tki), 0) AS jumlah_tki,
                                    COALESCE(SUM(detailpmas.jumlah_tka), 0) AS jumlah_tka
                                FROM
                                    detailpmas
                                    LEFT JOIN pmas ON pmas.id = detailpmas.pma_id
                                    WHERE pmas.tahun = ? AND pmas.jenis_berjangka_id =?
                                GROUP BY detailpmas.sektor_id) pma'), 'sektors.id', '=', 'pma.idsektor')
            
            ->groupBy('sektors.id')
            ->orderBy('tambahan_investasi', 'desc')
            ->select(
                'sektors.nama as namasektor',
                DB::raw('COALESCE((IFNULL(pma.tambahan_investasi, 0) + IFNULL(pm.tambahan_investasi, 0)), 0) AS tambahan_investasi'),
                DB::raw('COALESCE((IFNULL(pma.jumlah_proyek, 0) + IFNULL(pm.jumlah_proyek, 0)), 0)  AS jumlah_proyek'),
                DB::raw('COALESCE((IFNULL(pma.jumlah_tki, 0) + IFNULL(pm.jumlah_tki, 0)), 0) AS jumlah_tki'),
                DB::raw('COALESCE((IFNULL(pma.jumlah_tka, 0) + IFNULL(pm.jumlah_tka, 0)), 0) AS jumlah_tka')
            )
            ->setBindings([
                $this->tahun,
                  $this->tw,
                $this->tahun,
                 $this->tw,
            ])
            ->get()
            ;
        
        $this->pmapmdn = $query;
        
        $this->datapmapmdn = $this->getChart($this->pmapmdn);
    }

    private function getChartPMA()
    {
        $query = detailpma::query()
                ->join('sektors', 'sektors.id', 'detailpmas.sektor_id')
                ->leftJoin('pmas', 'pmas.id', 'detailpmas.pma_id')
                ->select('sektors.nama as namasektor', 
                        DB::raw('SUM(detailpmas.tambahan_investasi * pmas.kurs) as tambahan_investasi'),
                        DB::raw('SUM(detailpmas.jumlah_proyek) as jumlah_proyek'),
                        DB::raw('SUM(detailpmas.jumlah_tki) as jumlah_tki'),
                        DB::raw('SUM(detailpmas.jumlah_tka) as jumlah_tka'))
                ->orderBy('tambahan_investasi', 'desc')
                ->where('pmas.tahun', $this->tahun)
                ->where('pmas.jenis_berjangka_id',  $this->tw)
                ->groupBy('namasektor')->get();
        
        // $this->cek = $query->get();
        $this->pma = $query;
        
        $this->datapma = $this->getChart($this->pma);
        // return $result;
    }
    
    private function getChartPMDN()
    {
        $query = detailpmdn::query()
                ->join('sektors', 'sektors.id', 'detailpmdns.sektor_id')
                ->leftJoin('pmdns', 'pmdns.id', 'detailpmdns.pmdn_id')
                ->select('sektors.nama as namasektor', 
                        DB::raw('SUM(detailpmdns.tambahan_investasi) as tambahan_investasi'),
                        DB::raw('SUM(detailpmdns.jumlah_proyek) as jumlah_proyek'),
                        DB::raw('SUM(detailpmdns.jumlah_tki) as jumlah_tki'),
                        DB::raw('SUM(detailpmdns.jumlah_tka) as jumlah_tka'))
                ->orderBy('tambahan_investasi', 'desc')
                ->where('pmdns.tahun', $this->tahun)
                ->where('pmdns.jenis_berjangka_id',  $this->tw)
                ->groupBy('namasektor')->get();
                
        
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
                'name' => $kb->namasektor,
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
