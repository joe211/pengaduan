<?php

namespace App\Http\Livewire\Tenagakerja;

use Livewire\Component;
use App\Models\detailpmdn;
use App\Models\detailpma;
use App\Models\pma;
use App\Models\pmdn;
use App\Models\JenisBerjangka;
use App\Models\kota;
use App\Models\sektor;
use DB;

class TenagaKerjaSektorModul extends Component
{
    public $data;
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
    public $tw;
    public $sql;
    public $category;
    public $header;
    protected $listeners = ['reloadTable'];

    public function mount()
    {
        $this->tahun = date('Y');
        $currentMonth = now()->month;
        $this->loadData( date('Y'), [$this->tw]);
    }

    public function render()
    {
        return view('livewire.tenagakerja.tenaga-kerja-sektor-modul');
    }

    public function reloadTable($tahun, $tw)
    {
        $this->loadData($tahun, $tw);  
    }

    private function loadData( $tahun = null, $tw = null)
    {
        $results = sektor::query();
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
        
        $results = sektor::query()
                ->leftJoin(DB::raw('(SELECT
                                        detailpmdns.sektor_id AS sektorid,
                                        pmdns.jenis_berjangka_id AS jenis_berjangka_pmdn,
                                        pmdns.tahun AS tahunpmdn,
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
                                        COALESCE(SUM(detailpmas.jumlah_tki), 0) AS jumlah_tki,
                                        COALESCE(SUM(detailpmas.jumlah_tka), 0) AS jumlah_tka
                                    FROM
                                        detailpmas
                                        LEFT JOIN sektors ON sektors.id = detailpmas.sektor_id
                                        LEFT JOIN pmas ON pmas.id = detailpmas.pma_id
                                        WHERE pmas.tahun = ? AND pmas.jenis_berjangka_id IN (' . rtrim(str_repeat('?,', count($tw)), ',') . ')
                                    GROUP BY sektors.id) pma'), 'sektors.id', '=', 'pma.sektorid')
                ->groupBy('sektors.id', 'sektors.nama')
                ->select(
                    'sektors.id',
                    'sektors.nama as nama_sektor',
                    DB::raw('GROUP_CONCAT(sektors.nama ORDER BY sektors.id + 0 ASC SEPARATOR "; ") AS namasektor'),
                   
                    DB::raw('GROUP_CONCAT(COALESCE(pm.jumlah_tki, 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS jumlah_tki_pmdn'),
                    DB::raw('GROUP_CONCAT(COALESCE(pm.jumlah_tka, 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS jumlah_tka_pmdn'),
                    
                    DB::raw('GROUP_CONCAT(COALESCE(pma.jumlah_tki, 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS jumlah_tki_pma'),
                    DB::raw('GROUP_CONCAT(COALESCE(pma.jumlah_tka, 0) ORDER BY sektors.id + 0 ASC SEPARATOR ", ") AS jumlah_tka_pma'),
                   
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
        $this->jenis_berjangka_id = $tw ;
        // dd( $this->results);
    }
   
}
