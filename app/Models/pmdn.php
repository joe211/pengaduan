<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pmdn extends Model
{
    use HasFactory;

    protected $table = "pmdns";
 
    protected $fillable = ['user_id','jenis_data_id','jenis_berjangka_id','kota_id','id_laporan','periode_tahap','jenis_badan_usaha','tahun','nama_perusahaan','cetak_lokasi','sektor','deskripsi_kbli','wilayah','provinsi','negara','no_izin','tambahan_investasi','total_investasi','jumlah_proyek','jumlah_tki','jumlah_tka'];

    public function getData()
    {
        $data = $this->leftJoin('jenis_berjangkas', 'jenis_berjangkas.id', 'pmdns.jenis_berjangka_id')
                ->select(
                    'pmdns.*',
                    'jenis_berjangkas.nama as nama_jenis_berjangka'
                )
                ->orderBy('pmdns.created_at', 'desc')
                ->get();

        return $data;
    }

    public function getDataDetail($id)
    {
        $data = $this->leftJoin('detailpmdns', 'detailpmdns.pmdn_id', 'pmdns.id')
                ->leftJoin('kotas as kota1', 'kota1.id', 'detailpmdns.kota_id')
                ->leftJoin('jenis_berjangkas', 'jenis_berjangkas.id', 'pmdns.jenis_berjangka_id')
                ->leftJoin('sektors', 'sektors.id', 'detailpmdns.sektor_id')
                ->leftJoin('sektorutamas', 'sektorutamas.id', 'sektors.sektor_utama_id')
                ->select(
                    'jenis_berjangkas.nama as nama_jenis_berjangka',
                    'detailpmdns.id_laporan',
                    'pmdns.tahun',
                    'kota1.nama as nama_kota',
                    'detailpmdns.periode_tahap',
                    'detailpmdns.jenis_badan_usaha',
                    'detailpmdns.nama_perusahaan',
                    'detailpmdns.deskripsi_kbli',
                    'detailpmdns.wilayah',
                    'detailpmdns.provinsi',
                    'detailpmdns.negara',
                    'detailpmdns.no_izin',
                    'detailpmdns.tambahan_investasi',
                    'detailpmdns.total_investasi',
                    'detailpmdns.jumlah_proyek',
                    'detailpmdns.jumlah_tki',
                    'detailpmdns.jumlah_tka',
                    'sektorutamas.nama as nama_sektor_utama',
                    'sektors.nama as sektor'
                )
                ->where('pmdns.jenis_berjangka_id', $id)
                ->orderBy('detailpmdns.id', 'asc')
                ->get();

        return $data;
    }

}
