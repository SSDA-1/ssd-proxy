@extends('admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Пункт меню'): {{ $menu->name }}</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('menu.index') }}"><i class="bx bx-left-arrow-alt icon"></i> @lang('proxies::phrases.Назад')</a>
        </div>
    </div>

    <div class="block-background">
        <div class="row">
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Название'):</div>
                <div class="input-text">{{ $menu->name }}</div>
            </div>
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Ссылка'):</div>
                <div class="input-text">{{ $menu->link }}</div>
            </div>
            <div class="field">
                <div class="title-field">@lang('proxies::phrases.Тип меню'):</div>
                <div class="input-text">
                    @if ($menu->type_menu == 1)
                        @lang('proxies::phrases.Верхнее и мобильное меню')
                    @elseif($menu->type_menu == 2)
                        @lang('proxies::phrases.Подвал сайта')
                    @elseif($menu->type_menu == 3)
                        @lang('proxies::phrases.Социальные сети')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
