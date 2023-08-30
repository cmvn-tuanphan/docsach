<?php

use App\Http\Controllers\BookController;
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

Route::middleware('auth')->group(function () {
    Route::get('/', [BookController::class, "getAll"])->name("index");

    Route::get("/category/{id}", [BookController::class, "getByCategory"]);

    Route::get('genre/{id}', [BookController::class, 'getByGenre']);

    Route::get('/book/{id}', [BookController::class, 'getBookById']);

    Route::get('chapter/{id}', [BookController::class, 'getChapterById'])->name('chapter');

    Route::post('chapter/show', [BookController::class, 'showChapterById'])->name('chapter.show');

    Route::post('search', [BookController::class, 'search'])->name('search');   

    Route::get('favorite', [BookController::class, 'favorite'])->name('favorite');

    Route::post('/favorite/{book}', [BookController::class, 'toggleFavorite'])->name('book.toggleFavorite');

});



Route::post('search', [BookController::class, 'search'])->name('search');   

Route::prefix('admin')->middleware('admin' , 'auth')->group(function () {
    Route::get('/', function () {
        return "This is adminpage";
    })->name('adminIndex');
});

Route::get('/dang_xuat' , [login::class, "logout"])->name('logout');

Route::get('/dang_nhap', [login::class , "login"])->name('login');
Route::post('/dang-nhap', [login::class , "postLogin"])->name('postLogin');

Route::get('/dang_ki', [login::class , "signup"])->name('signup');
Route::post('/dang_ki', [login::class , "postSignup"])->name('postSignup');



