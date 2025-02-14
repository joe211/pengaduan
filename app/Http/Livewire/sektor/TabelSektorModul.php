<?php

namespace App\Http\Livewire\sektor;

use Livewire\Component;
use App\Models\detailpmdn;
use App\Models\detailpma;
use App\Models\pma;
use App\Models\pmdn;
use App\Models\JenisBerjangka;
use App\Models\kota;
use App\Models\sektorutama;
use DB;

class TabelSektorModul extends Component
{
    public $data;
    
    public $subTotalJumProyekPMDN;
    public $subTotalJumProyekPMA;
    public $subTotalJumProyekPMDNPMA;
    public $subTotalJtInvestasiPMDN;
    public $subTotalJtInvestasiPMA;
    public $subTotalInvestasiPMDN;
    public $subTotalInvestasiPMA;
    public $subTotalJumTkiPMDN;
    public $subTotalJumTkaPMA;
    public $tahun;
    public $tw;
    public $triwulan;
    public $title;
    public $header;
    public $chartPMDN;
    public $chartPMA;
    public $chartPMAPMDN;
    protected $listeners = ['reloadTable'];

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
        $this->loadData(date('Y'), [$this->tw]);

        // dd($this->tw);

    }

    public function render()
    {
        // $this->legendData = kota::where('provinsi_id', 4)->limit(5)->pluck('nama')->toArray();
        // $legendData = detailpmdn::select('tambahan_investasi', 'cetak_lokasi')->get()->toArray();
        // $legendData = ['1', '2', 'Union ad', 'Video ad', 'Search Engine'];
        // dd($legendData);

        return view('livewire.sektor.tabelsektor-modul');
    }

    public function reloadTable($tahun, $tw)
    {
        $this->loadData($tahun, $tw);
    }

    private function loadData($tahun, $tw)
    {
        $results = sektorutama::query();
        switch ($tw) {
            case ($tw >= 1 && $tw <= 4):
                $tw = [$tw];
                $this->triwulan = implode(', ', $tw);
                $this->header = 'Triwulan ' . implode(', ', $tw);
                break;
            case ($tw == 5):
                $this->triwulan = $tw;
                $tw = [1,2];
                $this->header = 'Januari - Juni';
                break;
        
            case ($tw == 6):
                $this->triwulan = $tw;
                $tw = [1,2,3];
                $this->header = 'Januari - September';
                break;
            case ($tw == 7):
                $this->triwulan = $tw;
                $tw = [1,2,3,4];
                $this->header = 'Januari - Desember';
                break;
            default:
                $this->tw = $this->tw;
                break;
        }
        
        $results = sektorutama::query()
                ->leftJoin('sektors', 'sektors.sektor_utama_id', '=', 'sektorutamas.id')
                ->leftJoin(DB::raw('(SELECT
                                        detailpmdns.sektor_id AS sektorid,
                                        pmdns.jenis_berjangka_id AS jenis_berjangka_pmdn,
                                        pmdns.tahun AS tahunpmdn,
                                        COALESCE(SUM(detailpmdns.jumlah_proyek), 0) AS jumlah_proyek,
                                        COALESCE(SUM(detailpmdns.tambahan_investasi) / 1000000, 0) AS tambahan_investasi_juta,
                                        COALESCE(SUM(detailpmdns.tambahan_investasi), 0) AS tambahan_investasi,
                                        COALESCE(SUM(detailpmdns.jumlah_tki), 0) AS jumlah_tki,
                                        COALESCE(SUM(detailpmdns.jumlah_tka), 0) AS jumlah_tka
                                    FROM
                                        detailpmdns
                                        LEFT JOIN sektors ON sektors.id = detailpmdns.sektor_id
                                        LEFT JOIN pmdns ON pmdns.id = detailpmdns.pmdn_id
                                        WHERE pmdns.tahun = ? AND pmdns.jenis_berjangka_id IN (' . rtrim(str_repeat('?,', count($tw)), ',') . ')
                                    GROUP BY sektors.id) pm'), 'sektors.id', '=', 'pm.sektorid')
                ->leftJoin(DB::raw('(SELECT
                                        detailpmas.sektor_id AS sektorid,
                                        pmas.jenis_berjangka_id AS jenis_berjangka_pma,
                                        pmas.tahun AS tahunpma,
                                        COALESCE(SUM(detailpmas.jumlah_proyek), 0) AS jumlah_proyek,
                                        COALESCE(SUM(detailpmas.tambahan_investasi) / 1000, 0) AS tambahan_investasi_juta,
                                        COALESCE(SUM(detailpmas.tambahan_investasi * pmas.kurs), 0) AS tambahan_investasi,
                                        COALESCE(SUM(detailpmas.jumlah_tki), 0) AS jumlah_tki,
                                        COALESCE(SUM(detailpmas.jumlah_tka), 0) AS jumlah_tka
                                    FROM
                                        detailpmas
                                        LEFT JOIN sektors ON sektors.id = detailpmas.sektor_id
                                        LEFT JOIN pmas ON pmas.id = detailpmas.pma_id
                                        WHERE pmas.tahun = ? AND pmas.jenis_berjangka_id IN (' . rtrim(str_repeat('?,', count($tw)), ',') . ')
                                    GROUP BY sektors.id) pma'), 'sektors.id', '=', 'pma.sektorid')
                ->groupBy('sektorutamas.id', 'sektorutamas.nama')
                ->select(
                    'sektorutamas.id as sektorutamaid',
                    'sektorutamas.nama as sektorutama',
                    DB::raw('GROUP_CONCAT(COALESCE(sektors.id) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS idsektor'),
                    DB::raw('GROUP_CONCAT(sektors.nama ORDER BY sektors.id + 0 ASC SEPARATOR "; ") AS namasektor'),
                    DB::raw('GROUP_CONCAT(COALESCE(pm.jumlah_proyek, 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS jumlah_proyek_pmdn'),
                    DB::raw('GROUP_CONCAT(COALESCE(pm.tambahan_investasi_juta, 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS tambahan_investasi_juta_pmdn'),
                    DB::raw('GROUP_CONCAT(COALESCE(pm.tambahan_investasi, 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS tambahan_investasi_pmdn'),
                    DB::raw('GROUP_CONCAT(COALESCE(pm.jumlah_tki, 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS jumlah_tki_pmdn'),
                    DB::raw('GROUP_CONCAT(COALESCE(pm.jumlah_tka, 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS jumlah_tka_pmdn'),
                    DB::raw('GROUP_CONCAT(COALESCE(pma.jumlah_proyek, 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS jumlah_proyek_pma'),
                    DB::raw('GROUP_CONCAT(COALESCE(pma.tambahan_investasi_juta, 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS tambahan_investasi_juta_pma'),
                    DB::raw('GROUP_CONCAT(COALESCE(pma.tambahan_investasi, 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS tambahan_investasi_pma'),
                    DB::raw('GROUP_CONCAT(COALESCE(pma.jumlah_tki, 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS jumlah_tki_pma'),
                    DB::raw('GROUP_CONCAT(COALESCE(pma.jumlah_tka, 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS jumlah_tka_pma'),
                    DB::raw('GROUP_CONCAT(COALESCE((IFNULL(pma.jumlah_proyek, 0) + IFNULL(pm.jumlah_proyek, 0)), 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS jumlah_proyek_pmdn_pma'),
                    DB::raw('GROUP_CONCAT(COALESCE((IFNULL(pma.tambahan_investasi, 0) + IFNULL(pm.tambahan_investasi, 0)), 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS jumlah_investasi_pmdn_pma'),
                    DB::raw('GROUP_CONCAT(COALESCE((IFNULL(pma.jumlah_tki, 0) + IFNULL(pm.jumlah_tki, 0)), 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS jumlah_tki_pmdn_pma'),
                    DB::raw('GROUP_CONCAT(COALESCE((IFNULL(pma.jumlah_tka, 0) + IFNULL(pm.jumlah_tka, 0)), 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS jumlah_tka_pmdn_pma')
                )
                ->setBindings([
                    $tahun,
                    'jenis_berjangka_pmdn' => $tw,
                    $tahun,
                    'jenis_berjangka_pma' => $tw,
                ])
                ;
                
        $this->results = $results->get();
        $this->data = $this->results;
        $this->tahun = $tahun ?? date('Y');
        // dd($this->getChartData());
        // $this->chartPMDN = $this->getChartPMDN();
        // $this->chartPMA = $this->getChartPMA();
        // $this->chartPMAPMDN = $this->getChartPMAPMDN();
        // $this->title = ($tw) ? 'PER SEKTOR '.$this->header.' '.$this->tahun : "PMDN/PMA PER LOKASI ".$this->header.' '.$this->tahun;
// dd($tw);
        // $this->title = 'PER SEKTOR '.$this->header.' '.$this->tahun;
        $this->emit('updateChart', [
            'chartPMDN' => $this->getChartPMDN(),
            'chartPMA' => $this->getChartPMA(),
            'chartPMAPMDN' => $this->getChartPMAPMDN()
        ]);
    }

    private function getChartPMDN()
    {
        $data = [];

        foreach ($this->data as $kb) {
            $invesPMDN = explode(", ", $kb->tambahan_investasi_pmdn);
            $data[] = [
                'name' => $kb->sektorutama,
                'value' => array_sum($invesPMDN),
                // Add more fields as needed
            ];
        }
        
        return $data;
    }
    
    private function getChartPMA()
    {
        $data = [];

        foreach ($this->data as $kb) {
            $invesPMA = explode(", ", $kb->tambahan_investasi_pma);
            $data[] = [
                'name' => $kb->sektorutama,
                'value' => array_sum($invesPMA),
                // Add more fields as needed
            ];
        }
        
        return $data;
    }
    
    private function getChartPMAPMDN()
    {
        $data = [];

        foreach ($this->data as $kb) {
            $invesPMDNPMA = explode(", ", $kb->jumlah_investasi_pmdn_pma);
            $data[] = [
                'name' => $kb->sektorutama,
                'value' => array_sum($invesPMDNPMA),
                // Add more fields as needed
            ];
        }
        
        return $data;
    }

    public function ExportExcel($tahun, $triwulan)
    {
        // dd($this->tahun);
        // $tahun = $this->tahun;
        // dd($tahun);
        $dataToExport = $this->getDataForExport($tahun, $triwulan);
        // dd($dataToExport);
        return view('livewire.sektor.excel', [
            'data' => $dataToExport,
            'header' => $this->header,
            'tahun' => $tahun,
        ]);
    }

    private function getDataForExport($tahun, $triwulan)
    {
        // Use the same logic you have in the loadData method to fetch the data
        // Assuming the loadData method is correctly retrieving the data

        $this->loadData($tahun, $triwulan);

        // Return the data
        return $this->data;
    }
}
