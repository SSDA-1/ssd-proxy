@extends('proxies::templates.'. (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.app')

@section('content')
<div class="wraper-reviews-block">
    <h1>Статьи</h1>
    <div class="wraper-reviews-section">
        @foreach ($newss as $news)
        <div class="card-reviews blog">
            <div class="img-reviews">
                <img src="{{$news->images}}" alt="">
            </div>
            <div class="date-author">
                <div class="date"><i class='fa fa-calendar'></i>{{ $news->created_at }}</div>
                <div class="author"><i class='fa fa-user'></i>{{ $news->author }}</div>
            </div>
            <div class="title-blog">
                <h3>{{ $news->name }}</h3>
            </div>
            <div class="content-reviews">
                <p>{!! $news->detail !!}</p>
                <div class="signature">
                    <a href="{{route('blogShow',$news->id)}}">Читать дальше <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{!! $newss->links() !!}
@endsection
