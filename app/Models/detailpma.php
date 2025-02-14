<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detailpma extends Model
{
    use HasFactory;

    protected $table = "detailpmas";

    protected $fillable = ['pma_id','kota_id','id_laporan','periode_tahap','jenis_badan_usaha','tahun','nama_perusahaan','cetak_lokasi','sektor','deskripsi_kbli','wilayah','provinsi','negara','no_izin','tambahan_investasi','total_investasi','jumlah_proyek','jumlah_tki','jumlah_tka'];

    public function getData()
    {
        $data = $this->leftJoin('kotas as kota1', 'kota1.id', 'pmas.kota_id')
                ->leftJoin('kotas as kota2', 'kota2.id', 'pmas.cetak_lokasi')
                ->leftJoin('jenis_data', 'jenis_data.id', 'pmas.jenis_data_id')
                ->leftJoin('jenis_berjangkas', 'jenis_berjangkas.id', 'pmas.jenis_berjangka_id')
                ->select(
                    'jenis_data.nama as nama_jenis_data',
                    'jenis_berjangkas.nama as nama_jenis_berjangka',
                    'pmas.id_laporan',
                    'pmas.tahun',
                    'kota1.nama as nama_kota',
                    'kota2.nama as nama_lokasi',
                    'pmas.periode_tahap',
                    'pmas.jenis_badan_usaha',
                    'pmas.nama_perusahaan',
                    'pmas.sektor',
                    'pmas.deskripsi_kbli',
                    'pmas.wilayah',
                    'pmas.provinsi',
                    'pmas.negara',
                    'pmas.no_izin',
                    'pmas.tambahan_investasi',
                    'pmas.total_investasi',
                    'pmas.jumlah_proyek',
                    'pmas.jumlah_tki',
                    'pmas.jumlah_tka',
                    'pmas.jenis_data_id',
                )
                ->orderBy('pmas.created_at', 'desc')
                ->get();

        return $data;
    }

}
