<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
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
        Route::get('/', [PedidoController::class, 'index'])->name('index');
        Route::get('/{cliente_id?}/dependencias', [PedidoController::class, 'dependencias'])->name('dependencias');
        Route::get('/{cliente_id?}/create', [PedidoController::class, 'create'])->name('create');
        Route::get('/{pedido_id?}/edit', [PedidoController::class, 'edit'])->name('edit');
        Route::get('/{pedido}', [PedidoController::class, 'show'])->name('show');
        Route::post('/{pedidoId?}/store', [PedidoController::class, 'store'])->name('store');
        Route::post('/{pedido}/pagar', [PedidoController::class, 'pagar'])->name('pagar');
        Route::post('/{pedido}/produzir', [PedidoController::class, 'produzir'])->name('produzir');
        Route::post('/{pedido}/destroy', [PedidoController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('clientes')->name('clientes.')->group(function () {
        Route::get('/', [ClienteController::class, 'index'])->name('index');
        Route::get('/create', [ClienteController::class, 'create'])->name('create');
        Route::post('/store', [ClienteController::class, 'store'])->name('store');
    });

    Route::prefix('producao')->name('producao.')->group(function () {
        Route::get('/', [SaborController::Class, 'index'])->name('index');
        Route::post('/{produto}/{pedido}', [SaborController::Class, 'store'])->name('marcarProdutoFeito');
    });

    Route::prefix('produtos')->name('produtos.')->group(function () {
        Route::get('/', [ProdutoController::class, 'index'])->name('index');
        Route::get('/dependencias', [ProdutoController::class, 'dependencias'])->name('dependencias');
        Route::post('/store', [ProdutoController::class, 'store'])->name('store');
        Route::get('/{produto}', [ProdutoController::class, 'show'])->name('show');
        Route::post('/{produto}/update', [ProdutoController::class, 'update'])->name('update');
        Route::post('/{produto}/toggle-ativo', [ProdutoController::class, 'toggleAtivo'])->name('toggleAtivo');
        Route::post('/{produto}/destroy', [ProdutoController::class, 'destroy'])->name('destroy');
    });
});
