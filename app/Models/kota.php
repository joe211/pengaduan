<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class kota extends Model
{
    use SoftDeletes;
    use HasFactory;

    public function getListByProv($id_provinsi)
    {
        return $this->where('provinsi_id',$id_provinsi)
            ->select(
                'id',
                'nama as text'
                )
            ->orderBy('nama','asc')
            ->get();
    }
}
