@extends('admin.app')
@section('summernote')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('phrases.Добавить новой отзыв')</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('reviews-adm.index') }}"><i class="bx bx-left-arrow-alt icon"></i>
                @lang('phrases.Назад')</a>
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

    <form action="{{ route('reviews-adm.store') }}" method="POST" data-fetch="none" enctype="multipart/form-data">
        @csrf
        <div class="block-background basement-form">
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('phrases.Имя'):</div>
                    <input type="text" placeholder="@lang('phrases.От кого отзыв')" name="name" class="input-text">
                </div>
                <div class="field">
                    <div class="title-field">@lang('phrases.Аватар') <span>(@lang('phrases.иконка пользователя'))</span>:</div>
                    <input type="file" name="avatar" class="input-text">
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('phrases.Ссылка на источник'):</div>
                    <input type="text" placeholder="@lang('phrases.Ссылка')" name="link" class="input-text">
                </div>
                <div class="field">
                    <div class="title-field">@lang('phrases.Имя ссылки'):</div>
                    <input type="text" placeholder="@lang('phrases.Имя ссылки')" name="linkName" class="input-text">
                </div>
            </div>
            <div class="row">
                <div class="field list">
                    <div class="title-field">@lang('phrases.Отзыв'):</div>
                    <textarea id="summernote" name="description" class="select-multiple"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('phrases.Имя')_en:</div>
                    <input type="text" placeholder="@lang('phrases.От кого отзыв')" name="name_en" class="input-text">
                </div>
            </div>
            <div class="row">
                <div class="field list">
                    <div class="title-field">@lang('phrases.Отзыв')_en:</div>
                    <textarea id="summernote2" name="description_en" class="select-multiple"></textarea>
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
            placeholder: '@lang('phrases.Текст отзыва')',
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
            placeholder: '@lang('phrases.Текст отзыва')',
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
