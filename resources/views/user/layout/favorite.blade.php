@extends('user.app')
@section('title', 'Favorite book')

@section('content')
    


<section class="section-products">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-md-8 col-lg-6">
                <div class="header">
                    <h2>{{$title}}</h2>
                </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Single Product -->
                    @foreach ($books as $book)
                    
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <a href="{{url('book', ['id' => $book->book_id])}}" class="link-book">
                            <div id="product-1" class="single-product" >
                                <div class="part-1 img-hover-zoom">
                                    <img src="{{$book->cover_image}}" alt="" style="width: 100%">
                                </div>
                                <div class="part-2">
                                    <h3 class="product-title text">{{$book->title}}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    
                    @endforeach
                  
                </div>


            </div>
        </section>

@endsection