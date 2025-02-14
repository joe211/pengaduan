<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class sektorController extends Controller
{
    public function __construct()
    {
        $this->data = [
            'category_name' => 'Sektor',
            'main_url' => 'sektor'
        ];
    }

    public function tabelSektor()
    {
        $this->data['page_name'] = 'Tabel Sektor';

        return view('dashboard.page.sektor.tabelsektor',$this->data);
    }

    public function index()
    {
        $this->data['page_name'] = 'Sektor';

        return view('dashboard.page.sektor.sektor',$this->data);
    }


}