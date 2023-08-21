@extends('user.app')
@section('title', 'Chapter')

@section('content')
    <style>
            #advertisement {
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: #f5f5f5;
    padding: 10px;
    text-align: center;
    display: block;
    transition: transfom 0.3s ease, bottom 0.3s ease; 
}

.active {
    bottom: -200px !important;
}
.hidden {
    display: none !important;
}
        </style>    

    <div class="container mt-5">
        <div class="text-center">
            <h2>{{$book->title}}</h2>
            <h4 class="mb-4">{{$chapter->chapter_title}}</h4>

            <h2>Chọn chương</h2>
        <form action="{{ route('chapter.show') }}" method="post">
        @csrf
        <select name="chapter_id" style="width: 200px; height: 50px;">
            @foreach ($chaptersInBook as $cIB)
                <option {{$cIB->chapter_id === $chapter->chapter_id ? 'selected' : ''}} 
                    value="{{ $cIB->chapter_id }}">{{ $cIB->chapter_title }}</option>
            @endforeach
        </select>
            <button type="submit">Nhấn để xem</button>
        </form>
           
           <p style="line-height: 2; font-size: 30px;" class="mt-3">
            {{$chapter->chapter_content}}
           </p>

           <h2>Chọn Chương</h2>
           <form action="{{ route('chapter.show') }}" method="post">
            @csrf
            <select name="chapter_id" style="width: 200px; height: 50px;">
                @foreach ($chaptersInBook as $cIB)
                    <option {{$cIB->chapter_id === $chapter->chapter_id ? 'selected' : ''}} 
                        value="{{ $cIB->chapter_id }}">{{ $cIB->chapter_title }}</option>
                @endforeach
            </select>
                <button type="submit">Nhấn để xem</button>
            </form>
        </div>
    </div>


    <div id="advertisement" style="border: 1px solid gray;">
        <div class="text-center">
            <div class="btn-ads" style="float: right;">
                <i id="icon" style="font-size: 40px; cursor: pointer;" class="fa fa-arrow-down"></i>
            </div>
            <div><h2>Loa kẹo kéo cực xịn</h2></div>
        </div>
        <!-- Advertisement content goes here -->
        <a target="blank" href="https://shopee.vn/Thi%E1%BA%BFt-B%E1%BB%8B-%C4%90i%E1%BB%87n-T%E1%BB%AD-cat.11036132">
            <p>Click to buy item</p>
            <img style="width: 400px; height: 150px;" src="https://down-vn.img.susercontent.com/file/d278b6c4fdaf00c707629fa6bb230709_tn" alt="">
        </a>
    
    </div>

    <script>
 

const sidebar = document.querySelector('#advertisement');
const toggleButton = document.querySelector('.btn-ads');
const icon = document.querySelector('#icon');
toggleButton.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    
    if(icon.classList.contains('fa-arrow-down')){
        icon.classList.remove('fa-arrow-down');
        icon.classList.add('fa-arrow-up');
        return;
    }
        icon.classList.remove('fa-arrow-up');
        icon.classList.add('fa-arrow-down');


});
    </script>
@endsection