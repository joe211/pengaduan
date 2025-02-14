<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detailpmdn extends Model
{
    use HasFactory;

    protected $table = "detailpmdns";
 
    protected $fillable = ['sektor_id','pmdn_id','kota_id','id_laporan','periode_tahap','jenis_badan_usaha','tahun','nama_perusahaan','deskripsi_kbli','wilayah','provinsi','negara','no_izin','tambahan_investasi','total_investasi','jumlah_proyek','jumlah_tki','jumlah_tka'];

    public function getData()
    {
        $data = $this->leftJoin('kotas as kota1', 'kota1.id', 'pmdns.kota_id')
                ->leftJoin('kotas as kota2', 'kota2.id', 'pmdns.cetak_lokasi')
                ->leftJoin('jenis_data', 'jenis_data.id', 'pmdns.jenis_data_id')
                ->leftJoin('jenis_berjangkas', 'jenis_berjangkas.id', 'pmdns.jenis_berjangka_id')
                ->select(
                    'jenis_data.nama as nama_jenis_data',
                    'jenis_berjangkas.nama as nama_jenis_berjangka',
                    'pmdns.id_laporan',
                    'pmdns.tahun',
                    'kota1.nama as nama_kota',
                    'kota2.nama as nama_lokasi',
                    'pmdns.periode_tahap',
                    'pmdns.jenis_badan_usaha',
                    'pmdns.nama_perusahaan',
                    'pmdns.sektor',
                    'pmdns.deskripsi_kbli',
                    'pmdns.wilayah',
                    'pmdns.provinsi',
                    'pmdns.negara',
                    'pmdns.no_izin',
                    'pmdns.tambahan_investasi',
                    'pmdns.total_investasi',
                    'pmdns.jumlah_proyek',
                    'pmdns.jumlah_tki',
                    'pmdns.jumlah_tka',
                    'pmdns.jenis_data_id',
                )
                ->orderBy('pmdns.created_at', 'desc')
                ->get();

        return $data;
    }

}
