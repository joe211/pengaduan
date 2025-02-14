<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NegaraController extends Controller
{
    public function __construct()
    {
        $this->data = [
            'category_name' => 'Negara Asal',
            'main_url' => 'negara-asal'
        ];
    }

    public function index()
    {
        $this->data['page_name'] = 'Negara Asal';

        return view('dashboard.page.negara-asal.index',$this->data);
    }
}
