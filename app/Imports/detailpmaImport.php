<?php

namespace App\Imports;

use App\Models\detailpma;
use App\Models\pma;
use App\Models\sektor;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Auth;
use Toastr;
use Redirect;
use Illuminate\Support\Facades\Validator;



class detailpmaImport implements ToModel, SkipsEmptyRows, WithHeadingRow
{
    private $pmaId;
    private $nama_file;
    private $pmaClass;

    public function __construct($pmaId, $nama_file)
    {
        $this->pmaId = $pmaId;
        $this->nama_file    = $nama_file;
        $this->pmaClass = new pma;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use Importable;

    public function model(array $row)
        {
        if($row['id_laporan'] == null){
            Toastr::success('Data Berhasil Disimpan', 'Sukses', ["positionClass" => "toast-bottom-right"]);
            return null;
        }else{
        $validator = Validator::make($row, $this->rules());
        if ($validator->fails()) {
            $delete = $this->pmdnClass->where('id', $this->pmaId);
            $delete->forceDelete();
            if (!empty($this->nama_file)) {
                $filePath = public_path('/file_pma/' . $this->nama_file);
                // Pastikan file ada sebelum dihapus
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            Toastr::error('Mohon periksa kembali kolom pada file excel', 'Gagal', ["positionClass" => "toast-bottom-right"]);
            }else{
            $kota           = getKota($row['kabkot']);
            // $cetak_lokasi   = getKota($row['cetak_lokasi']);
            // Cek apakah data sudah ada berdasarkan id_laporan
            $existingData = detailpma::where('id_laporan', $row['id_laporan'])->first();
            $sektor = sektor::where('nama', $row['sektor'])->first();

            $tambahan_investasi_pemisah = isset($row['tambahan_investasi_dalam_us_ribu']) ? str_replace('.', '.', $row['tambahan_investasi_dalam_us_ribu']) : 0;
            $tambahan_investasi = (float)$tambahan_investasi_pemisah  * 1000;
            
            $total_investasi_pemisah = isset($row['total_investasi_dalam_us_ribu']) ? str_replace('.', '.', $row['total_investasi_dalam_us_ribu']) : 0;
            $total_investasi = (float)$total_investasi_pemisah  * 1000;
            
                if (!$existingData) {
                    // dd($this->pmaId);
                    if($row['id_laporan'] !== null){
                    return detailpma::create([
                        'pma_id' => $this->pmaId,
                        'kota_id' => $kota,
                        'sektor_id' => $sektor->id,
                        'periode_tahap' => $row['periode_tahap'], 
                        'jenis_badan_usaha' => $row['jenis_badan_usaha'],
                        'id_laporan' => $row['id_laporan'],
                        'nama_perusahaan' => $row['nama_perusahaan'],
                        'deskripsi_kbli' => $row['deskripsi_kbli'],
                        'wilayah' => $row['wilayah'],
                        'provinsi' => $row['provinsi'],
                        'negara' => $row['negara'],
                        'no_izin' => $row['no_izin'],
                        'tambahan_investasi' => $tambahan_investasi ? $tambahan_investasi : 0,
                        'total_investasi' => $total_investasi ? $total_investasi : 0,
                        'jumlah_proyek' => $row['proyek'],
                        'jumlah_tki' => $row['tki'],
                        'jumlah_tka' => $row['tka']
                    ]);
                    }
                } else {
                    if($row['id_laporan'] !== null){
                    $existingData->update([
                        'pma_id' => $this->pmaId,
                        'kota_id' => $kota,
                        'sektor_id' => $sektor->id,
                        'periode_tahap' => $row['periode_tahap'], 
                        'jenis_badan_usaha' => $row['jenis_badan_usaha'],
                        'nama_perusahaan' => $row['nama_perusahaan'],
                        'deskripsi_kbli' => $row['deskripsi_kbli'],
                        'wilayah' => $row['wilayah'],
                        'provinsi' => $row['provinsi'],
                        'negara' => $row['negara'],
                        'no_izin' => $row['no_izin'],
                        'tambahan_investasi' => $tambahan_investasi ? $tambahan_investasi : 0,
                        'total_investasi' => $total_investasi ? $total_investasi : 0,
                        'jumlah_proyek' => $row['proyek'],
                        'jumlah_tki' => $row['tki'],
                        'jumlah_tka' => $row['tka']
                    ]);

                    return $existingData;
                    }
                }
            }
        }
        }
    public function rules(): array
    {
        return [
            'id_laporan' => 'required|unique:detailpmas',
            'kabkot' => 'required',
            'sektor' => 'required'
        ];
    }

    public function headingRow(): int
    {
        return 2;
    }
}
