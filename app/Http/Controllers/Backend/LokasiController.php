<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LokasiController extends Controller
{
    public function __construct()
    {
        $this->data = [
            'category_name' => 'Lokasi',
            'main_url' => 'lokasi'
        ];
    }

    public function index()
    {
        $this->data['page_name'] = 'Lokasi';

        return view('dashboard.page.lokasi.index',$this->data);
    }
}
