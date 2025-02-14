<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class negaraasalController extends Controller
{
    public function __construct()
    {

        $this->data = [
            'category_name' => 'NEGARA ASAL',
            'main_url' => 'form-negara-asal'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['page_name'] = 'Export Negara Asal';
        return view('dashboard.page.form-negara-asal.index',$this->data);
        //
    }
}
