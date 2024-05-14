@extends('admin.app')
@section('summernote')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Создание нового преимущества')</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('advantag.index') }}"><i class="bx bx-left-arrow-alt icon"></i> @lang('proxies::phrases.Назад')</a>
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

    <form action="{{ route('advantag.store') }}" method="POST" data-fetch="none" enctype="multipart/form-data">
        @csrf
        <div class="block-background basement-form">
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Название'):</div>
                    <input type="text" placeholder="@lang('proxies::phrases.Название')" name="title" class="input-text">
                    <input type="text" placeholder="@lang('proxies::phrases.Порядок вывода')" name="position" value="1" hidden
                        class="input-text">
                </div>
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Иконка'):</div>
                    <input type="file" name="image" class="input-text">
                </div>
            </div>
            <div class="row">
                <div class="field list">
                    <div class="title-field">@lang('proxies::phrases.Текст'):</div>
                    <textarea id="summernote" name="description" class="select-multiple"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Название')_en:</div>
                    <input type="text" placeholder="@lang('proxies::phrases.Название')_en" name="title_en" class="input-text">
                </div>
            </div>
            <div class="row">
                <div class="field list">
                    <div class="title-field">@lang('proxies::phrases.Текст')_en:</div>
                    <textarea id="summernote2" name="description_en" class="select-multiple"></textarea>
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
            placeholder: '@lang('proxies::phrases.Полное описание')',
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
            placeholder: '@lang('proxies::phrases.Полное описание')',
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
        })
    </script>
@endsection
