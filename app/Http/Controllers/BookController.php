<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookGenRes;
use App\Models\Category;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function getAll(){
        $books = Book::paginate(20);
        $topBooks = Book::orderBy('created_at', 'asc')
        ->take(3)
        ->get();

        $categories = Category::get();
        $genres = BookGenRes::get();

        return view('user.layout.home')->with('books', $books)
        ->with('books3', $topBooks)->with('categories', $categories)->with('genres', $genres)
        ->with('title', 'Danh sách truyện');
        
    }

    public function search(Request $request) {
        $query = $request->input('search');
        $books = Book::where('title', 'LIKE', "%" . $query ."%")->paginate(10); 
        $topBooks = Book::orderBy('created_at', 'asc')
        ->take(3)
        ->get();
        $categories = Category::get();
        $genres = BookGenRes::get();

        $title = 'Danh sách tìm kiếm';
        if($books->count() == 0 ){
            $title = 'Không tìm thấy sách';
        }
        return view('user.layout.home')->with('books', $books)
        ->with('books3', $topBooks)->with('categories', $categories)->with('genres', $genres)
        ->with('title', $title)->with('query' ,$query);
    }

    public function getByCategory ($category_id) {
        $books = Book::whereRaw('category_ids = ?', [$category_id])->paginate(10);
        $topBooks = Book::orderBy('created_at', 'asc')
        ->take(3)
        ->get();


        $title = Category::where('category_id', $category_id)->pluck('category_name')[0];
        //dd($title);
        $categories = Category::get();
        $genres = BookGenRes::get();

        return view('user.layout.home')->with('books', $books)
        ->with('books3', $topBooks)->with('categories', $categories)->with('genres', $genres)
        ->with('title', $title);
    }

    public function getByGenre ($genre_id) {
        $books = Book::whereRaw('genre_ids = ?', [$genre_id])->paginate(10);
        $topBooks = Book::orderBy('created_at', 'asc')
        ->take(3)
        ->get();

        $title = BookGenRes::where('genre_id', $genre_id)->pluck('genre_name')[0];

        $categories = Category::get();
        $genres = BookGenRes::get();

        return view('user.layout.home')->with('books', $books)
        ->with('books3', $topBooks)->with('categories', $categories)->with('genres', $genres)
        ->with('title', $title);
        
    }

    public function getBookById ($book_id) {
        $categories = Category::get();
        $genres = BookGenRes::get();

        $topBooks = Book::orderBy('created_at', 'asc')
        ->take(3)
        ->get();

        $book = Book::where('book_id', $book_id)->first();

        $category_ids = explode(',', $book->category_ids);
        $categoriesName = Category::whereIn('category_id', $category_ids)->get();

        $genre_ids = explode(',', $book->genre_ids);
        $genresName = BookGenRes::whereIn('genre_id', $genre_ids)->get();

        $relatedBooks = Book::whereRaw('category_ids = ?', [$book->category_ids])->where('book_id', '!=', $book->book_id)->take(4)->get();

        $author = Author::where('author_id', $book->author_id)->first();

        $chapters = Chapter::where('book_id', $book_id)->get();

        return view('user.layout.book')->with('book' ,$book)->with('books3', $topBooks)
        ->with('categories', $categories)->with('categoriesName', $categoriesName)->with('genres', $genres)
        ->with('genresName', $genresName)->with('relatedBooks', $relatedBooks)
        ->with('author', $author)->with('chapters', $chapters);
    }

    public function getChapterById ($chapter_id) {
        $categories = Category::get();
        $genres = BookGenRes::get();

        $topBooks = Book::orderBy('created_at', 'asc')
        ->take(3)
        ->get();

        $chapter = Chapter::where('chapter_id', $chapter_id)->first();
        $book = Book::where('book_id', $chapter->book_id)->first();
        $allChapterInBook = Chapter::where('book_id', $chapter->book_id)->get();

        return view('user.layout.chapter')->with('books3', $topBooks)
        ->with('categories', $categories)->with('genres', $genres)
        ->with('chapter', $chapter)->with('book', $book)
        ->with("chaptersInBook", $allChapterInBook);
    }

    public function showChapterById (Request $request) {
        $chapter_id = $request->input('chapter_id');

        return redirect()->route('chapter', ['id' => $chapter_id]);
    }
}
