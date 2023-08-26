@extends('user.app')
@section('title', 'Book')

@section('content')
    <style>
        .active {
            color: red !important;
        }
    </style>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-5">
            <div class="project-info-box mt-0">
                <h5>{{$book->title}} </h5>
                <p class="mb-0">{{$book->description}}</p>
            </div><!-- / project-info-box -->

            <div class="project-info-box">
                <p><b>Thể loại:</b> 
                    @foreach ($genresName as $name)
                    <a href="{{url('genre', ['id' => $name->genre_id])}}">
                        {{$name->genre_name}}, 
                    </a>
                    @endforeach
                </p>
                <p><b>Ngày:</b> {{$book->updated_at}}</p>
                <p><b>Tác giả:</b> {{$author->author_name}}</p>
               
                <h3>Mục lục sách</h3>
                    <div>
                        @foreach ($chapters as $chapter)
                            <a href="{{url('chapter', ['id' => $chapter->chapter_id])}}" class="text text-info">{{$chapter->chapter_title}}: {{$chapter->chapter_content}}</a>  
                            <br> 
                        @endforeach
                    </div>
                
            </div><!-- / project-info-box -->

           <!-- / project-info-box -->
        </div><!-- / column -->

        <div class="col-md-7 text-center">
            <img src="{{$book->cover_image}}" alt="project-image" class="rounded" style="width: 50%">
            <div class="project-info-box">
                <p><b>Danh mục:</b> 
                    @foreach ($categoriesName as $name)
                    <a href="{{url('category', ['id' => $name->category_id])}}">
                        {{$name->category_name}},
                    </a>
                    @endforeach    
                   
                    <div class="project-info-box mt-0 mb-0">
                        <p class="mb-0">
                            <div style="                                    font-size: 24px;
                                    color: #ccc; /* Inactive color */
                                " class="favorite-heart{{ $book->isFavoritedByUser(auth()->user()) ? ' active' : '' }}">
                                <i style="font-size: 30px; cursor: pointer;" class="fas fa-heart"></i>
                            </div>                    
                        </p>
                    </div>
                 
                </p>
            </div><!-- / project-info-box -->
        </div><!-- / column -->
    </div>

    <div class="mt-5">
        <h2 class="text-center">Các truyện liên quan</h2>

        <div class="row mt-4">
            @foreach ($relatedBooks as $item)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="{{url('book', ['id' => $item->book_id])}}" class="link-book">
                <div id="product-1" class="single-product" >
                    <div class="part-1 img-hover-zoom">
                        <img src="{{$item->cover_image}}" alt="" style="width: 100%">
                    </div>
                    <div class="part-2">
                        <h3 class="product-title text">{{$item->title}}</h3>
                    </div>
                </div>
            </a>
            </div>
            @endforeach
        </div>
        
    </div>

      
</div>


<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>

    // Assuming you have a heart icon with the class "favorite-heart"
const favoriteHeart = document.querySelector('.favorite-heart');
const favoriteHeartIcon =document.querySelector('.favorite-heart i');
favoriteHeartIcon.addEventListener('click', function() {
    const bookId = {{$book->book_id}};

    try {
        axios.post(`/favorite/${bookId}`)
        .then(response => {
            // Update the UI or show a success message
            if (response.data.message === 'Book favorited') {
                favoriteHeart.classList.add('active');
            } else {
                favoriteHeart.classList.remove('active');
            }
        })
        .catch(error => {
            // Handle errors
            console.error(error);
        });
    } catch (err) {
        console.log(err)
    }
    
});

</script>
@endsection