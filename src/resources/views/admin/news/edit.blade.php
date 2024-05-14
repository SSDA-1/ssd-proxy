@extends('admin.app')

@section('summernote')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Редактирование новости') {{ $news->name }}</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('news.index') }}"><i class="bx bx-left-arrow-alt icon"></i> @lang('proxies::phrases.Назад')</a>
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

    <form action="{{ route('news.update', $news->id) }}" method="POST" data-fetch="none" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="block-background basement-form">
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Название'):</div>
                    <input type="text" name="name" value="{{ $news->name }}" class="input-text" placeholder="@lang('proxies::phrases.Имя')">
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Категории'):</div>
                    <select name="category" id="category" value="{{ $news->category }}" class="select-multiple">
                        <option value="1">@lang('proxies::phrases.Новости')</option>
                        <option value="2">@lang('proxies::phrases.Полезное')</option>
                        <option value="3">@lang('proxies::phrases.О прокси')</option>
                    </select>
                </div>
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Обложка') <span>(@lang('proxies::phrases.так же отобразится внутри новости'))</span>:</div>
                    <input type="file" name="images" value="{{ $news->images }}" class="input-text">
                </div>
            </div>
            <div class="row">
                <div class="field list">
                    <div class="title-field">@lang('proxies::phrases.Текст'):</div>
                    {!! Form::textarea('body', $news->detail, [
                        'class' => 'select-multiple',
                        'id' => 'summernote',
                        'name' => 'detail',
                    ]) !!}
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Название')_en:</div>
                    <input type="text" name="name_en" value="{{ $news->name_en }}" class="input-text" placeholder="@lang('proxies::phrases.Имя')">
                </div>
            </div>
            <div class="row">
                <div class="field list">
                    <div class="title-field">@lang('proxies::phrases.Текст')_en:</div>
                    {!! Form::textarea('body', $news->detail_en, [
                        'class' => 'select-multiple',
                        'id' => 'summernote2',
                        'name' => 'detail_en',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="footer-block">
            <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
        </div>
    </form>

@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $('#summernote').summernote({
            value: '{{ $news->detail }}',
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
            value: '{{ $news->detail_en }}',
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
