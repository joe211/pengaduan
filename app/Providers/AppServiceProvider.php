<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        
        view()->composer(['frontend.main-1','frontend.include.sidebar','frontend.include.sidebar-profil','frontend.page.kontak.index','frontend.page.konsultasi.index'],function($view)
        {
            $view->with('berita',\App\Models\Berita::leftJoin('kategori_beritas','kategori_beritas.id','beritas.kategori_berita_id')
            ->select(
                'kategori_beritas.nama as nama_kategori',
                'kategori_beritas.slug as slug_kategori',
                'beritas.id',
                'beritas.judul',
                'beritas.meta_deskripsi',
                'beritas.thumbnail',
                'beritas.updated_at',
                'beritas.slug',
            )
            ->orderBy('beritas.dilihat','desc')
            ->groupBy('beritas.id')
            ->limit(5)
            ->get());

            $view->with('berita_terbaru',\App\Models\Berita::leftJoin('kategori_beritas','kategori_beritas.id','beritas.kategori_berita_id')
            ->select(
                'kategori_beritas.nama as nama_kategori',
                'kategori_beritas.slug as slug_kategori',
                'beritas.id',
                'beritas.judul',
                'beritas.meta_deskripsi',
                'beritas.thumbnail',
                'beritas.updated_at',
                'beritas.slug',
            )
            ->orderBy('beritas.created_at','desc')
            ->groupBy('beritas.id')
            ->limit(10)
            ->get());

            $view->with('kategori_unduhan',\App\Models\KategoriUnduhan::orderBy('nama','asc')->orderByDesc('created_at')->get());
            $view->with('profil',\App\Models\Profil::orderBy('nama','asc')->orderByDesc('nama')->get());
            $view->with('aplikasi',\App\Models\Aplikasi::orderBy('nama','asc')->orderByDesc('nama')->get());
            $view->with('link_terkait',\App\Models\LinkTerkait::orderBy('nama','asc')->where('jenis','2')->orderByDesc('nama')->get());
            $view->with('link_terkait_bkd',\App\Models\LinkTerkait::orderBy('nama','asc')->where('jenis','1')->orderByDesc('nama')->get());
            $view->with('kategori_berita_menu',\App\Models\KategoriBerita::orderBy('nama','asc')->orderByDesc('nama')->get());
            $view->with('pengaturan_website',\App\Models\Pengaturan::first());
            $view->with('kategori_unduhan_footer',\App\Models\Unduh::orderBy('created_at','asc')->limit(10)->get());
            $view->with('bidang_menu',\App\Models\Bidang::orderBy('nama','asc')->limit(10)->get());
        });

        // view()->composer('',function($view)
        // {
        //     $view->with('profil',\App\Models\Profil::orderBy('nama','asc')->get());
        // });

        
    }
}
