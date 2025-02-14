<?php

namespace App\Http\Livewire\Negara;

use Livewire\Component;
use App\Models\detailpma;
use App\Models\pma;
use App\Models\JenisBerjangka;
use DB;

class NegaraModul extends Component
{
    public $PmdnClass;
    public $subTotalJumProyek;
    public $subTotalJumInvestasi;
    public $subTotalJumTki;
    public $subTotalJumTka;
    public $tahun;
    public $data;
    public $tw;
    public $title;
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
        $this->reloadTable(date('Y'), $this->tw);

    }

    public function render()
    {
        return view('livewire.negara.negara-modul', [
            'subTotalJumProyek' => $this->subTotalJumProyek,
            'subTotalJumInvestasi' => $this->subTotalJumInvestasi,
            'subTotalJumTki' => $this->subTotalJumTki,
            'subTotalJumTka' => $this->subTotalJumTka,
        ]);
    }

    public function reloadTable($tahun, $tw)
    {
        $query = detailpma::query()
            ->leftJoin('pmas', 'pmas.id', 'detailpmas.pma_id')
            ->select('negara', 
                    DB::raw('SUM(detailpmas.tambahan_investasi) / 1000 as investasi_dolar'),
                    DB::raw('SUM(detailpmas.tambahan_investasi * pmas.kurs) as tambahan_investasi'),
                    DB::raw('SUM(detailpmas.jumlah_proyek) as jumlah_proyek'),
                    DB::raw('SUM(detailpmas.jumlah_tki) as jumlah_tki'),
                    DB::raw('SUM(detailpmas.jumlah_tka) as jumlah_tka'))
            ->orderBy('tambahan_investasi', 'desc')
            ->groupBy('negara');
            
        if ($tahun) {
            $query->where('tahun', $tahun);
            
            if ($tw <= 4) {
                $query->where('jenis_berjangka_id', $tw);
                $this->tw = "Triwulan ".$tw;
            }else if ($tw == 5) {
                $query->whereIn('jenis_berjangka_id', [1,2]);
                // dd($query->toSql());
                $this->tw = 'Januari - Juni';
            }else if ($tw == 6) {
                $query->whereIn('jenis_berjangka_id', [1,2,3]);
                $this->tw = 'Januari - September';
            }else if ($tw == 6) {
                $this->tw = 'Januari - Desember';
            }
        }else{
            $query->where('tahun', date('Y'));
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

        $this->title = "PMA PER NEGARA ASAL ".$this->tw.' '.$this->tahun;

        $this->dataset = $this->getChartData();
        // dd($this->dataset);
        $this->emit('updateChart', [
            'datasets' => $this->dataset,
            'judul' => $this->title
        ]);
    }

    private function getChartData()
    {
        $data = [];

        foreach ($this->PmdnClass as $kb) {
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
