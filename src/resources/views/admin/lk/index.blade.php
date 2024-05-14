@extends('templates.' . (new App\Http\Controllers\TemplateController())->getUserTemplateDirectory() . '.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/lk.css') }}{{ '?' . time() }}">
@endsection
@section('body-class')
personal-area
@endsection 

@section('content')
    <div class="lk-block">
        @include('admin.lk.menu')
        <div class="lk-content">
            <div class="lk1">
                <div class="wrap">
                    {{-- <form action="" class="form"> --}}
                    {!! Form::open(['method' => 'POST', 'route' => 'saveUserControl', 'class' => 'form']) !!}
                    <h3>@lang('proxies::phrases.Основные данные')</h3>
                    <div class="wrap-form">
                        <label for="name">
                            @lang('proxies::phrases.Привязка Telegram Уведомлений')
                            <span style="font-size: 11px;">@lang('proxies::phrases.для получения id chata перейдите к боту') <a
                                    style="font-size: 11px;text-decoration: underline;" href="{{ $tgData->telegram_link }}"
                                    target="_blanck">{{ $tgData->telegram_link }}</a> @lang('proxies::phrases.и напишите /start')</span>
                            <input type="text" name="telegram_chat_id" id="telegram" placeholder="00000000"
                                class="input-lk" value="{{ Auth::user()->telegram_chat_id }}" />
                        </label>
                        @if (Str::startsWith(Auth::user()->email, '@'))
                        @else
                            <label for="email">
                                Email
                                <input type="email" name="email" id="email" placeholder="Email" class="input-lk"
                                    value="{{ Auth::user()->email }}" />
                            </label>
                        @endif
                        <label for="name">
                            @lang('proxies::phrases.Логин')
                            <input type="text" name="name" id="name" placeholder="@lang('proxies::phrases.Логин')" class="input-lk"
                                value="{{ Auth::user()->name }}" />
                        </label>

                        <label for="password">
                            @lang('proxies::phrases.Изменить пароль')
                            <input type="password" name="password" id="password" placeholder="@lang('proxies::phrases.Новый пароль')"
                                class="input-lk" />
                        </label>
                        <label for="">
                            <input type="password" name="confirm-password" placeholder="@lang('proxies::phrases.Повторите новый пароль')"
                                class="input-lk" />
                        </label>
                    </div>
                    <div class="wrap-btn">
                        <button class="btn button">@lang('proxies::phrases.Сохранить')</button>
                    </div>
                    {!! Form::close() !!}
                    <div class="form">
                        <h3>@lang('proxies::phrases.История движения средств')</h3>
                        <ul>
                            @foreach (Auth::user()->historyOperation as $operation)
                                @if ($operation->type != 'buySub')
                                    <li>
                                        <div class="date">
                                            {{ \Carbon\Carbon::parse($operation->created_at, 'GMT')->format('m.d.Y') }}
                                            в {{ \Carbon\Carbon::parse($operation->created_at, 'GMT')->format('h:s') }}
                                        </div>
                                        <div class="type {{ $operation->type == 'minus' ? 'minus' : '' }}">
                                            <img
                                                src="/assets/img/{{ $operation->type == 'plus' ? 'iconoir_plus.svg' : ($operation->type == 'minus' ? 'minus.svg' : ($operation->type == 'buySub' ? 'iconoir_plus.svg' : '')) }}" />{{ $operation->amount }}
                                            $
                                        </div>
                                    </li>
                                @elseif($operation->status != null)
                                    <li>
                                        <div class="date">
                                            {{ \Carbon\Carbon::parse($operation->created_at, 'GMT')->format('m.d.Y') }}
                                            в {{ \Carbon\Carbon::parse($operation->created_at, 'GMT')->format('h:s') }}
                                        </div>
                                        <div class="type {{ $operation->type == 'minus' ? 'minus' : '' }}">
                                            <img
                                                src="/assets/img/{{ $operation->type == 'plus' ? 'iconoir_plus.svg' : ($operation->type == 'minus' ? 'minus.svg' : ($operation->type == 'buySub' ? 'iconoir_plus.svg' : '')) }}" />{{ $operation->amount }}
                                            $
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="indent"></div>
@endsection
@section('modal')
    <div class="modal notifications extend">
        <div class="background">
            <div class="body">
                <div class="textWrap">
                    <i class="fa fa-check-circle"></i>
                    <i class="fa fa-exclamation-triangle"></i>
                    <div class="title"></div>
                    <div class="massage"></div>
                </div>
                <div></div>
                <div class="buttonFormWrap">
                    <a class="main_btn closeModal dopButt" href="#">
                        @lang('proxies::phrases.Обратится в тех поддержку')
                    </a>
                    <a class="main_btn close closeModal" href="#">
                        @lang('proxies::phrases.Окей')
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('.submit-balance').click(function() {
            $('.balance-wrap').css('display', 'none');
            $('.balance-form').css('display', 'flex');
        });
        document.querySelector('form.balance-form').onchange = function(e) {
            if (e.target.name == "gateway" && e.target.id == "qiwi") {
                // document.querySelector('form.balance-form').action = e.target.value;
                // document.querySelector('form.balance-form').method = 'GET';
                // document.querySelector('input[name="amount"]').value = document.querySelector('input[name="balance"]').value
            }
            if (e.target.name == "balance") {
                // document.querySelector('input[name="amount"]').value = document.querySelector('input[name="balance"]').value
            }
        }
    </script>
@endsection
