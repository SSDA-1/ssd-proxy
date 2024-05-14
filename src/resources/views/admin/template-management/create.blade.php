@extends('admin.app')
@section('content')
    <div class="header-page">
        <div class="page-title">
            <div class="pull-left">
                <h2>@lang('proxies::phrases.Создание нового шаблона')</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('template-management') }}"> @lang('proxies::phrases.Назад')</a>
            </div>
        </div>
    </div>


    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>@lang('proxies::phrases.Ой')! </strong>@lang('proxies::phrases.С вашим вводом возникли некоторые проблемы').<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('store-template') }}" method="POST" data-fetch="none">
        @csrf


        <div class="project-edit">
            <div class="content-block who-is-entrusted">
                <div class="block-con">
                    <div class="left-block">
                        <div class="pull-left">
                            <h4>@lang('proxies::phrases.Название')</h4>
                        </div>
                        <div class="just-wrapper">
                            <input type="text" placeholder="@lang('proxies::phrases.Название')" name="name" id="name">
                        </div>
                    </div>
                    <div class="left-block">
                        <div class="pull-left">
                            <h4>@lang('proxies::phrases.Тип')</h4>
                        </div>
                        <div class="just-wrapper">
                            <input type="text" placeholder="Vip or Basic" name="type">
                        </div>
                    </div>
                    <div class="left-block">
                        <div class="pull-left">
                            <h4>@lang('proxies::phrases.Описание')</h4>
                        </div>
                        <div class="just-wrapper">
                            <input type="text" placeholder="Some text" name="description">
                        </div>
                    </div>
                    <div class="left-block">
                        <div class="pull-left">
                            <h4>@lang('proxies::phrases.Директорий')</h4>
                        </div>
                        <div class="just-wrapper">
                            <input type="text" placeholder="@lang('proxies::phrases.Ссылка')" name="directory">
                        </div>
                    </div>
                    <div class="left-block">
                        <div class="pull-left">
                            <h4>@lang('proxies::phrases.Цена')</h4>
                        </div>
                        <div class="just-wrapper">
                            <input type="text" placeholder="Cost" name="cost">
                        </div>
                    </div>
                </div>
            </div>
            <div class="button-projects">
                <button type="submit" class="btn btn-success">@lang('proxies::phrases.Добавить')</button>
            </div>
        </div>
    </form>
@endsection
