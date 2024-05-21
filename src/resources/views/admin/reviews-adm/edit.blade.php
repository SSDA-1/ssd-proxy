@extends('proxies::admin.app')

@section('summernote')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Редактирование отзыва') {{ $reviews_adm->name }}</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('reviews-adm.index') }}"><i class="bx bx-left-arrow-alt icon"></i>
                @lang('proxies::phrases.Назад')</a>
        </div>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger block-background">
            <strong>@lang('proxies::phrases.Упс')!</strong> @lang('proxies::phrases.Были некоторые проблемы с вашим вводом').<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- <form action="{{ route(['reviews-adm.update',$reviews->id]) }}" method="POST" data-fetch="none"> --}}
    {!! Form::open([
        'route' => ['reviews-adm.update', $reviews_adm->id],
        'method' => 'PATCH',
        'data-fetch' => 'none',
        'enctype' => 'multipart/form-data',
    ]) !!}
    @csrf
    @method('PUT')
    <div class="grid-block basement-form">
        <div class="block-background basement-form">
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Имя'):</div>
                    <input type="text" name="name" value="{{ $reviews_adm->name }}" class="input-text"
                        placeholder="Имя">
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Ссылка на источник'):</div>
                    <input type="text" placeholder="@lang('proxies::phrases.Ссылка')" name="link" class="input-text"
                        value="{{ $reviews_adm->link }}">
                </div>
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Имя ссылки'):</div>
                    <input type="text" placeholder="@lang('proxies::phrases.Имя ссылки')" name="linkName" class="input-text"
                        value="{{ $reviews_adm->linkName }}">
                </div>
            </div>
            <div class="row">
                <div class="field list">
                    <div class="title-field">@lang('proxies::phrases.Отзыв'):</div>
                    {!! Form::textarea('body', $reviews_adm->description, [
                        'class' => 'select-multiple',
                        'id' => 'summernote',
                        'name' => 'description',
                    ]) !!}
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Имя')_en:</div>
                    <input type="text" name="name_en" value="{{ $reviews_adm->name_en }}" class="input-text"
                           placeholder="@lang('proxies::phrases.Имя')">
                </div>
            </div>
            <div class="row">
                <div class="field list">
                    <div class="title-field">@lang('proxies::phrases.Отзыв')_en:</div>
                    {!! Form::textarea('body', $reviews_adm->description_en, [
                        'class' => 'select-multiple',
                        'id' => 'summernote2',
                        'name' => 'description_en',
                    ]) !!}
                </div>
            </div>
            <input type="text" name="author" value="{{ Auth::user()->name }}" hidden>
        </div>
        <div class="block-background basement-form">
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Аватар') <span>(@lang('proxies::phrases.иконка пользователя'))</span>:</div>
                    @if ($reviews_adm->avatar)
                        <img src="{{ $reviews_adm->avatar }}" id="logoFile" class="cover">
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <input type="file" name="avatar" class="input-text">
                </div>
            </div>
        </div>
    </div>
    <div class="footer-block">
        <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
    </div>
    {!! Form::close() !!}

@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $('#summernote').summernote({
            value: '{{ $reviews_adm->description }}',
            tabsize: 2,
            height: 120,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('#summernote2').summernote({
            value: '{{ $reviews_adm->description }}',
            tabsize: 2,
            height: 120,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    </script>
@endsection
