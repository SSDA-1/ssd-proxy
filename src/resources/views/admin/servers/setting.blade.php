@extends('admin.app')
@section('content')
<div class="header-page">
    <div class="page-title">
        <div class="pull-left">
            <h2>@lang('phrases.Основные настройки прокси')</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('proxy.index') }}"><i class="bx bx-left-arrow-alt icon"></i> @lang('phrases.Назад')</a>
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

<div class="wrapFlexForm">
    <div class="itemFlexForm">
        <div class="titleFlexForm">@lang('phrases.Интеграция')</div>
        <form action="{{ route('integrationSave') }}" method="POST">
            @csrf
            <div class="project-edit">
                <div class="content-block who-is-entrusted">
                    <div class="block-con">
                        <div class="left-block">
                            <div class="pull-left">
                                <h4>@lang('phrases.Логин')</h4>
                            </div>
                            <div class="just-wrapper">
                                <input type="text" placeholder="@lang('phrases.Название')" name="login_kraken" value="{{ $settingModel->integration_login }}">
                            </div>
                        </div>
                        <div class="left-block noMargin">
                            <div class="pull-left">
                                <h4>@lang('phrases.Пароль')</h4>
                            </div>
                            <div class="just-wrapper">
                                @if ($settingModel->integration_password)
                                    <a id="changePass" style="cursor: pointer;display: block;padding: 10px 0;font-size: 18px;color: #557dfc;">@lang('phrases.Изменить')</a>
                                    <input type="password" placeholder="@lang('phrases.Пароль')" id="passKraken" name="pass_kraken" value="{{$settingModel->integration_password}}" style="display: none">
                                @else
                                <input type="password" placeholder="@lang('phrases.Пароль')" name="pass_kraken">
                                @endif
                                
                            </div>
                        </div>
                        <div class="right-block full">
                            <div class="pull-left">
                                <h4>@lang('phrases.Ссылка на Host')</h4>
                            </div>
                            <div class="just-wrapper">
                                <input type="text" placeholder="@lang('phrases.Ссылка')" name="ip_kraken" value="{{ $settingModel->integration_ip }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="buttonFormWrap">
                    <button type="submit" class="btn btn-success">@lang('phrases.Сохранить')</button>
                </div>
            </div>
        </form>
    </div>
    <div class="itemFlexForm">
        <div class="titleFlexForm">@lang('phrases.Основное')</div>
        <form action="{{ route('basicSave') }}" method="POST">
            @csrf
            <div class="project-edit">
                <div class="content-block who-is-entrusted">
                    <div class="block-con">
                        <div class="left-block full">
                            <div class="pull-left">
                                <h4>@lang('phrases.Цена (Общие прокси)')</h4>
                            </div>
                            <div class="just-wrapper numberFormat">
                                <span>₽</span><input type="number" placeholder="0" name="all_price" value="{{ $settingModel->proxy_all_price }}">
                            </div>
                        </div>
                        <div class="left-block full">
                            <div class="pull-left">
                                <h4>@lang('phrases.Цена (Приватные прокси)')</h4>
                            </div>
                            <div class="just-wrapper numberFormat">
                                <span>₽</span><input type="number" placeholder="0" name="privat_price" value="{{ $settingModel->proxy_privat_price }}">
                            </div>
                        </div>
                        <div class="right-block full">
                            <div class="pull-left">
                                <h4>@lang('phrases.Месяцев')</h4>
                            </div>
                            <div class="just-wrapper">
                                <input type="number" placeholder="0" name="mounth" value="{{ $settingModel->proxy_mounth }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="buttonFormWrap">
                    <button type="submit" class="btn btn-success">@lang('phrases.Сохранить')</button>
                </div>
            </div>
        </form>
    </div>
    <div class="itemFlexForm">
        <div class="titleFlexForm">@lang('phrases.Скидки')</div>
        <form action="{{ route('saleSave') }}" method="POST">
            @csrf
            <div class="project-edit">
                <div class="content-block who-is-entrusted">
                    <div class="block-con">
                        <div class="left-block full">
                            <div class="pull-left">
                                <h4>@lang('phrases.Скидка на кол-во проксей'):</h4>
                            </div>
                            <div class="just-wrapper">
                                <p>@lang('phrases.От 2х штук')</p>
                                <input type="text" placeholder="0" name="two_sel_count" value="{{ $settingModel->proxy_two_sel_count }}">
                                <p>@lang('phrases.От 5х штук')</p>
                                <input type="text" placeholder="0" name="three_sel_count" value="{{ $settingModel->proxy_three_sel_count }}">
                            </div>
                        </div>
                        <div class="left-block full">
                            <div class="pull-left">
                                <h4>@lang('phrases.Скидка на период'):</h4>
                            </div>
                            <div class="just-wrapper">
                                <p>@lang('phrases.От 2х месяцев')</p>
                                <input type="text" placeholder="0" name="two_sel_period" value="{{ $settingModel->proxy_two_sel_period }}">
                                <p>@lang('phrases.От 3х месяцев')</p>
                                <input type="text" placeholder="0" name="three_sel_period" value="{{ $settingModel->proxy_three_sel_period }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="buttonFormWrap">
                    <button type="submit" class="btn btn-success">@lang('phrases.Сохранить')</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelector('#changePass').addEventListener('click', function (e) {
            e.target.style.display = 'none'
            document.querySelector('#passKraken').style.display = 'block'
        });
    });
</script>
@endsection