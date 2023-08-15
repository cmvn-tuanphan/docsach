<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use Illuminate\Support\Str;
use DB;
use Symfony\Component\DomCrawler\Crawler;

class CrawlController extends Controller
{
    private $client;
    private $categoryNames = [
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
    
    private $genreNames = [
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


    public function setup(){
        $this->client = new Client();
        $this->addCategories();
        $this->addGenres();
        DB::table("authors")->insert(["author_name" => 1]);
        $this->crawlBooksFromGenres(2);
        $this->crawlBooksFromCategories(2);
        $this->mappingGenresAndCategories();
        $this->crawl_books_chapters();
    }

    function addCategories(){
        // Setup Genres Categories
        foreach ($this->categoryNames as $categoryName) {
            $slug = Str::slug($categoryName);
            $crawler = $this->client->request('GET', "https://docsach24.co/dinh-nghia-the-loai/{$slug}.html");
            $description = str_replace("\n", '', $crawler->filter('.description')->first()->text());
            DB::table('categories')->insert([
                'category_name' => $categoryName,
                'description' => $description,
            ]);
        }

    }

    function addGenres(){
        // Setup Genres 
        foreach ($this->genreNames as $genreName) {
            $slug = Str::slug($genreName);
            $crawler = $this->client->request('GET', "https://docsach24.co/dinh-nghia-the-loai/{$slug}.html");
            $description = str_replace("\n", '', $crawler->filter('.description')->first()->text());
            DB::table('book_genres')->insert([
                'genre_name' => $genreName,
                'description' => $description,
            ]);
        }
    }


    function crawlBooksFromGenres($amount){
        foreach ($this->genreNames as $genreName) {
            for ($i = 0 ; $i < $amount ; $i++) {
                $slug = Str::slug($genreName);
                $crawler = $this->client->request('GET', "https://docsach24.co/the-loai/{$slug}.html" . ($i == 0 ? "" : "page=" . $i));
                $parentSelector = '#list-posts > div > div > div.col-12.col-lg-9 > div.row';
                $childSelector = 'a.link-default[href^="https://docsach24.co/e-book/"]';
                $crawler->filter($parentSelector)->each(function ($parentNode) use ($childSelector) {
                    $elements = $parentNode->filter($childSelector);
                    $elements->each(function ($element) use (&$logged) {
                        $href = $element->attr('href');
                        $text = $element->text();
                
                        if (trim($text) !== '') {
                            $crawler = $this->client->request('GET', $href);
                            $html = $crawler->filter('#box-content > div.detail-post > div > div.col-12.col-xl-8')->html();
                            DB::table('books')->insert(["cover_image" => "" , "title" => $text, "genre_ids" => 1 , "author_id" => 1 , "category_ids" => 1 , "tag_ids" => 1 , "parserElem" => $html , "parserLink" => $href]);
                        }
                    });
                }); 
            }
        }
    }


    function crawlBooksFromCategories($amount){
        foreach ($this->categoryNames as $categoryName) {
            for ($i = 0 ; $i < $amount ; $i++) {
                $slug = Str::slug($categoryName);
                $crawler = $this->client->request('GET', "https://docsach24.co/the-loai/{$slug}.html" . ($i == 0 ? "" : "page=" . $i));
                $parentSelector = '#list-posts > div > div > div.col-12.col-lg-9 > div.row';
                $childSelector = 'a.link-default[href^="https://docsach24.co/e-book/"]';
                $crawler->filter($parentSelector)->each(function ($parentNode) use ($childSelector) {
                    $elements = $parentNode->filter($childSelector);
                    $elements->each(function ($element) use (&$logged) {
                        $href = $element->attr('href');
                        $text = $element->text();
                
                        if (trim($text) !== '') {
                            $crawler = $this->client->request('GET', $href);
                            $html = $crawler->filter('#box-content > div.detail-post > div > div.col-12.col-xl-8')->html();
                            DB::table('books')->insert(["cover_image" => "" , "title" => $text, "genre_ids" => 1 , "author_id" => 1 , "category_ids" => 1 , "tag_ids" => 1 , "parserElem" => $html , "parserLink" => $href]);
                        }
                    });
                }); 
            }
        }
    }

    function mappingGenresAndCategories(){
        $books = DB::table('books')->get();
        foreach($books as $item) {
            $book = $item->parserElem;
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

            $cover_image = $crawler->filter('img')->eq(0)->attr('src');
            $description = $crawler->filter('.description')->eq(0)->text();
            DB::table("books")->where("book_id" , $item->book_id)->update(["description" => $description , "cover_image" => $cover_image , "genre_ids" => implode("," , $genre_ids) , "category_ids" => implode("," , $category_ids) , "author_id" => $authorID]);
        }
    }

    function crawl_books_chapters(){
        $books = DB::table("books")->get();

        $replacement = '"';
        $problematicChar = "\xE2\x80\x9D";
    
        foreach($books as $book) {
            $crawler = new Crawler($book->parserElem);
          
            $linkNodes = $crawler->filter('.list li a');
            $url = $crawler->filter('.list li a')->eq(0)->attr('href');
            $chapterTitle = $crawler->filter('.list li a')->eq(0)->text();
            $crawler = $this->client->request('GET', $url);
            $textContent = $crawler->filter('.c-content')->first()->text();
            DB::table("chapters")->insert(["book_id" => $book->book_id , "chapter_title" => $chapterTitle , "chapter_content" => str_replace($problematicChar, $replacement, $textContent)]);
            $description = $book->parserElem;
            $crawler = new Crawler($description);
            if($linkNodes->count() > 1) {
                $url = $crawler->filter('.list li a')->eq(1)->attr('href');
                $chapterTitle = $crawler->filter('.list li a')->eq(1)->text();
                
                $this->client = new Client();
                $crawler = $this->client->request('GET', $url);
                $textContent = $crawler->filter('.c-content')->text();
                DB::table("chapters")->insert(["book_id" => $book->book_id , "chapter_title" => $chapterTitle , "chapter_content" => str_replace($problematicChar, $replacement, $textContent)]);
            }
        }    
    }


}
