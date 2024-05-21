@extends('proxies::admin.app')
@section('summernote')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Правила сайта')</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('rules.index') }}"><i class="bx bx-left-arrow-alt icon"></i> @lang('proxies::phrases.Назад')</a>
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

    <form action="{{ route('rules.store') }}" method="POST" data-fetch="none">
        @csrf
        <div class="block-background basement-form">
            <div class="row">
                <div class="field list">
                    <div class="title-field">@lang('proxies::phrases.Текст'):</div>
                    {!! Form::textarea('body', null, [
                        'class' => 'select-multiple',
                        'id' => 'summernote',
                        'name' => 'text',
                    ]) !!}
                </div>
            </div>
        </div>
        <div class="block-background basement-form">
            <div class="row">
                <div class="field list">
                    <div class="title-field">@lang('proxies::phrases.Текст')_en:</div>
                    {!! Form::textarea('body', null, [
                        'class' => 'select-multiple',
                        'id' => 'summernote2',
                        'name' => 'text_en',
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
            placeholder: '@lang('proxies::phrases.Правила сайта')',
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
            placeholder: '@lang('proxies::phrases.Правила сайта')',
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
