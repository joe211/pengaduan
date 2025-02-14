<?php

namespace App\Http\Livewire\Negaraasal;

use Livewire\Component;
// use App\Models\detailpmdn;
use App\Models\detailpma;
use App\Models\pma;
// use App\Models\pmdn;
use App\Models\JenisBerjangka;
use App\Models\kota;
use DB;

class NegaraModul extends Component
{
    public $PmaClass;
    public $subTotalJumProyek;
    public $subTotalJumInvestasi;
    public $subTotalJumInvestasiAsli; //tambahan untuk mendapatkan angka investasi dalam dollar
    public $subTotalJumTki;
    public $subTotalJumTka;
    public $selectedValue;
    public $tahun;
    public $tw;
    public $title;
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
        $this->loadData('PMA', date('Y'), $this->tw);

        // dd($this->tw);
        // dd($this->PmdnClass);

    }

    public function render()
    {
        return view('livewire.negaraasal.negara-modul', [
            'subTotalJumProyek' => $this->subTotalJumProyek,
            'subTotalJumInvestasi' => $this->subTotalJumInvestasi,
            'subTotalJumInvestasiAsli' => $this->subTotalJumInvestasiAsli, // tambahan
            'subTotalJumTki' => $this->subTotalJumTki,
            'subTotalJumTka' => $this->subTotalJumTka,
        ]);

    }

    public function reloadTable($data, $tahun, $tw)
    {
        $this->loadData($data, $tahun, $tw);
    }

    private function loadData($data = null, $tahun = null, $tw = null)
    {
        if ($data == 'PMA-OLD') {
            $query_OLD = detailpma::query()
                ->rightJoin('kotas as kota1', 'kota1.id', 'detailpmas.kota_id')
                ->leftJoin('pmas', 'pmas.id', 'detailpmas.pma_id')
                ->select('kota1.nama as nama_kota',
                'detailpmas.negara as nama_negara', // nyoba nambah
                        DB::raw('SUM(detailpmas.tambahan_investasi * pmas.kurs) as tambahan_investasi'),
                        DB::raw('SUM(detailpmas.tambahan_investasi) as tambahan_investasi_asli'), // nyoba nambah
                        DB::raw('SUM(detailpmas.jumlah_proyek) as jumlah_proyek'),
                        DB::raw('SUM(detailpmas.jumlah_tki) as jumlah_tki'),
                        DB::raw('SUM(detailpmas.jumlah_tka) as jumlah_tka'))
                ->orderBy('tambahan_investasi', 'desc')
                ->where('kota1.provinsi_id', 4)
                ->groupBy('nama_kota');
        }

        if ($data == 'PMA') {
            $query = detailpma::query()
                ->rightJoin('kotas as kota1', 'kota1.id', '=', 'detailpmas.kota_id')
                ->leftJoin('pmas', 'pmas.id', '=', 'detailpmas.pma_id')
                ->select(
                    'kota1.nama as nama_kota',
                    'detailpmas.negara as negara', // untuk grouping negara
                    DB::raw('SUM(detailpmas.tambahan_investasi * pmas.kurs) as tambahan_investasi'),
                    DB::raw('SUM(detailpmas.tambahan_investasi) as tambahan_investasi_asli'), // tambahan
                    DB::raw('SUM(detailpmas.jumlah_proyek) as jumlah_proyek'),
                    DB::raw('SUM(detailpmas.jumlah_tki) as jumlah_tki'),
                    DB::raw('SUM(detailpmas.jumlah_tka) as jumlah_tka')
                )
                ->orderBy('tambahan_investasi', 'desc')
                ->where('kota1.provinsi_id', 4)
                ->groupBy('negara');
        } else {
            $query = detailpma::query()
                ->rightJoin('kotas as kota1', 'kota1.id', '=', 'detailpmas.kota_id')
                ->leftJoin('pmas', 'pmas.id', '=', 'detailpmas.pma_id')
                ->select(
                    'kota1.nama as nama_kota',
                    'detailpmas.negara as negara', // untuk grouping negara
                    DB::raw('SUM(detailpmas.tambahan_investasi * pmas.kurs) as tambahan_investasi'),
                    DB::raw('SUM(detailpmas.tambahan_investasi) as tambahan_investasi_asli'), // tambahan
                    DB::raw('SUM(detailpmas.jumlah_proyek) as jumlah_proyek'),
                    DB::raw('SUM(detailpmas.jumlah_tki) as jumlah_tki'),
                    DB::raw('SUM(detailpmas.jumlah_tka) as jumlah_tka')
                )
                ->where('kota1.provinsi_id', 4)
                ->groupBy('negara')
                ->orderBy('tambahan_investasi', 'desc');
        }


        if ($tahun) {
            $query->where('tahun', $tahun);
        }else{
            $query->where('tahun', date('Y'));
        }

        if ($tw) {
            $query->where('jenis_berjangka_id', $tw);
            $this->tw = $tw ;
        }else{
            $query->where('jenis_berjangka_id', $this->tw);
            $this->tw = $this->tw;
        }

        $this->cek = $query->get();
        $this->PmaClass = $this->cek;
        // $this->PmdnClass = $this->cek;
        $this->tahun = $tahun ?? date('Y');
        // $this->tw = $tw ;
        // dd($this->tw);

        $this->subTotalJumProyek = $this->PmaClass->sum('jumlah_proyek');
        $this->subTotalJumInvestasi = $this->PmaClass->sum('tambahan_investasi');
        $this->subTotalJumInvestasiAsli = $this->PmaClass->sum('tambahan_investasi_asli'); // tambahan
        $this->subTotalJumTki = $this->PmaClass->sum('jumlah_tki');
        $this->subTotalJumTka = $this->PmaClass->sum('jumlah_tka');

        $this->selectedValue = $data ?? "PMA";
        $this->title = ($data) ? $this->selectedValue.' PER NEGARA ASAL TW '.$this->tw.' '.$this->tahun : "PMDN/PMA PER NEGARA ASAL TW ".$this->tw.' '.$this->tahun;

        $this->dataset = $this->getChartData();
        // dd($this->dataset);
        $this->emit('updateChart', [
            'datasets' => $this->dataset,
            'judul' => ($this->selectedValue) ? $this->selectedValue.' PER NEGARA ASAL TW '.$this->tw.' '.$this->tahun : "PMDN/PMA PER NEGARA ASAL TW ".$this->tw.' '.$this->tahun
        ]);
    }

    private function getChartData()
    {
        $data = [];

        foreach ($this->PmaClass as $kb) {
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
