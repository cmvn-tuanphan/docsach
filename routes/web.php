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

Route::get("crawlertester" , [crawl::class , "setup"]);


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

Route::get('/repharse' , function(){

    $books = DB::table('books')->get();

    foreach($books as $item) {
        $book = $item->description;
        $crawler = new Crawler($book);
        $genreNames = explode("," , str_replace("Thể loại: " , "" , $crawler->filter('.cat')->text()));
        $authorName = $crawler->filter('.author a')->text();
        $author = DB::table("authors")->where("author_name" , $authorName)->first();

        if($author == null) {
            $authorID = DB::table("authors")->insertGetId(["author_name" => $authorName]);
        }
        else {
            $authorID = $author->author_id;
        }
        $category_ids = [];
        $genre_ids = [];
        foreach($genreNames as $genreName) {
            $genre = DB::table('book_genres')->where('genre_name', trim($genreName))->first();
            $category = DB::table("categories")->where('category_name', trim($genreName))->first();  

            if($genre == null) {
                if($category == null) {
                    $newID = DB::table('categories')->insertGetId(['category_name' => trim($genreName)]);
                    array_push($category_ids, $newID);
                }
                else {
                    array_push($category_ids , $category->category_id);
                }
            }
            else {
                array_push($genre_ids , $genre->genre_id);
            }

        }

        DB::table("books")->where("book_id" , $item->book_id)->update(["genre_ids" => implode("," , $genre_ids) , "category_ids" => implode("," , $category_ids) , "author_id" => $authorID]);
        
        
        
        // dd(implode("," , $test_arr));


    }
    //$book = DB::table('books')->where('book_id' , 9)->pluck("description")[0];
   
    dd("Done");



    // Find the genre ID in the database based on the genre name
    // $genreId = DB::table('book_genres')->where('genre_name', $genreName)->first();

    // $category = DB::table("categories")->where('category_name', $genreName)->first();
    
    //$genre = Genre::where('genre_name', $tagName)->first();


    dd($genreId);

    // If the genre is found in the database, insert the book with the genre ID
    if ($genreId) {
        // DB::table('books')->insert([
        //     "cover_image" => $coverImage,
        //     "title" => $title,
        //     "genre_ids" => $genreId, // Use the retrieved genre ID
        //     "author_id" => $authorId,
        //     "category_ids" => $categoryId,
        //     "tag_ids" => $tagId
        // ]);
    } else {
        // Genre not found in the database
        // You might want to handle this case based on your application's logic
    }
});



Route::get('/crawl_chapters' , function(){
    $books = DB::table("books")->get();

    $replacement = '"';
    $problematicChar = "\xE2\x80\x9D";

    foreach($books as $book) {
        $description = $book->description;
        $crawler = new Crawler($description);
      
        $linkNodes = $crawler->filter('.list li a');
  
        $url = $crawler->filter('.list li a')->eq(0)->attr('href');
        $chapterTitle = $crawler->filter('.list li a')->eq(0)->text();
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $textContent = $crawler->filter('.c-content')->first()->text();
        
        DB::table("chapters")->insert(["book_id" => $book->book_id , "chapter_title" => $chapterTitle , "chapter_content" => str_replace($problematicChar, $replacement, $textContent)]);
       
        $description = $book->description;
        $crawler = new Crawler($description);
      
        if($linkNodes->count() > 1) {
            $url = $crawler->filter('.list li a')->eq(1)->attr('href');
            $chapterTitle = $crawler->filter('.list li a')->eq(1)->text();
            $client = new Client();
            $crawler = $client->request('GET', $url);
            $textContent = $crawler->filter('.c-content')->text();
            
            DB::table("chapters")->insert(["book_id" => $book->book_id , "chapter_title" => $chapterTitle , "chapter_content" => str_replace($problematicChar, $replacement, $textContent)]);
           
    
        }
 

    }





});

