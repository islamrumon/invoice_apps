<?php

use App\Http\Controllers\InvoiceController;
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

Route::get('/', function () {
    return view('welcome');
});




Route::get('/dashboard', function () {
    return redirect()->route('invoices.index');
})->middleware(['auth'])->name('dashboard');


Route::get('create',[InvoiceController::class,'create'])->name('invoices.create');
Route::get('index',[InvoiceController::class,'index'])->name('invoices.index');
Route::post('store',[InvoiceController::class,'store'])->name('invoices.store');
Route::get('edit/{id}',[InvoiceController::class,'edit'])->name('invoices.edit');
Route::post('update',[InvoiceController::class,'update'])->name('invoices.update');
Route::get('show/{id}',[InvoiceController::class,'show'])->name('invoices.show');
Route::get('append',[InvoiceController::class,'appendTd'])->name('append.td');
Route::get('print/{id}',[InvoiceController::class,'print'])->name('invoices.print');
Route::get('pdf/{id}',[InvoiceController::class,'pdf'])->name('invoices.pdf');
require __DIR__.'/auth.php';
