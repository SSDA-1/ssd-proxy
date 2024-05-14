@extends('admin.app')

@section('summernote')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Редактирование вопроса'): {{ $faq_adm->question }}</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('faq-adm.index') }}"><i class="bx bx-left-arrow-alt icon"></i> @lang('proxies::phrases.Назад')</a>
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

    <form action="{{ route('faq-adm.update', $faq_adm->id) }}" method="POST" data-fetch="none">
        @csrf
        @method('PUT')
        <div class="block-background basement-form">
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Вопрос'):</div>
                    <input type="text" name="question" value="{{ $faq_adm->question }}" class="input-text"
                        placeholder="@lang('proxies::phrases.Вопрос')">
                </div>
            </div>
            <div class="row">
                <div class="field list">
                    <div class="title-field">@lang('proxies::phrases.Ответ'):</div>
                    {!! Form::textarea('body', $faq_adm->answer, [
                        'class' => 'select-multiple',
                        'id' => 'summernote',
                        'name' => 'answer',
                    ]) !!}
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Вопрос')_en:</div>
                    <input type="text" value="{{ $faq_adm->question_en }}" name="question_en" class="input-text">
                </div>
            </div>
            <div class="row">
                <div class="field list">
                    <div class="title-field">@lang('proxies::phrases.Ответ')_en:</div>
                    {!! Form::textarea('body', $faq_adm->answer_en, [
                        'class' => 'select-multiple',
                        'id' => 'summernote2',
                        'name' => 'answer_en',
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
            value: '{{ $faq_adm->answer }}',
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
            value: '{{ $faq_adm->answer }}',
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
