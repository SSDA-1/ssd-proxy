@extends('proxies::templates.'. (new ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.app')

@section('content')

<div class="wraper-reviews-block">
    <h1>{{ $blogNews->name }}</h1>
    <div class="wraper-reviews-section show-blog">
        <div class="img-reviews">
            <img src="{{$blogNews->images}}" alt="">
        </div>
        <div class="date-author">
            <div class="date"><i class='fa fa-calendar'></i>{{ $blogNews->created_at }}</div>
            <div class="author"><i class='fa fa-user'></i>{{ $blogNews->author }}</div>
        </div>
        <div class="content-reviews">
            <p>{!! $blogNews->detail !!}</p>
        </div>

    </div>
</div>

@endsection
