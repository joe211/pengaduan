<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\pmdn;
use App\Models\detailpmdn;
use App\Models\JenisBerjangka;
use App\Models\User;
use Carbon\Carbon;
use DataTables;
use Toastr;
use File;
use Auth;
use App\Imports\detailpmdnImport;
use Maatwebsite\Excel\Facades\Excel;

class pmdnController extends Controller
{
    private $data;
    private $pmdnClass;
    private $detailpmdnClass;
    private $JenisBerjangkaClass;
    private $userClass;

    public function __construct()
    {
        $this->pmdnClass = new pmdn;
        $this->detailpmdnClass = new detailpmdn;
        $this->JenisBerjangkaClass = new JenisBerjangka;
        $this->userClass = new User;

        $this->data = [
            'category_name' => 'PMDN',
            'main_url' => 'form-pmdn'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['page_name'] = 'Export PMDN';
        return view('dashboard.page.form-pmdn.index',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['jenis_berjangka_id'] = $this->JenisBerjangkaClass->orderBy('nama','asc')->get();
        $this->data['page_name'] = 'Export PMDN';
        return view('dashboard.page.form-pmdn.create',$this->data);
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
        ]);
        if ($validator->fails()) {
            Toastr::error('Terjadi Kesalahan Saat Menyimpan Data', 'Gagal', ["positionClass" => "toast-bottom-right"]);
            return Redirect::back()->withErrors($validator)->withInput();
        }
		$file = $request->file('file');
        $nama_file = bersih(rand().$file->getClientOriginalName()).'.'.$file->getClientOriginalExtension();
        $this->pmdnClass->user_id = Auth::user()->id;
        $this->pmdnClass->jenis_berjangka_id = $request->jenis_berjangka_id;
        $this->pmdnClass->nama = $nama_file;
        $this->pmdnClass->tahun = $request->tahun;
        $this->pmdnClass->save();

        if($this->pmdnClass->id){
		$file->move('file_pmdn',$nama_file);
		Excel::import(new detailpmdnImport($this->pmdnClass->id,$nama_file), public_path('/file_pmdn/'.$nama_file));
        return redirect::to('dashboard/form-pmdn');
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
        $this->data['page_name'] = 'Detail PMDN';
        $this->data['id'] = $id;
        return view('dashboard.page.form-pmdn.detail',$this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit = $this->pmdnClass->find($id);
        $this->data['jenis_berjangka_id'] = $this->JenisBerjangkaClass->orderBy('nama','asc')->get();
        $this->data['edit'] = $edit;
        $this->data['update_name'] = $edit->nama;
        $this->data['page_name'] = 'ubah pmdn';
        return view('dashboard.page.form-pmdn.edit',$this->data);
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
            'tahun' => 'required'
        ]);
        if ($validator->fails()) {
            Toastr::error('Terjadi Kesalahan Saat Menyimpan Data', 'Gagal', ["positionClass" => "toast-bottom-right"]);
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $update = pmdn::find($id);
        $update->jenis_berjangka_id = $request->jenis_berjangka_id;
        $update->tahun = $request->tahun;
        $update->save();
        $update ? Toastr::success('Data Berhasil Diubah', 'Sukses', ["positionClass" => "toast-bottom-right"]) : Toastr::error('Terjadi Kesalahan Saat Menyimpan Data', 'Gagal', ["positionClass" => "toast-bottom-right"]);

        return redirect::to('dashboard/form-pmdn');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletedetail = $this->detailpmdnClass->where('pmdn_id', $id);
        $deletedetail->forceDelete();
        if($deletedetail){
            $delete = $this->pmdnClass->find($id);
            $delete->forceDelete();
        }
        return $delete ? 1 : 0;
    }

    public function table(Request $request)
    {   
        
        $data = $this->pmdnClass->getData();
            return DataTables::of($data)
            ->addColumn('option', function($data)
            {
                $button = '<div class="dropdown">
                                <button class="btn btn-rounded btn-outline btn-dark dropdown-toggle" type="button" data-toggle="dropdown">Aksi</button>
                                <div class="dropdown-menu">
                                <a class="dropdown-item" href="'.asset('file_pmdn/'.$data->nama).'"><i class="fa fa-download"></i> Download File</a>
                                <a class="dropdown-item" href="'.url('dashboard/form-pmdn/detail/'.$data->id).'"><i class="fa fa-list"></i> Detail</a>
                                <a class="dropdown-item" href="'.url('dashboard/form-pmdn/edit/'.$data->id).'"><i class="fa fa-edit"></i> Edit</a>
                                <a class="dropdown-item" href="#" data-id="'.$data->id.'" data-nama="'.$data->nama.'" data-toggle="modal" data-target="#ModalHapus" ><i class="fa fa-trash"></i> Hapus</a>

                            </div>';
                return  $button;
            })

            ->addIndexColumn()
            ->rawColumns(['option'])
            ->make(true);
    }

    public function tabledetail($id)
    {   
        $data = $this->pmdnClass->getDataDetail($id);
        return DataTables::of($data)
        ->addColumn('tambahan_investasi', function($data)
        {
            return '<span class="badge badge-dark">'.formatRupiah($data->tambahan_investasi).'</span>';
        })

        ->addColumn('total_investasi', function($data)
        {
            return '<span class="badge badge-primary">'.formatRupiah($data->total_investasi).'</span>';
        })

        ->addIndexColumn()
        ->rawColumns(['tambahan_investasi','total_investasi'])
        ->make(true);
    }
    
}
