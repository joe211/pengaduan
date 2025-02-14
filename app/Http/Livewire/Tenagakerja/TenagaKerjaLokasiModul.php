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
use Maatwebsite\Excel\Facades\Excel;

class TenagaKerjaLokasiModul extends Component
{
    public $lokasiClass;
    public $realisasi;
    public $subTotalJumTki;
    public $subTotalJumTka;
    public $subTotalJumTkipma;
    public $subTotalJumTkapma;
    public $subTotalJumTkiTka;
    public $subTotalJumTkiTkapma;
    public $subTotalJumTkipmapmdn;
    public $subTotalJumTkapmapmdn;
    public $subTotalJumTkiTkapmapmdn;
    public $selectedValue;
    public $legendData;
    public $tahun;
    public $triwulan;
    public $tw;
    public $sql;
    public $data;
    public $header;
    public $provinsi_id;
    protected $listeners = ['reloadTable'];
    

    public function mount()
    {
        $this->tahun = date('Y');
        $currentMonth = now()->month;

        $this->loadData( date('Y'), $this->tw);
    }

    public function render()
    {
        return view('livewire.tenagakerja.tenaga-kerja-lokasi-modul');
    }

    public function reloadTable($tahun, $tw)
    {
        $this->loadData($tahun, $tw);  
    }

    private function loadData( $tahun = null, $tw = null)
    {
        $results = kota::query();
        switch ($tw) {
            case ($tw >= 1 && $tw <= 4):
                $tw = [$tw];
                $this->header = 'Triwulan ' . implode(', ', $tw);
                break;
            case ($tw == 5):
                $tw = [1,2];
                $this->header = 'Januari - Juni';
                break;
        
            case ($tw == 6):
                $tw = [1,2,3];
                $this->header = 'Januari - September';
                break;
            case ($tw == 7):
                $tw = [1,2,3,4];
                $this->header = 'Januari - Desember';
                break;
            default:
                $this->tw = $this->tw;
                break;
        }

        $provinsi_id = 4;
        $results = kota::query()
            ->leftJoin(DB::raw('(SELECT
                                    detailpmdns.kota_id AS kotaid,
                                    pmdns.jenis_berjangka_id AS jenis_berjangka_pmdn,
                                    pmdns.tahun AS tahunpmdn,
                                    COALESCE(SUM(detailpmdns.jumlah_tki), 0) AS jumlah_tki,
                                    COALESCE(SUM(detailpmdns.jumlah_tka), 0) AS jumlah_tka
                                FROM
                                    detailpmdns
                                    LEFT JOIN kotas ON kotas.id = detailpmdns.kota_id
                                    LEFT JOIN pmdns ON pmdns.id = detailpmdns.pmdn_id
                                    WHERE pmdns.tahun = ? AND pmdns.jenis_berjangka_id IN (' . rtrim(str_repeat('?,', count($tw)), ',') . ')
                                    GROUP BY kotas.id) pm'), 'kotas.id', '=', 'pm.kotaid')
            ->leftJoin(DB::raw('(SELECT
                                    detailpmas.kota_id AS kotaid,
                                    pmas.jenis_berjangka_id AS jenis_berjangka_pma,
                                    pmas.tahun AS tahunpma,                                       
                                    COALESCE(SUM(detailpmas.jumlah_tki), 0) AS jumlah_tki,
                                    COALESCE(SUM(detailpmas.jumlah_tka), 0) AS jumlah_tka
                                FROM
                                    detailpmas
                                    LEFT JOIN kotas ON kotas.id = detailpmas.kota_id
                                    LEFT JOIN pmas ON pmas.id = detailpmas.pma_id
                                    WHERE pmas.tahun = ? AND pmas.jenis_berjangka_id IN (' . rtrim(str_repeat('?,', count($tw)), ',') . ') 
                                    GROUP BY kotas.id) pma'), 'kotas.id', '=', 'pma.kotaid')
            ->groupBy('kotas.id', 'kotas.nama')
            ->where('kotas.provinsi_id','=', $provinsi_id)
            ->select(
                'kotas.id',
                'kotas.nama as nama_kota',
                DB::raw('GROUP_CONCAT(kotas.nama ORDER BY kotas.id + 0 ASC SEPARATOR "; ") AS namakota'),
                        
                DB::raw('GROUP_CONCAT(COALESCE(pm.jumlah_tki, 0) ORDER BY kotas.id + 0 ASC SEPARATOR ", ") AS jumlah_tki_pmdn'),
                DB::raw('GROUP_CONCAT(COALESCE(pm.jumlah_tka, 0) ORDER BY kotas.id + 0 ASC SEPARATOR ", ") AS jumlah_tka_pmdn'),
                            
                DB::raw('GROUP_CONCAT(COALESCE(pma.jumlah_tki, 0) ORDER BY kotas.id + 0 ASC SEPARATOR ", ") AS jumlah_tki_pma'),
                DB::raw('GROUP_CONCAT(COALESCE(pma.jumlah_tka, 0) ORDER BY kotas.id + 0 ASC SEPARATOR ", ") AS jumlah_tka_pma'),
                        
                DB::raw('GROUP_CONCAT(COALESCE((IFNULL(pma.jumlah_tki, 0) + IFNULL(pm.jumlah_tki, 0)), 0) ORDER BY kotas.id + 0 ASC SEPARATOR ", ") AS jumlah_tki_pmdn_pma'),
                DB::raw('GROUP_CONCAT(COALESCE((IFNULL(pma.jumlah_tka, 0) + IFNULL(pm.jumlah_tka, 0)), 0) ORDER BY kotas.id + 0 ASC SEPARATOR ", ") AS jumlah_tka_pmdn_pma')
            )
            ->setBindings([
                $tahun,
                'jenis_berjangka_pmdn' => $tw,
                $tahun,
                'jenis_berjangka_pma' => $tw,
                $provinsi_id, // Add binding parameter for $provinsi_id
            ]);

                
        $this->results = $results->get();
        $this->data = $this->results;
        $this->tahun = $tahun ?? date('Y');
        $this->jenis_berjangka_id = $tw ;
        // dd( $this->results);
    }
    
    public function ExportExcel($tahun, $tw)
    {
        // dd($this->tahun);
        // $tahun = $this->tahun;
        // dd($tahun);
        $dataToExport = $this->getDataForExport($tahun, $tw);
        // dd($dataToExport);
        return view('livewire.Tenagakerja.excel', [
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
