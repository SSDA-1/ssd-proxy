@extends('templates.'. (new App\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.app')

@section('content')
<div class="wraper-reviews-block">
    <h1>Отзывы</h1>
    <div class="wraper-reviews-section">
        @foreach ($reviewss as $reviews)
        <div class="card-reviews">
            <div class="img-reviews">
                <img src="{{ $reviews->avatar }}" alt="">
            </div>
            <div class="content-reviews">
                <p>{!! $reviews->description !!}</p>
                <div class="signature">
                    <span>{{ $reviews->name }}</span>
                    <a href="{{ $reviews->link }}">{{ $reviews->linkName }}</a>
                </div>
            </div>
            {{-- <div class="date-author">
                <div class="date"><i class='fa fa-calendar'></i>{{ $reviews->created_at }}</div>
                <div class="author"><i class='fa fa-user'></i>{{ $reviews->author }}</div>
            </div> --}}
        </div>
        @endforeach
    </div>
</div>

{!! $reviewss->links() !!}
@endsection
