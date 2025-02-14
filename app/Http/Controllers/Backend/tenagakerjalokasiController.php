<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\levelUser;
use Carbon\Carbon;
use DataTables;
use Toastr;
use File;
use Auth;;
use Illuminate\Support\Facades\Hash;

class tenagakerjalokasiController extends Controller
{
    protected $data;
    public function __construct()
    {
       
        $this->data = [
            'category_name' => 'Tenaga Kerja Perlokasi',
            'main_url' => 'tenagakerjaperlokasi',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['page_name'] = 'Tabel Data Tenaga Kerja Perlokasi';
        return view('dashboard.page.tenagakerjalokasi.index',$this->data); 
    }

    public function createPDF()
{
    // Create mPDF object
    $mpdf = new Mpdf();

    // Generate HTML content (contoh)
    $html = '<h1>Hello, mPDF in Laravel!</h1>';

    // Add HTML content to mPDF
    $mpdf->WriteHTML($html);

    // Output PDF
    $mpdf->Output('output.pdf', 'D');
}
}
