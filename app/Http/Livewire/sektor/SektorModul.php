<?php

namespace App\Http\Livewire\sektor;

use Livewire\Component;
use App\Models\detailpmdn;
use App\Models\detailpma;
use App\Models\pma;
use App\Models\pmdn;
use App\Models\JenisBerjangka;
use App\Models\sektor;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExcelSektor;

class SektorModul extends Component
{
    public $PmdnClass;
    public $subTotalJumProyek;
    public $subTotalJumInvestasi;
    public $subTotalJumTki;
    public $subTotalJumTka; 
    public $selectedValue;
    public $tahun;
    public $triwulan;
    public $tw;
    public $title;
    public $header;
    protected $listeners = ['reloadTable'];

    public $dataset;
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
        $this->loadData('PMDN/PMA', date('Y'), [$this->tw]);

        // dd($this->tw);
        // dd($this->PmdnClass);

    }

    public function render()
    {
        // $this->legendData = kota::where('provinsi_id', 4)->limit(5)->pluck('nama')->toArray();
        // $legendData = detailpmdn::select('tambahan_investasi', 'cetak_lokasi')->get()->toArray();
        // $legendData = ['1', '2', 'Union ad', 'Video ad', 'Search Engine'];
        // dd($legendData);
        return view('livewire.sektor.sektor-modul', [
            'subTotalJumProyek' => $this->subTotalJumProyek,
            'subTotalJumInvestasi' => $this->subTotalJumInvestasi,
            'subTotalJumTki' => $this->subTotalJumTki,
            'subTotalJumTka' => $this->subTotalJumTka,
        ]);
    }

    public function reloadTable($data, $tahun, $tws)
    {
        $this->loadData($data, $tahun, $tws);
    }

    private function loadData($data = null, $tahun = null, $tws)
    {
        $query = sektor::query();
        switch ($tws) {
            case ($tws >= 1 && $tws <= 4):
                $tws = [$tws];
                $this->triwulan = implode(', ', $tws);
                $this->header = 'Triwulan ' . implode(', ', $tws);
                break;
            case ($tws == 5):
                $tws = [1,2];
                $this->triwulan = $tws;
                $this->header = 'Januari - Juni';
                break;
        
            case ($tws == 6):
                $this->triwulan = $tws;
                $tws = [1,2,3];
                $this->header = 'Januari - September';
                break;
            case ($tws == 7):
                $this->triwulan = $tws;
                $tws = [1,2,3,4];
                $this->header = 'Januari - Desember';
                break;
            default:
                $this->tw = $this->tw;
                break;
        }
        if ($data == 'PMA') {
            $query = detailpma::query()
                ->join('sektors', 'sektors.id', 'detailpmas.sektor_id')
                ->leftJoin('pmas', 'pmas.id', 'detailpmas.pma_id')
                ->select('sektors.nama as namasektor', 
                        DB::raw('SUM(detailpmas.tambahan_investasi * pmas.kurs) as tambahan_investasi'),
                        DB::raw('SUM(detailpmas.jumlah_proyek) as jumlah_proyek'),
                        DB::raw('SUM(detailpmas.jumlah_tki) as jumlah_tki'),
                        DB::raw('SUM(detailpmas.jumlah_tka) as jumlah_tka'))
                ->orderBy('tambahan_investasi', 'desc')
                ->where('pmas.tahun', $tahun)
                ->whereIn('pmas.jenis_berjangka_id', $tws)
                ->groupBy('namasektor');
            
        } elseif ($data == 'PMDN') {
            $query = detailpmdn::query()
                ->join('sektors', 'sektors.id', 'detailpmdns.sektor_id')
                ->leftJoin('pmdns', 'pmdns.id', 'detailpmdns.pmdn_id')
                ->select('sektors.nama as namasektor', 
                        DB::raw('SUM(detailpmdns.tambahan_investasi) as tambahan_investasi'),
                        DB::raw('SUM(detailpmdns.jumlah_proyek) as jumlah_proyek'),
                        DB::raw('SUM(detailpmdns.jumlah_tki) as jumlah_tki'),
                        DB::raw('SUM(detailpmdns.jumlah_tka) as jumlah_tka'))
                ->orderBy('tambahan_investasi', 'desc')
                ->where('pmdns.tahun', $tahun)
                ->whereIn('pmdns.jenis_berjangka_id', $tws)
                ->groupBy('namasektor');
        } else {
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
                                    WHERE pmdns.tahun = ? AND pmdns.jenis_berjangka_id IN (' . rtrim(str_repeat('?,', count($tws)), ',') . ')
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
                                    WHERE pmas.tahun = ? AND pmas.jenis_berjangka_id IN (' . rtrim(str_repeat('?,', count($tws)), ',') . ')
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
                $tahun,
                'jenis_berjangka_pmdn' => $tws,
                $tahun,
                'jenis_berjangka_pma' => $tws,
            ])
            ;


           
        }

        
        
        
        $this->cek = $query->get();
        $this->PmdnClass = $this->cek;
        $this->tahun = $tahun ?? date('Y');
        // $this->tw = $tw ;
        // dd($this->tw);

        $this->subTotalJumProyek = $this->PmdnClass->sum('jumlah_proyek');
        $this->subTotalJumInvestasi = $this->PmdnClass->sum('tambahan_investasi');
        $this->subTotalJumTki = $this->PmdnClass->sum('jumlah_tki');
        $this->subTotalJumTka = $this->PmdnClass->sum('jumlah_tka');
        
        
        $this->selectedValue = $data ?? "PMDN/PMA";
        $this->title = ($data) ? $this->selectedValue.' PER SEKTOR '.$this->header.' '.$this->tahun : "PMDN/PMA PER LOKASI ".$this->header.' '.$this->tahun;

        $this->dataset = $this->getChartData();
        // dd($this->dataset);
        $this->emit('updateChart', [
            'datasets' => $this->dataset,
            'judul' => ($this->selectedValue) ? $this->selectedValue.' PER SEKTOR '.$this->header.' '.$this->tahun : "PMDN/PMA PER LOKASI ".$this->header.' '.$this->tahun
        ]);
    }

    private function getChartData()
    {
        $data = [];

        foreach ($this->PmdnClass as $kb) {
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

$topFive = array_slice($data, 0, 5);
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
    public function ExcelSektor()
    {
        // Fetch the data specifically for exporting
        // $selectedValue = preg_replace('/[^a-zA-Z0-9]/', '_', $this->selectedValue);
        $selected = empty($this->selectedValue) ? "PMDN/PMA" : $this->selectedValue;
        $selectedValue = preg_replace('/[^a-zA-Z0-9]/', '_', $selected);
        $header = preg_replace('/[^a-zA-Z0-9]/', '_', $this->header);
        $tahun = preg_replace('/[^a-zA-Z0-9]/', '_', $this->tahun);
        $dataToExport = $this->getDataForExport();
        // dd(empty($selectedValue));
        $namaFile = 'sektor' . $selectedValue . '_' . $header . '_' . $tahun . '.xlsx';
        // return view('livewire.lokasi.excel');
        return Excel::download(new ExcelSektor($dataToExport, $selectedValue, $this->tahun, $this->header, 
                        $this->subTotalJumProyek, $this->subTotalJumInvestasi, $this->subTotalJumTki, 
                        $this->subTotalJumTka), $namaFile);
    }

    private function getDataForExport()
    {
        // Use the same logic you have in the loadData method to fetch the data
        // Assuming the loadData method is correctly retrieving the data

        $this->loadData($this->selectedValue, $this->tahun, $this->triwulan);

        // Return the data
        return $this->PmdnClass;
    }
}
