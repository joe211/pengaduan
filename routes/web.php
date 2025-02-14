<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\sektor\TabelSektorModul;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::prefix('/login')->group(function(){
    Route::get('login', function () {
        return redirect('login');
    });
});

Route::prefix('/')->group(function(){
    Route::resource('/','Frontend\DashboardController');
});

// Route::group(['middleware' => 'auth'],function()
// {
//     Route::get('/lokasi', "Backend\LokasiController@index");
//     Route::get('/negara-asal', "Backend\NegaraController@index");

// });

// Route::group(['middleware' => 'auth'],function()
// {
//     Route::get('/sektor', "Backend\sektorController@index");
//     Route::get('/tabelsektor', "Backend\sektorController@tabelSektor");
//     Route::get('/export/{tahun}/{triwulan}', [TabelSektorModul::class, 'ExportExcel'])->name('export');



// });
Route::group(['prefix' => 'dashboard',  'middleware' => 'auth'],function()
{
    Route::get('/', "Backend\DashboardController@index");
    

    // // START PMDN
    // Route::prefix('/form-pmdn')->group(function(){
    //     Route::resource('/','Backend\pmdnController');
    //     Route::get('/table','Backend\pmdnController@table');
    //     Route::get('/tabledetail/{id}','Backend\pmdnController@tabledetail');
    //     Route::get('/edit/{id}','Backend\pmdnController@edit');
    //     Route::get('/detail/{id}','Backend\pmdnController@detail');
    //     Route::patch('/update/{id}','Backend\pmdnController@update');
    //     Route::delete('/delete/{id}','Backend\pmdnController@destroy');
    // });
    // // END PMDN

    // // START PMA
    // Route::prefix('/form-pma')->group(function(){
    //     Route::resource('/','Backend\pmaController');
    //     Route::get('/table','Backend\pmaController@table');
    //     Route::get('/tabledetail/{id}','Backend\pmaController@tabledetail');
    //     Route::get('/edit/{id}','Backend\pmaController@edit');
    //     Route::get('/detail/{id}','Backend\pmaController@detail');
    //     Route::patch('/update/{id}','Backend\pmaController@update');
    //     Route::delete('/delete/{id}','Backend\pmaController@destroy');
    // });
    // // END PMA

    // START LEVEL USER
    Route::prefix('/level-user')->group(function(){
        Route::resource('/','Backend\LevelUserController');
        Route::get('/table','Backend\LevelUserController@table');
        Route::get('/edit/{id}','Backend\LevelUserController@edit');
        Route::patch('/update/{id}','Backend\LevelUserController@update');
        Route::delete('/delete/{id}','Backend\LevelUserController@destroy');
    });
    // END LEVEL USER

    // START USER
    Route::prefix('/user')->group(function(){
        Route::resource('/','Backend\UserController');
        Route::get('/table','Backend\UserController@table');
        Route::get('/edit/{id}','Backend\UserController@edit');
        Route::patch('/update/{id}','Backend\UserController@update');
        Route::delete('/delete/{id}','Backend\UserController@destroy');
    });
    // END USER

    // // START SEKTOR
    // Route::prefix('/form-sektor')->group(function(){
    //     Route::resource('/','Backend\tsektorlengkapController');
    //     Route::get('/table','Backend\tsektorlengkapController@table');
    //     Route::get('/tabledetail','Backend\tsektorlengkapController@tabledetail');
    //     Route::get('/edit/{id}','Backend\tsektorlengkapController@edit');
    //     Route::get('/detail/{id}','Backend\tsektorlengkapController@detail');
    //     Route::patch('/update/{id}','Backend\tsektorlengkapController@update');
    //     Route::delete('/delete/{id}','Backend\sektorController@destroy');
    // });
    // // END SEKTOR

    // // START SEKTOR
    // Route::prefix('/form-sektor')->group(function(){
    //     Route::resource('/','Backend\sektorController');
    //     Route::get('/table','Backend\sektorController@table');
    //     Route::get('/tabledetail','Backend\sektorController@tabledetail');
    //     Route::get('/edit/{id}','Backend\sektorController@edit');
    //     Route::get('/detail/{id}','Backend\sektorController@detail');
    //     Route::patch('/update/{id}','Backend\sektorController@update');
    //     Route::delete('/delete/{id}','Backend\sektorController@destroy');
    // });
    // // END SEKTOR

    // // START SEKTOR
    // Route::prefix('/form-grafiksektor')->group(function(){
    //     Route::resource('/','Backend\sektorController');
    //     Route::get('/table','Backend\sektorController@table');
    //     Route::get('/tabledetail','Backend\sektorController@tabledetail');
    //     Route::get('/edit/{id}','Backend\sektorController@edit');
    //     Route::get('/detail/{id}','Backend\sektorController@detail');
    //     Route::patch('/update/{id}','Backend\sektorController@update');
    //     Route::delete('/delete/{id}','Backend\sektorController@destroy');
    // });
    // // END SEKTOR

    //  // START Tenaga Kerja
    //  Route::prefix('/tenagakerjalokasi')->group(function(){
    //     Route::resource('/','Backend\tenagakerjalokasiController');
    //     Route::get('/table','Backend\tenagakerjalokasiController@table');
    //     Route::get('/pdf-generator','Livewire\Tenagakerja\FilterTenagaKerjaLokasi@generatePDF');
        


    // });
    // Route::prefix('/tenagakerjasektor')->group(function(){
    //     Route::resource('/','Backend\tenagakerjasektorController');
    //     Route::get('/table','Backend\tenagakerjasektorController@table');
    //     Route::get('/create-pdf','Backend\tenagakerjasektorController@createPDF');


    // });

    // Route::prefix('/tenagakerjapenyerapan')->group(function(){
    //     Route::resource('/','Backend\tenagakerjapenyerapanController');
    //     Route::get('/table','Backend\tenagakerjapenyerapanController@table');
    //     Route::get('/create-pdf','Backend\tenagakerjapenyerapanController@createPDF');


    // });

    // // START negaraasal
    // Route::prefix('/form-negara-asal')->group(function(){
    //     Route::resource('/','Backend\NegaraasalController');
    //     Route::get('/table','Backend\NegaraasalController@table');
    //     Route::get('/tabledetail','Backend\NegaraasalController@tabledetail');
    //     Route::get('/edit/{id}','Backend\NegaraasalController@edit');
    //     Route::get('/detail/{id}','Backend\NegaraasalController@detail');
    //     Route::patch('/update/{id}','Backend\NegaraasalController@update');
    //     Route::delete('/delete/{id}','Backend\NegaraasalController@destroy');
    // });
    // // END negaraasal

    // //END lokasi


});
