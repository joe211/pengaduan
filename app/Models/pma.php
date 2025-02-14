<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pma extends Model
{
    use HasFactory;

    protected $table = "pmas";
 
    protected $fillable = ['user_id','jenis_data_id','jenis_berjangka_id','kota_id','id_laporan','periode_tahap','jenis_badan_usaha','tahun','nama_perusahaan','cetak_lokasi','sektor','deskripsi_kbli','wilayah','provinsi','negara','no_izin','tambahan_investasi','total_investasi','jumlah_proyek','jumlah_tki','jumlah_tka'];

    public function getData()
    {
        $data = $this->leftJoin('jenis_berjangkas', 'jenis_berjangkas.id', 'pmas.jenis_berjangka_id')
                ->select(
                    'pmas.*',
                    'jenis_berjangkas.nama as nama_jenis_berjangka'
                )
                ->orderBy('pmas.created_at', 'desc')
                ->get();

        return $data;
    }

    public function getDataDetail($id)
    {
        $data = $this->leftJoin('detailpmas', 'detailpmas.pma_id', 'pmas.id')
                ->leftJoin('kotas as kota1', 'kota1.id', 'detailpmas.kota_id')
                ->leftJoin('jenis_berjangkas', 'jenis_berjangkas.id', 'pmas.jenis_berjangka_id')
                ->leftJoin('sektors', 'sektors.id', 'detailpmas.sektor_id')
                ->leftJoin('sektorutamas', 'sektorutamas.id', 'sektors.sektor_utama_id')
                ->select(
                    'jenis_berjangkas.nama as nama_jenis_berjangka',
                    'detailpmas.id_laporan',
                    'pmas.tahun',
                    'kota1.nama as nama_kota',
                    'detailpmas.periode_tahap',
                    'detailpmas.jenis_badan_usaha',
                    'detailpmas.nama_perusahaan',
                    'detailpmas.deskripsi_kbli',
                    'detailpmas.wilayah',
                    'detailpmas.provinsi',
                    'detailpmas.negara',
                    'detailpmas.no_izin',
                    'detailpmas.tambahan_investasi',
                    'detailpmas.total_investasi',
                    'detailpmas.jumlah_proyek',
                    'detailpmas.jumlah_tki',
                    'detailpmas.jumlah_tka',
                    'sektorutamas.nama as nama_sektor_utama',
                    'sektors.nama as sektor'
                )
                ->where('pmas.jenis_berjangka_id', $id)
                ->orderBy('detailpmas.id', 'asc')
                ->get();

        return $data;
    }

}
