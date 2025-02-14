<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menampilkan daftar pengaduan
    public function index()
    {
        $pengaduan = Pengaduan::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('pengaduan.index', compact('pengaduan'));
    }

    // Menampilkan form tambah pengaduan
    public function create()
    {
        return view('pengaduan.create');
    }

    // Menyimpan pengaduan baru
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        Pengaduan::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'status' => 'Diajukan',
        ]);

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil diajukan.');
    }

    // Menampilkan detail pengaduan
    public function show($id)
    {
        $pengaduan = Pengaduan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('pengaduan.show', compact('pengaduan'));
    }

    // Menampilkan form edit pengaduan
    public function edit($id)
    {
        $pengaduan = Pengaduan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('pengaduan.edit', compact('pengaduan'));
    }

    // Memperbarui pengaduan
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $pengaduan = Pengaduan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $pengaduan->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil diperbarui.');
    }

    // Menghapus pengaduan
    public function destroy($id)
    {
        $pengaduan = Pengaduan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $pengaduan->delete();

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dihapus.');
    }
}
