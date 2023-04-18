<?php

use Illuminate\Support\Facades\Route;
use Modules\Faq\Http\Controllers\FaqAdminController;
use Modules\Faq\Http\Controllers\FaqController;

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

// FAQ
Route::prefix('/faq')->controller(FaqController::class)->group(function () {
    Route::get('/{faqCategory?}', 'index')->name('faq.index');
});

// FAQ 관리자
Route::prefix('adm/faq')->middleware('admin.menu.permission')->controller(FaqAdminController::class)->group(function () {

    Route::prefix('/category')->group(function () {
        Route::get('/', 'indexCategory')->name('admin.faq-category.index');
        Route::get('/create', 'createCategory')->name('admin.faq-category.create');
        Route::post('/', 'storeCategory')->name('admin.faq-category.store');
        Route::get('/{faqCategory}', 'editCategory')->name('admin.faq-category.edit');
        Route::put('/{faqCategory}', 'updateCategory')->name('admin.faq-category.update');
        Route::delete('/{faqCategory}', 'destroyCategory')->name('admin.faq-category.destroy');    
    });

    Route::get('/', 'index')->name('admin.faq.index');
    Route::get('/create', 'create')->name('admin.faq.create');
    Route::post('/', 'store')->name('admin.faq.store');
    Route::get('/{faq}', 'edit')->name('admin.faq.edit');
    Route::put('/{faq}', 'update')->name('admin.faq.update');
    Route::delete('/{faq}', 'destroy')->name('admin.faq.destroy');
});