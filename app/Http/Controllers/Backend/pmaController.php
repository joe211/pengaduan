<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\pma;
use App\Models\detailpma;
use App\Models\JenisBerjangka;
use App\Models\User;
use Carbon\Carbon;
use DataTables;
use Toastr;
use File;
use Auth;
use App\Imports\detailpmaImport;
use Maatwebsite\Excel\Facades\Excel;

class pmaController extends Controller
{
    private $data;
    private $pmaClass;
    private $detailpmaClass;
    private $JenisBerjangkaClass;
    private $userClass;

    public function __construct()
    {
        $this->pmaClass = new pma;
        $this->detailpmaClass = new detailpma;
        $this->JenisBerjangkaClass = new JenisBerjangka;
        $this->userClass = new User;

        $this->data = [
            'category_name' => 'PMA',
            'main_url' => 'form-pma'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['page_name'] = 'Export PMA';
        return view('dashboard.page.form-pma.index',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['jenis_berjangka_id'] = $this->JenisBerjangkaClass->orderBy('nama','asc')->get();
        $this->data['page_name'] = 'Export pma';
        return view('dashboard.page.form-pma.create',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls|max:2048',
            'jenis_berjangka_id' => 'required',
            'tahun' => 'required',
            'kurs' => 'required'
        ]);
        if ($validator->fails()) {
            Toastr::error('Terjadi Kesalahan Saat Menyimpan Data', 'Gagal', ["positionClass" => "toast-bottom-right"]);
            return Redirect::back()->withErrors($validator)->withInput();
        }
		$file = $request->file('file');
        $nama_file = bersih(rand().$file->getClientOriginalName()).'.'.$file->getClientOriginalExtension();
        $this->pmaClass->user_id = Auth::user()->id;
        $this->pmaClass->jenis_berjangka_id = $request->jenis_berjangka_id;
        $this->pmaClass->nama = $nama_file;
        $this->pmaClass->tahun = $request->tahun;
        $this->pmaClass->kurs = $request->kurs;
        $this->pmaClass->save();

        if($this->pmaClass->id){
		$file->move('file_pma',$nama_file);
		Excel::import(new detailpmaImport($this->pmaClass->id,$nama_file), public_path('/file_pma/'.$nama_file));
        return redirect::to('dashboard/form-pma');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        $this->data['page_name'] = 'Detail pma';
        $this->data['id'] = $id;
        return view('dashboard.page.form-pma.detail',$this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit = $this->pmaClass->find($id);
        $this->data['jenis_berjangka_id'] = $this->JenisBerjangkaClass->orderBy('nama','asc')->get();
        $this->data['edit'] = $edit;
        $this->data['update_name'] = $edit->nama;
        $this->data['page_name'] = 'ubah pma';
        return view('dashboard.page.form-pma.edit',$this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'jenis_berjangka_id' => 'required',
            'tahun' => 'required',
            'kurs' => 'required'
        ]);
        if ($validator->fails()) {
            Toastr::error('Terjadi Kesalahan Saat Menyimpan Data', 'Gagal', ["positionClass" => "toast-bottom-right"]);
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $update = pma::find($id);
        $update->jenis_berjangka_id = $request->jenis_berjangka_id;
        $update->tahun = $request->tahun;
        $update->kurs = $request->kurs;
        $update->save();
        $update ? Toastr::success('Data Berhasil Diubah', 'Sukses', ["positionClass" => "toast-bottom-right"]) : Toastr::error('Terjadi Kesalahan Saat Menyimpan Data', 'Gagal', ["positionClass" => "toast-bottom-right"]);

        return redirect::to('dashboard/form-pma');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletedetail = $this->detailpmaClass->where('pma_id', $id);
        $deletedetail->forceDelete();
        if($deletedetail){
            $delete = $this->pmaClass->find($id);
            $delete->forceDelete();
        }
        return $delete ? 1 : 0;
    }

    public function table(Request $request)
    {   
        
        $data = $this->pmaClass->getData();
            return DataTables::of($data)
            ->addColumn('option', function($data)
            {
                $button = '<div class="dropdown">
                                <button class="btn btn-rounded btn-outline btn-dark dropdown-toggle" type="button" data-toggle="dropdown">Aksi</button>
                                <div class="dropdown-menu">
                                <a class="dropdown-item" href="'.asset('file_pma/'.$data->nama).'"><i class="fa fa-download"></i> Download File</a>
                                <a class="dropdown-item" href="'.url('dashboard/form-pma/detail/'.$data->id).'"><i class="fa fa-list"></i> Detail</a>
                                <a class="dropdown-item" href="'.url('dashboard/form-pma/edit/'.$data->id).'"><i class="fa fa-edit"></i> Edit</a>
                                <a class="dropdown-item" href="#" data-id="'.$data->id.'" data-nama="'.$data->nama.'" data-toggle="modal" data-target="#ModalHapus" ><i class="fa fa-trash"></i> Hapus</a>

                            </div>';
                return  $button;
            })
            ->addColumn('kurs', function($data)
            {
                return  formatRupiah($data->kurs);
            })

            ->addIndexColumn()
            ->rawColumns(['option','kurs'])
            ->make(true);
    }

    public function tabledetail($id)
    {   
        $data = $this->pmaClass->getDataDetail($id);
        return DataTables::of($data)
        ->addColumn('tambahan_investasi', function($data)
        {
            return '<span class="badge badge-dark">'.formatUSD($data->tambahan_investasi).'</span>';
        })

        ->addColumn('total_investasi', function($data)
        {
            return '<span class="badge badge-primary">'.formatUSD($data->total_investasi).'</span>';
        })

        ->addIndexColumn()
        ->rawColumns(['tambahan_investasi','total_investasi'])
        ->make(true);
    }
    
}
