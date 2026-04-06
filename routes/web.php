<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\SaborController;
use Illuminate\Support\Facades\Route;

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

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return redirect()->route('vendas.pedidos.index');
});

Route::prefix('vendas')->name('vendas.')->group(function () {
    Route::prefix('pedidos')->name('pedidos.')->group(function () {
        Route::get('/{pedido_id?}', [PedidoController::class, 'index'])->name('index');
        Route::get('/create/{cliente_id?}', [PedidoController::class, 'create'])->name('create');
        Route::post('/store', [PedidoController::class, 'store'])->name('store');
        Route::post('/pagar/{pedido_id?}', [PedidoController::class, 'pagar'])->name('pagar');
//        Route::get('/edit/{pedido_id?}', [PedidoController::class, 'edit'])->name('edit');
//        Route::post('/update/{pedido_id?}', [PedidoController::class, 'update'])->name('update');
        Route::post('/destroy/{pedido_id?}', [PedidoController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('clientes')->name('clientes.')->group(function () {
        Route::get('/', [ClienteController::class, 'index'])->name('index');
        Route::get('/create', [ClienteController::class, 'create'])->name('create');
        Route::post('/store', [ClienteController::class, 'store'])->name('store');
    });

    Route::prefix('producao')->name('producao.')->group(function () {
        Route::get('/', [SaborController::Class, 'index'])->name('index');
        Route::post('/marcar-produto-feito', [SaborController::Class, 'store'])->name('marcarProdutoFeito');
    });
});
