<?php

namespace App\Http\Livewire\Realisasi;

use Livewire\Component;
use App\Models\detailpmdn;
use App\Models\detailpma;
use App\Models\pma;
use App\Models\pmdn;
use App\Models\JenisBerjangka;
use DB;

class Modul extends Component
{
    public $now;
    public $past;
    public $before;
    public $tahun;
    public $data;
    public $tw;
    public $titleNow;
    public $titlePast;
    public $titleBefore;
    public $header;
    protected $listeners = ['reloadTable'];
    public $dataNow;
    public $dataPast;
    public $dataBefore;
    public $dataTW;
    public $dataTahun;
    public $kurs;

    public function mount()
    {        
        $currentMonth = now()->month;

        // if ($currentMonth >= 1 && $currentMonth <= 3) {
        //     $this->tw = 1;
        // } elseif ($currentMonth >= 4 && $currentMonth <= 6) {
        //     $this->tw = 2;
        // } elseif ($currentMonth >= 7 && $currentMonth <= 9) {
        //     $this->tw = 3;
        // } else {
        //     $this->tw = 4;
        // }
        $tahun = date('Y');
        $this->getDataNow($tahun, $this->tw);
        $this->getDataPast($tahun-1, $this->tw);
        $this->getDataBefore($tahun, $this->tw);

    }

    public function render()
    {
        return view('livewire.realisasi.modul', [
            'now' => $this->now,
            'past' => $this->past,
            'before' => $this->before,
        ]);
    }

    public function reloadTable($tahun, $tw)
    {
        $this->getDataNow($tahun, $tw);
        $this->getDataPast($tahun-1, $tw);
        $this->getDataBefore($tahun, $tw);
    }

