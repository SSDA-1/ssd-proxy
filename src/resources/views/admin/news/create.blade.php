@extends('admin.app')
@section('summernote')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('phrases.Создание новой новости')</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('news.index') }}"><i class="bx bx-left-arrow-alt icon"></i> @lang('phrases.Назад')</a>
        </div>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger block-background">
            <strong>@lang('phrases.Упс')!</strong> @lang('phrases.Были некоторые проблемы с вашим вводом').<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('news.store') }}" method="POST" data-fetch="none" enctype="multipart/form-data" id="my_form">
        @csrf
        <div class="block-background basement-form">
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('phrases.Название'):</div>
                    <input type="text" placeholder="@lang('phrases.Название')" name="name" class="input-text">
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('phrases.Категории'):</div>
                    <select name="category" id="category" class="select-multiple">
                        <option value="1">@lang('phrases.Новости')</option>
                        <option value="2">@lang('phrases.Полезное')</option>
                        <option value="3">@lang('phrases.О прокси')</option>
                    </select>
                </div>
                <div class="field">
                    <div class="title-field">@lang('phrases.Обложка') <span>(@lang('phrases.так же отобразится внутри новости'))</span>:</div>
                    <input type="file" name="images" id="filesizecheck" class="input-text">
                </div>
            </div>
            <div class="row">
                <div class="field list">
                    <div class="title-field">@lang('phrases.Текст'):</div>
                    <textarea id="summernote" name="detail" class="select-multiple"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('phrases.Название')_en:</div>
                    <input type="text" placeholder="@lang('phrases.Название')" name="name_en" class="input-text">
                </div>
            </div>
            <div class="row">
                <div class="field list">
                    <div class="title-field">@lang('phrases.Текст')_en:</div>
                    <textarea id="summernote2" name="detail_en" class="select-multiple"></textarea>
                </div>
            </div>
            <input type="text" name="author" value="{{ Auth::user()->name }}" hidden>
        </div>
        <div class="footer-block">
            <button type="submit" class="btn btn-primary">@lang('phrases.Сохранить')</button>
        </div>
    </form>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $('#summernote').summernote({
            placeholder: '@lang('phrases.Полное описание новости')',
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
            placeholder: '@lang('phrases.Полное описание новости')',
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