Route::get('/add_genre' , function(){

$categoryNames = [
    // "Truyện Ngắn - Ngôn Tình",
    "Kiếm Hiệp - Tiên Hiệp",
    "Tiểu Thuyết Phương Tây",
    "Trinh Thám - Hình Sự",
    "Tâm Lý - Kỹ Năng Sống",
    "Huyền bí - Giả Tưởng",
    "Truyện Ma - Truyện Kinh Dị",
    "Y Học - Sức Khỏe",
    "Thiếu Nhi- Tuổi Mới Lớn",
    "Tiểu Thuyết Trung Quốc",
    "Tài Liệu Học Tập",
    "Phiêu Lưu - Mạo Hiểm",
    "Kinh Tế - Quản Lý",
    "Cổ Tích - Thần Thoại",
    "Lịch Sử - Chính Trị",
    "Triết Học",
    "Hồi Ký - Tuỳ Bút",
    "Văn Học Việt Nam",
    "Marketing - Bán hàng",
    "Khoa Học - Kỹ Thuật",
    "Học Ngoại Ngữ",
    "Thư Viện Pháp Luật",
    "Truyện Cười - Tiếu Lâm",
    "Văn Hóa - Tôn Giáo",
    "Tử Vi - Phong Thủy",
    "Thể Thao - Nghệ Thuật",
    "Công Nghệ Thông Tin"
];

$genreNames = [
    "Hiện đại",
    "Sủng",
    "Xuyên không",
    "Cổ đại Ngôn tình",
    "HE - Happy Ending",
    "Tổng tài",
    "Ngược tâm",
    "Sắc",
    "Đô thị tình duyên",
    "Trọng sinh ngôn tình",
    "Nữ cường",
    "Cung đấu",
    "Hài hước",
    "Dị giới ngôn tình",
    "Đam Mỹ",
    "Huyền huyễn",
    "Thanh mai trúc mã/Oan gia",
    "Hào môn thế gia",
    "Dị giới",
    "Võng du",
    "Cổ đại",
    "Dị năng",
    "Hắc bang/hắc đạo",
    "Thanh xuân vườn trường",
    "Trọng sinh",
    "Bách hợp",
    "Tu tiên",
    "Thương trường",
    "Quân nhân",
    "Sách ngoại văn"
];

$client = new Client();


foreach ($categoryNames as $categoryName) {
    $slug = Str::slug($categoryName);
    $url = "https://docsach24.co/dinh-nghia-the-loai/{$slug}.html";
    $client = new Client();
    $crawler = $client->request('GET', $url);
    $description = $crawler->filter('.description')->first()->text();
    $description = str_replace("\n", '', $description);
    
    DB::table('categories')->insert([
        'category_name' => $categoryName,
        'description' => $description,
    ]);
  
    
}


foreach ($genreNames as $genreName) {
    $slug = Str::slug($genreName);
    $url = "https://docsach24.co/dinh-nghia-the-loai/{$slug}.html";
    $client = new Client();
    $crawler = $client->request('GET', $url);
    $description = $crawler->filter('.description')->first()->text();
    $description = str_replace("\n", '', $description);
    
    DB::table('book_genres')->insert([
        'genre_name' => $genreName,
        'description' => $description,
    ]);
  
}

// foreach ($genreNames as $genreName) {
//     DB::table('book_genres')->insert(['genre_name' => $genreName]);
// }

// foreach($categoryNames as $categoryName) {
//     DB::table('categories')->insert(['category_name' => $categoryName]);
// }

});
Route::get('/crawl' , function(){

$categoryNames = [
    "Truyện Ngắn - Ngôn Tình",
    "Kiếm Hiệp - Tiên Hiệp",
    "Tiểu Thuyết Phương Tây",
    "Trinh Thám - Hình Sự",
    "Tâm Lý - Kỹ Năng Sống",
    "Huyền bí - Giả Tưởng",
    "Truyện Ma - Truyện Kinh Dị",
    "Y Học - Sức Khỏe",
    "Thiếu Nhi- Tuổi Mới Lớn",
    "Tiểu Thuyết Trung Quốc",
    "Tài Liệu Học Tập",
    "Phiêu Lưu - Mạo Hiểm",
    "Kinh Tế - Quản Lý",
    "Cổ Tích - Thần Thoại",
    "Lịch Sử - Chính Trị",
    "Triết Học",
    "Hồi Ký - Tuỳ Bút",
    "Văn Học Việt Nam",
    "Marketing - Bán hàng",
    "Khoa Học - Kỹ Thuật",
    "Học Ngoại Ngữ",
    "Thư Viện Pháp Luật",
    "Truyện Cười - Tiếu Lâm",
    "Văn Hóa - Tôn Giáo",
    "Tử Vi - Phong Thủy",
    "Thể Thao - Nghệ Thuật",
    "Công Nghệ Thông Tin"
];

$genreNames = [
    "Hiện đại",
    "Sủng",
    "Xuyên không",
    "Cổ đại Ngôn tình",
    "HE - Happy Ending",
    "Tổng tài",
    "Ngược tâm",
    "Sắc",
    "Đô thị tình duyên",
    "Trọng sinh ngôn tình",
    "Nữ cường",
    "Cung đấu",
    "Hài hước",
    "Dị giới ngôn tình",
    "Đam Mỹ",
    "Huyền huyễn",
    "Thanh mai trúc mã/Oan gia",
    "Hào môn thế gia",
    "Dị giới",
    "Võng du",
    "Cổ đại",
    "Dị năng",
    "Hắc bang/hắc đạo",
    "Thanh xuân vườn trường",
    "Trọng sinh",
    "Bách hợp",
    "Tu tiên",
    "Thương trường",
    "Quân nhân",
    "Sách ngoại văn"
];


    foreach ($categoryNames as $categoryName) {
        $slug = Str::slug($categoryName);
        $url = "https://docsach24.co/the-loai/{$slug}.html";
        
        $client = new Client();

        $crawler = $client->request('GET', $url);

        $categories = DB::table('categories')->pluck("category_name");
        

        $logged = array();
    
        $categories_parsed = array();
        foreach ($categories as $category) {
            $categoryToFind = $category;
            $matchingLink = '';
            $crawler->filter('a.link-default')->each(function ($node) use (&$matchingLink, $categoryToFind) {
                if (strpos($node->text(), $categoryToFind) !== false) {
                    $matchingLink = $node->attr('href');
                }
            });

            $crawler = $client->request('GET', $matchingLink);
            $parentSelector = '#list-posts > div > div > div.col-12.col-lg-9 > div.row';
            $childSelector = 'a.link-default[href^="https://docsach24.co/e-book/"]';
         
            $crawler->filter($parentSelector)->each(function ($parentNode) use ($childSelector , &$logged) {
                $elements = $parentNode->filter($childSelector);

                $elements->each(function ($element) use (&$logged) {
                    $href = $element->attr('href');
                    $text = $element->text();
            
                    if (trim($text) !== '') {
                        $cssSelector = '#box-content > div.detail-post > div > div.col-12.col-xl-8';
                        $clientCSS = new Client();

                        $crawler = $clientCSS->request('GET', $href);
                        $html = $crawler->filter($cssSelector)->html();


                        DB::table('books')->insert(["cover_image" => $href , "title" => $text, "genre_ids" => 1 , "author_id" => 1 , "category_ids" => 1 , "tag_ids" => 1 , "description" => $html]);
                        //array_push($logged , "Link: $href, Text: $text");
                    }
                });
            });


            // $crawler = $client->request('GET', $matchingLink . "?page=2");
            // $crawler->filter($parentSelector)->each(function ($parentNode) use ($childSelector , &$logged) {
            //     $elements = $parentNode->filter($childSelector);

            //     $elements->each(function ($element) use (&$logged) {
            //         $href = $element->attr('href');
            //         $text = $element->text();
            
            //         if (trim($text) !== '') {
            //             DB::table('books')->insert(["cover_image" => $href , "title" => $text, "genre_ids" => 1 , "author_id" => 1, "category_ids" => 1, "tag_ids" => 1]);
            //             //array_push($logged , "Link: $href, Text: $text");
            //         }
            //     });
            // });



        }
    
    }


        dd($logged);
        

    });