    private function getDataNow($tahun, $tws)
    {
        switch ($tws) {
            case ($tws >= 1 && $tws <= 4):
                $dataTW = $tws;
                $tws = [$tws];
                $this->header = 'Triwulan ' . implode(', ', $tws);
                break;
            case ($tws == 5):
                $dataTW = $tws;
                $tws = [1,2];
                $this->header = 'Januari - Juni';
                break;
        
            case ($tws == 6):
                $dataTW = $tws;
                $tws = [1,2,3];
                $this->header = 'Januari - September';
                break;
            case ($tws == 7):
                $dataTW = $tws;
                $tws = [1,2,3,4];
                $this->header = 'Januari - Desember';
                break;
            default:
                $this->tw = $this->tw;
                break;
        }
        // dd($tws);
        // if ($tws >= 1 && $tws <= 4) {
        //     $dataTW = $tws;
        //     $tws = [$tws];
        //     $this->header = 'Triwulan ' . implode(', ', $tws);
        // } elseif ($tws == 5) {
        //     $dataTW = $tws;
        //     $tws = [1, 2];
        //     $this->header = 'Januari - Juni';
        // } elseif ($tws == 6) {
        //     $dataTW = $tws;
        //     $tws = [1, 2, 3];
        //     $this->header = 'Januari - September';
        // } elseif ($tws == 7) {
        //     $dataTW = $tws;
        //     $tws = [1, 2, 3, 4];
        //     $this->header = 'Januari - Desember';
        // } else {
        //     $this->tw = 0;
        // }
        // dd(empty($tws));
        $query = DB::table(DB::raw("(
            SELECT 
                'PMDN' AS type,
                pmdn.jenis_berjangka_id,
                COALESCE(SUM(detailpmdn.tambahan_investasi), 0) AS value
            FROM detailpmdns detailpmdn
            LEFT JOIN pmdns pmdn ON pmdn.id = detailpmdn.pmdn_id
            
            WHERE pmdn.tahun = ? AND pmdn.jenis_berjangka_id IN (" . rtrim(str_repeat('?,', count($tws)), ',') . ")
            GROUP BY pmdn.jenis_berjangka_id
    
            UNION ALL
    
            SELECT 
                'PMA' AS type,
                pma.jenis_berjangka_id,
                COALESCE(SUM(detailpma.tambahan_investasi * pma.kurs), 0) AS value
            FROM detailpmas detailpma
            LEFT JOIN pmas pma ON pma.id = detailpma.pma_id
            
            WHERE pma.tahun = ? AND pma.jenis_berjangka_id IN (" . rtrim(str_repeat('?,', count($tws)), ',') . ")
            GROUP BY pma.jenis_berjangka_id

            UNION ALL

            SELECT 
                'pmdnpma' AS type,
                pm.jenis_berjangka_id AS id,
                COALESCE((IFNULL(pma.tambahan_investasi, 0) + IFNULL(pm.tambahan_investasi, 0)), 0) AS value
            FROM (
                SELECT 
                    pmdns.jenis_berjangka_id,
                    pmdns.tahun AS tahunpmdn,
                    COALESCE(SUM(detailpmdns.tambahan_investasi), 0) AS tambahan_investasi
                FROM detailpmdns
                LEFT JOIN pmdns ON pmdns.id = detailpmdns.pmdn_id
                WHERE pmdns.tahun = ? AND pmdns.jenis_berjangka_id IN (" . rtrim(str_repeat('?,', count($tws)), ',') . ")
                GROUP BY pmdns.jenis_berjangka_id
            ) AS pm
            LEFT JOIN (
                SELECT 
                    pmas.jenis_berjangka_id,
                    pmas.tahun AS tahunpma,
                    COALESCE(SUM(detailpmas.tambahan_investasi * pmas.kurs), 0) AS tambahan_investasi
                FROM detailpmas
                LEFT JOIN pmas ON pmas.id = detailpmas.pma_id
                WHERE pmas.tahun = ? AND pmas.jenis_berjangka_id IN (" . rtrim(str_repeat('?,', count($tws)), ',') . ")
                GROUP BY pmas.jenis_berjangka_id
            ) AS pma ON pm.jenis_berjangka_id = pma.jenis_berjangka_id
        ) AS subquery"))
        ->groupBy('type')
        ->select('type', DB::raw('COALESCE(SUM(value), 0) AS total_value'))
        ->setBindings([
            $tahun,
            'jenis_berjangka_pmdn' => $tws,
            $tahun,
            'jenis_berjangka_pma' => $tws,
            $tahun,
            $tws,
            $tahun,
            $tws,
        ])
        ->get();
    //  dd($query);
        $this->now = $query;
        $this->titleNow = ($tws) ? $this->header.' '.$tahun : '';
        $this->dataTW = $dataTW;
        $this->dataTahun = $tahun;
        $this->kurs = getKurs(implode(', ', $tws),  $this->dataTahun);
        $this->dataNow = $this->getChart($this->now);
    }
    
    private function getDataPast($tahun, $tws)
    {
        switch ($tws) {
            case ($tws >= 1 && $tws <= 4):
                $tws = [$tws];
                break;
            case ($tws == 5):
                $tws = [1,2];
                break;
            case ($tws == 6):
                $tws = [1,2,3];
                break;
            case ($tws == 7):
                $tws = [1,2,3,4];
                break;
            default:
                $this->tw = $this->tw;
                break;
        }
        $query = DB::table(DB::raw("(
            SELECT 
                'PMDN' AS type,
                pmdn.jenis_berjangka_id,
                COALESCE(SUM(detailpmdn.tambahan_investasi), 0) AS value
            FROM detailpmdns detailpmdn
            LEFT JOIN pmdns pmdn ON pmdn.id = detailpmdn.pmdn_id
            
            WHERE pmdn.tahun = ? AND pmdn.jenis_berjangka_id IN (" . rtrim(str_repeat('?,', count($tws)), ',') . ")
            GROUP BY pmdn.jenis_berjangka_id
    
            UNION ALL
    
            SELECT 
                'PMA' AS type,
                pma.jenis_berjangka_id,
                COALESCE(SUM(detailpma.tambahan_investasi * pma.kurs), 0) AS value
            FROM detailpmas detailpma
            LEFT JOIN pmas pma ON pma.id = detailpma.pma_id
            
            WHERE pma.tahun = ? AND pma.jenis_berjangka_id IN (" . rtrim(str_repeat('?,', count($tws)), ',') . ")
            GROUP BY pma.jenis_berjangka_id

            UNION ALL

            SELECT 
                'pmdnpma' AS type,
                pm.jenis_berjangka_id AS id,
                COALESCE((IFNULL(pma.tambahan_investasi, 0) + IFNULL(pm.tambahan_investasi, 0)), 0) AS value
            FROM (
                SELECT 
                    pmdns.jenis_berjangka_id,
                    pmdns.tahun AS tahunpmdn,
                    COALESCE(SUM(detailpmdns.tambahan_investasi), 0) AS tambahan_investasi
                FROM detailpmdns
                LEFT JOIN pmdns ON pmdns.id = detailpmdns.pmdn_id
                WHERE pmdns.tahun = ? AND pmdns.jenis_berjangka_id IN (" . rtrim(str_repeat('?,', count($tws)), ',') . ")
                GROUP BY pmdns.jenis_berjangka_id
            ) AS pm
            LEFT JOIN (
                SELECT 
                    pmas.jenis_berjangka_id,
                    pmas.tahun AS tahunpma,
                    COALESCE(SUM(detailpmas.tambahan_investasi * pmas.kurs), 0) AS tambahan_investasi
                FROM detailpmas
                LEFT JOIN pmas ON pmas.id = detailpmas.pma_id
                WHERE pmas.tahun = ? AND pmas.jenis_berjangka_id IN (" . rtrim(str_repeat('?,', count($tws)), ',') . ")
                GROUP BY pmas.jenis_berjangka_id
            ) AS pma ON pm.jenis_berjangka_id = pma.jenis_berjangka_id
        ) AS subquery"))
        ->groupBy('type')
        ->select('type', DB::raw('COALESCE(SUM(value), 0) AS total_value'))
        ->setBindings([
            $tahun,
            'jenis_berjangka_pmdn' => $tws,
            $tahun,
            'jenis_berjangka_pma' => $tws,
            $tahun,
            $tws,
            $tahun,
            $tws,
        ])
        ->get();
        
        $this->past = $query;
        $this->titlePast = ($tws) ? $this->header.' '.$tahun : '';
        $this->dataPast = $this->getChart($this->past);
        
    }

    private function getDataBefore($tahun, $tws)
    {
        if ($tws == 1) {
            $tahun = $tahun - 1;
            $tws = 4;
            $this->header = 'Triwulan ' . $tws;
        } elseif ($tws >= 2 && $tws <= 4) {
            $tws = $tws - 1;
            $this->header = 'Triwulan ' . $tws;
        } else {
            $tws = 0;
        }
        $query = DB::table(DB::raw("(
            SELECT 
                'PMDN' AS type,
                pmdn.jenis_berjangka_id,
                COALESCE(SUM(detailpmdn.tambahan_investasi), 0) AS value
            FROM detailpmdns detailpmdn
            LEFT JOIN pmdns pmdn ON pmdn.id = detailpmdn.pmdn_id
            
            WHERE pmdn.tahun = ? AND pmdn.jenis_berjangka_id = ?
            GROUP BY pmdn.jenis_berjangka_id
    
            UNION ALL
    
            SELECT 
                'PMA' AS type,
                pma.jenis_berjangka_id,
                COALESCE(SUM(detailpma.tambahan_investasi * pma.kurs), 0) AS value
            FROM detailpmas detailpma
            LEFT JOIN pmas pma ON pma.id = detailpma.pma_id
            
            WHERE pma.tahun = ? AND pma.jenis_berjangka_id = ?
            GROUP BY pma.jenis_berjangka_id

            UNION ALL

            SELECT 
                'pmdnpma' AS type,
                pm.jenis_berjangka_id AS id,
                COALESCE((IFNULL(pma.tambahan_investasi, 0) + IFNULL(pm.tambahan_investasi, 0)), 0) AS value
            FROM (
                SELECT 
                    pmdns.jenis_berjangka_id,
                    pmdns.tahun AS tahunpmdn,
                    COALESCE(SUM(detailpmdns.tambahan_investasi), 0) AS tambahan_investasi
                FROM detailpmdns
                LEFT JOIN pmdns ON pmdns.id = detailpmdns.pmdn_id
                WHERE pmdns.tahun = ? AND pmdns.jenis_berjangka_id = ?
                GROUP BY pmdns.jenis_berjangka_id
            ) AS pm
            LEFT JOIN (
                SELECT 
                    pmas.jenis_berjangka_id,
                    pmas.tahun AS tahunpma,
                    COALESCE(SUM(detailpmas.tambahan_investasi * pmas.kurs), 0) AS tambahan_investasi
                FROM detailpmas
                LEFT JOIN pmas ON pmas.id = detailpmas.pma_id
                WHERE pmas.tahun = ? AND pmas.jenis_berjangka_id = ?
                GROUP BY pmas.jenis_berjangka_id
            ) AS pma ON pm.jenis_berjangka_id = pma.jenis_berjangka_id
        ) AS subquery"))
        ->groupBy('type')
        ->select('type', DB::raw('COALESCE(SUM(value), 0) AS total_value'))
        ->setBindings([
            $tahun,
            'jenis_berjangka_pmdn' => $tws,
            $tahun,
            'jenis_berjangka_pma' => $tws,
            $tahun,
            $tws,
            $tahun,
            $tws,
        ])
        ->get();
        
        $this->before = $query;
        $this->titleBefore = ($tws) ? $this->header.' '.$tahun : '';
        $this->dataBefore = $this->getChart($this->before);

        $this->emit('updateChart', [
            'now' => $this->dataNow,
            'titleNow' => $this->titleNow,
            'past' => $this->dataPast,
            'titlePast' => $this->titlePast,
            'before' => $this->dataBefore,
            'titleBefore' => $this->titleBefore,
        ]);
    }

    private function getChart($value)
    {
        $data = [];

        foreach ($value as $kb) {
            $data[] = [
                'value' => $kb->total_value,
                'name' => $kb->type,
            ];
        }
        
        return $data;
    }
    
}
