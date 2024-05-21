@extends('proxies::admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Редактирование партнера')</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('partners.index') }}"><i class="bx bx-left-arrow-alt icon"></i> @lang('proxies::phrases.Назад')</a>
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

    <form action="{{ route('partners.update', $partner->id) }}" method="POST" data-fetch="none" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="block-background basement-form">
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Название'):</div>
                    <input type="text" value="{{ $partner->name }}" name="name" class="input-text">
                </div>
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Логотип'):</div>
                    <input type="file" name="logo" class="input-text">
                </div>
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Скидка'):</div>
                    <input type="text" value="{{ $partner->discount }}" name="discount" class="input-text">
                </div>
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Промокод'):</div>
                    <input type="text" value="{{ $partner->promo }}" name="promo" class="input-text">
                </div>
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Ссылка'):</div>
                    <input type="text" value="{{ $partner->link }}" name="link" class="input-text">
                </div>
            </div>
        </div>
        <div class="footer-block">
            <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
        </div>
    </form>
@endsection

