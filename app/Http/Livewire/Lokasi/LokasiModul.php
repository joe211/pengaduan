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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportExcelLokasi;

class LokasiModul extends Component
{
    public $PmdnClass;
    public $subTotalJumProyek;
    public $subTotalJumInvestasi;
    public $subTotalJumTki;
    public $subTotalJumTka;
    public $selectedValue;
    public $tahun;
    public $data;
    public $tw;
    public $triwulan;
    public $title;
    public $header;
    protected $listeners = ['reloadTable'];
    
    public $dataset;

    public function mount()
    {        
        $currentMonth = now()->month;

        if ($currentMonth >= 1 && $currentMonth <= 3) {
            $this->tw = 1;
        } elseif ($currentMonth >= 4 && $currentMonth <= 6) {
            $this->tw = 2;
        } elseif ($currentMonth >= 7 && $currentMonth <= 9) {
            $this->tw = 3;
        } else {
            $this->tw = 4;
        }
        // $this->tw = $this->tw;
        $this->loadData('PMDN/PMA', date('Y'), [$this->tw]);

    }

    public function render()
    {        
        return view('livewire.lokasi.lokasi-modul', [
            'subTotalJumProyek' => $this->subTotalJumProyek,
            'subTotalJumInvestasi' => $this->subTotalJumInvestasi,
            'subTotalJumTki' => $this->subTotalJumTki,
            'subTotalJumTka' => $this->subTotalJumTka,
        ]);
    }

    public function reloadTable($data, $tahun, $tw)
    {
        $this->loadData($data, $tahun, $tw);
    }

    public function loadData($data = null, $tahun, $tws)
    {

        $query = kota::query();
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
                $tws = [1,2,3];
                $this->triwulan = $tws;
                $this->header = 'Januari - September';
                break;
            case ($tws == 7):
                $tws = [1,2,3,4];
                $this->triwulan = $tws;
                $this->header = 'Januari - Desember';
                break;
            default:
                $this->tw = $this->tw;
                break;
        }
        if ($data == 'PMA') {
            $query = detailpma::query()
                ->join('kotas as kota1', 'kota1.id', 'detailpmas.kota_id')
                ->leftJoin('pmas', 'pmas.id', 'detailpmas.pma_id')
                ->select('kota1.nama as nama_kota', 
                        DB::raw('SUM(detailpmas.tambahan_investasi * pmas.kurs) as tambahan_investasi'),
                        DB::raw('SUM(detailpmas.jumlah_proyek) as jumlah_proyek'),
                        DB::raw('SUM(detailpmas.jumlah_tki) as jumlah_tki'),
                        DB::raw('SUM(detailpmas.jumlah_tka) as jumlah_tka'))
                ->orderBy('tambahan_investasi', 'desc')
                ->where('kota1.provinsi_id', 4)
                ->where('pmas.tahun', $tahun)
                ->whereIn('pmas.jenis_berjangka_id', $tws)
                ->groupBy('nama_kota')
                ;
            
        } elseif ($data == 'PMDN') {
            $query = detailpmdn::query()
                ->join('kotas as kota1', 'kota1.id', 'detailpmdns.kota_id')
                ->leftJoin('pmdns', 'pmdns.id', 'detailpmdns.pmdn_id')
                ->select('kota1.nama as nama_kota', 
                        DB::raw('SUM(detailpmdns.tambahan_investasi) as tambahan_investasi'),
                        DB::raw('SUM(detailpmdns.jumlah_proyek) as jumlah_proyek'),
                        DB::raw('SUM(detailpmdns.jumlah_tki) as jumlah_tki'),
                        DB::raw('SUM(detailpmdns.jumlah_tka) as jumlah_tka'))
                ->orderBy('tambahan_investasi', 'desc')
                ->where('kota1.provinsi_id', 4)
                ->where('pmdns.tahun', $tahun)
                ->whereIn('pmdns.jenis_berjangka_id', $tws)
                ->groupBy('nama_kota');
        } else {
            $query = kota::query()
                ->leftJoin(DB::raw('(SELECT
                                        detailpmdns.kota_id AS kotaid,
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
                                    GROUP BY kotaid) pm'), 'kotas.id', '=', 'pm.kotaid')
                ->leftJoin(DB::raw('(SELECT
                                        detailpmas.kota_id AS kotaid,
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
                                    GROUP BY kotaid) pma'), 'kotas.id', '=', 'pma.kotaid')
                ->where('kotas.provinsi_id', '?')
                ->groupBy('kotas.id')
                ->orderBy('tambahan_investasi', 'desc')
                ->select(
                    'kotas.nama AS nama_kota',
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
                    4
                ])
                ;
        }

        $this->cek = $query->get();
        $this->PmdnClass = $this->cek;
        $this->tahun = $tahun ?? date('Y');
        // $this->tw = $tw ;

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

    public function ExportExcelLokasi()
    {
        // Fetch the data specifically for exporting
        // $selectedValue = preg_replace('/[^a-zA-Z0-9]/', '_', $this->selectedValue);
        $selected = empty($this->selectedValue) ? "PMDN/PMA" : $this->selectedValue;
        $selectedValue = preg_replace('/[^a-zA-Z0-9]/', '_', $selected);
        $header = preg_replace('/[^a-zA-Z0-9]/', '_', $this->header);
        $tahun = preg_replace('/[^a-zA-Z0-9]/', '_', $this->tahun);
        $dataToExport = $this->getDataForExport();
        // dd(empty($selectedValue));
        $namaFile = 'lokasi_data_' . $selectedValue . '_' . $header . '_' . $tahun . '.xlsx';
        // return view('livewire.lokasi.excel');
        return Excel::download(new ExportExcelLokasi($dataToExport, $selectedValue, $this->tahun, $this->header, 
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
