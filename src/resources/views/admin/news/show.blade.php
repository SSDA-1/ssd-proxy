@extends('proxies::admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Название новости'): {{ $news->name }}</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('news.index') }}"><i class="bx bx-left-arrow-alt icon"></i> @lang('proxies::phrases.Назад')</a>
        </div>
    </div>

    <div class="grid-block">
        <div class="block-background">
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Название'):</div>
                    <div class="input-text">{{ $news->name }}</div>
                </div>
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Категория'):</div>
                    <div class="input-text">
                        @if ($news->category == 1)
                            @lang('proxies::phrases.Новости')
                        @elseif($news->category == 2)
                            @lang('proxies::phrases.Полезное')
                        @elseif($news->category == 3)
                            @lang('proxies::phrases.О прокси')
                        @endif
                    </div>
                </div>

                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Автор'):</div>
                    <div class="input-text">{{ $news->author }}</div>
                </div>
            </div>
            <div class="row full" style="max-height: calc(100% - 95px);">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Текст'):</div>
                    <div class="input-text full">{!! $news->detail !!}</div>
                </div>
            </div>

        </div>
        <div class="block-background">
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Обложка') <span>(@lang('proxies::phrases.так же отобразится внутри новости'))</span>:</div>
                    <div class="input-text"><img src="{{ $news->images }}" class="cover"></div>
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Дата публикации'):</div>
                    <div class="input-text">{{ $news->created_at }}</div>
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Дата обновления'):</div>
                    <div class="input-text">{{ $news->updated_at }}</div>
                </div>
            </div>
        </div>
    </div>


    {{-- <div class="project-show">

    <div class="content-block who-is-entrusted">

        <div class="pull-left">
            <h4>Название</h4>
        </div>
        <div class="just-wrapper">
            <div class="indoor-unit">{{ $news->name }}</div>
        </div>

        <div class="block-con">
            <div class="left-block">
                <div class="pull-left">
                    <h4>Категория</h4>
                </div>
                <div class="indoor-unit">
                    @if ($news->category == 1)
                    Новости
                    @elseif($news->category == 2)
                    Полезное
                    @elseif($news->category == 3)
                    О прокси
                    @endif
                </div>
            </div>
            <div class="right-block">
                <div class="pull-left">
                    <h4>Картинка</h4>
                </div>
                <div class="indoor-unit">{{ $news->images }}</div>
            </div>
        </div>
        <div class="block-con">
            <div class="left-block">
                <div class="pull-left">
                    <h4>Автор</h4>
                </div>
                <div class="indoor-unit">{{ $news->author }}</div>
            </div>
            <div class="left-block">
                <div class="pull-left">
                    <h4>Дата публикации</h4>
                </div>
                <div class="indoor-unit">{{ $news->created_at }}</div>
            </div>
            <div class="right-block">
                <div class="pull-left">
                    <h4>Дата обновления</h4>
                </div>
                <div class="indoor-unit">{{ $news->updated_at }}</div>
            </div>
        </div>

        <div class="pull-left">
            <h4>Текст</h4>
        </div>

        <div class="indoor-unit">
            {!! $news->detail !!}
        </div>
    </div>
</div> --}}
@endsection
