@extends('admin.app')
@section('content')
    <div class="header-page">
        <div class="page-title">
            <div class="pull-left">
                <h2>@lang('phrases.Создание нового шаблона')</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('template-management') }}"> @lang('phrases.Назад')</a>
            </div>
        </div>
    </div>


    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>@lang('phrases.Ой')! </strong>@lang('phrases.С вашим вводом возникли некоторые проблемы').<br><br>
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
                            <h4>@lang('phrases.Название')</h4>
                        </div>
                        <div class="just-wrapper">
                            <input type="text" placeholder="@lang('phrases.Название')" name="name" id="name">
                        </div>
                    </div>
                    <div class="left-block">
                        <div class="pull-left">
                            <h4>@lang('phrases.Тип')</h4>
                        </div>
                        <div class="just-wrapper">
                            <input type="text" placeholder="Vip or Basic" name="type">
                        </div>
                    </div>
                    <div class="left-block">
                        <div class="pull-left">
                            <h4>@lang('phrases.Описание')</h4>
                        </div>
                        <div class="just-wrapper">
                            <input type="text" placeholder="Some text" name="description">
                        </div>
                    </div>
                    <div class="left-block">
                        <div class="pull-left">
                            <h4>@lang('phrases.Директорий')</h4>
                        </div>
                        <div class="just-wrapper">
                            <input type="text" placeholder="@lang('phrases.Ссылка')" name="directory">
                        </div>
                    </div>
                    <div class="left-block">
                        <div class="pull-left">
                            <h4>@lang('phrases.Цена')</h4>
                        </div>
                        <div class="just-wrapper">
                            <input type="text" placeholder="Cost" name="cost">
                        </div>
                    </div>
                </div>
            </div>
            <div class="button-projects">
                <button type="submit" class="btn btn-success">@lang('phrases.Добавить')</button>
            </div>
        </div>
    </form>
@endsection
