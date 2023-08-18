@extends('user.app')
@section('title', 'Chapter')

@section('content')
    
    <div class="container mt-5">
        <div class="text-center">
            <h2>{{$book->title}}</h2>
            <h4>{{$chapter->chapter_title}}</h4>


            <h2>Chọn chapter</h2>
        <form action="{{ route('chapter.show') }}" method="post">
        @csrf
        <select name="chapter_id" style="width: 200px; height: 50px;">
            @foreach ($chaptersInBook as $cIB)
                <option {{$cIB->chapter_id === $chapter->chapter_id ? 'selected' : ''}} 
                    value="{{ $cIB->chapter_id }}">{{ $cIB->chapter_title }}</option>
            @endforeach
        </select>
            <button type="submit">View Chapter</button>
        </form>
           
           <p style="line-height: 2; font-size: 30px;" class="mt-3">
            {{$chapter->chapter_content}}
           </p>
        </div>
    </div>

@endsection