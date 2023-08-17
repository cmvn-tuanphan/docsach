<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\LoginController as login;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Controllers\CrawlController as crawl;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get("crawl" , [crawl::class , "setup"]);


Route::get('/', function () {
    return "This is homepage";
})->name("index");

Route::prefix('admin')->middleware('admin' , 'auth')->group(function () {
    Route::get('/', function () {
        return "This is adminpage";
    })->name('adminIndex');
});

Route::get('/dang_xuat' , [login::class, "logout"])->name('logout');
Route::get('/dang_nhap', [login::class , "login"])->name('login');
Route::post('/dang_nhap', [login::class , "postLogin"])->name('postLogin');