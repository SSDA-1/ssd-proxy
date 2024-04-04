@extends('templates.'. (new App\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/lk.css') }}{{ '?' . time() }}">
@endsection
@section('body-class')
personal-area
@endsection

@section('content')
    <style>
        .wrap-support {
            margin: 50px 0 50px;
            display: flex;
            width: var(--width-1200);
        }

        .wrap-title {
            width: var(--width-1200);
            display: flex;
            /* margin-top: 50px; */
        }

        .wrap-support .bodyDialog {
            height: calc(100vh - 286px);
            width: 70%;
            border: 2px solid #3a4763;
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            max-height: 600px;
        }

        .wrap-support .bodyDialog .topLine {
            padding: 15px;
            border-bottom: 1px solid #3a4763;
            flex-direction: row;
            color: #3a4763;
            justify-content: space-between;
            display: flex;
        }

        .wrap-support .bodyDialog .midleLine {
            flex: 1 auto;
            overflow: auto;
        }

        .wrap-support .bodyDialog .midleLine .bubble {
            padding: 10px;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .wrap-support .bodyDialog .midleLine .bubble:after {
            position: absolute;
            content: ' ';
            top: 38px;
            width: 0;
            height: 0;
        }

        .wrap-support .bodyDialog .midleLine .bubble .time {
            font-size: 14px;
        }

        .wrap-support .bodyDialog .midleLine .bubble .name {
            padding-right: 10px;
            padding-bottom: 3px;
            color: #3546be;
            font-weight: 600;
        }

        .wrap-support .bodyDialog .midleLine .bubble .massageBody {
            max-width: 50%;
            border-radius: 5px;
            margin-bottom: 5px;
            word-break: break-all;
            position: relative;
        }

        .wrap-support .bodyDialog .midleLine .bubble.thisUser .time {
            text-align: end;
        }

        .wrap-support .bodyDialog .midleLine .bubble.thisUser {
            align-items: flex-end;
        }

        .wrap-support .bodyDialog .midleLine .bubble.thisUser:after {
            right: 3px;
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
            border-left: 7px solid #3546bebd;
        }

        .wrap-support .bodyDialog .midleLine .bubble.appUser {
            align-items: flex-start;
        }

        .wrap-support .bodyDialog .midleLine .bubble.appUser .name {
            padding-left: 10px;
            padding-right: 0px;
        }

        .wrap-support .bodyDialog .midleLine .bubble.appUser:after {
            left: 3px;
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
            border-right: 7px solid #f7f7f7;
        }

        .wrap-support .bodyDialog .midleLine .bubble.thisUser .massageBody {
            background-color: #3546bebd;
            padding: 10px 10px 10px 20px;
            color: #fff;
        }

        .wrap-support .bodyDialog .midleLine .bubble.appUser .massageBody {
            background-color: #f7f7f7;
            padding: 10px 20px 10px 10px;
        }

        .wrap-support .bodyDialog .bottomLine {
            border-top: 1px solid #3a4763;
            padding: 10px;
            display: flex;
            justify-content: space-between;
        }

        .wrap-support .bodyDialog .bottomLine .send {
            border: none;
            width: 24px;
            height: 24px;
            background: url(/assets/img/send.png) no-repeat center center;
            background-size: contain;
            cursor: pointer;
            margin-left: 10px;
        }

        .wrap-support .bodyDialog .bottomLine div[contenteditable="true"] {
            outline: none;
            width: 100%;
        }

        .wrap-support .bodyDialog .bottomLine.close {
            justify-content: center;
            color: #b3b2b2;
            display: none;
        }

        .wrap-support .bodyDialog .bottomLine .sendTextarea {
            display: none;
        }

        .wrap-support .pastAppeals {
            padding-right: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 30%;
        }

        .wrap-support .pastAppeals h2 {margin-bottom:10px; font-size: 26px;}
        .wrap-support .pastAppeals .cardAppeals {
            padding: 10px 20px;
            padding-bottom: 5px;
            padding-left: 10px;
            background-color: #f7f7f7a8;
            margin-bottom: 5px;
            cursor: pointer;
            border-radius: 5px;
        }

        .wrap-support .pastAppeals .cardAppeals.action {
            background-color: #ebebf9;
            border: 1px solid #3546be;
        }

        .wrap-support .pastAppeals .cardAppeals h3 {
            display: flex;
            justify-content: space-between;
            padding-bottom: 5px;
            margin-bottom: 10px;
            align-items: flex-end;
            border-bottom: 1px solid #bfbfbf;
        }

        .wrap-support .pastAppeals .cardAppeals h3 span {
            font-size: 14px;
        }

        .wrap-support .pastAppeals .warp-Appeals {
            max-height: 530px;
            width: 100%;
            overflow: auto;
            margin-bottom: 5px;
            padding-right: 10px;
        }
        .wrap-support .btn:hover {background-color: #DA5583; color: #fff;}
        #element::-webkit-scrollbar, #messBody::-webkit-scrollbar {
            width: 5px;
            background-color: #f9f9fd;
        }

        #element::-webkit-scrollbar-thumb, #messBody::-webkit-scrollbar-thumb {
            border-radius: 10px;
            background-color: #3546be
        }

        #element::-webkit-scrollbar-track, #messBody::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            background-color: #f9f9fd;
        }
        @media (max-width: 450px) {
        .wrap-support {
        flex-direction: column;
        gap: 40px;
        }
        .wrap-support .pastAppeals {
        width: 100%;
        min-height: 300px;
        padding: 0;
        }
        .wrap-support .pastAppeals .warp-Appeals {
        padding-right: 0;
        max-height: 250px;
        }
        .wrap-support .bodyDialog {
        width: 100%;
        }}
    </style>

<div class="lk-block">
    @include('admin.lk.menu')
    <div class="lk-content">
        <div class="wrap-title">
            <h1>@lang('phrases.Техническая поддержка')</h1>
        </div>
        <div class="wrap-support">
            <div class="pastAppeals">
                <div class="warp-Appeals" id="element">
                    <h2>@lang('phrases.Прошлые обращения')</h2>
                    @foreach ($supportsList as $item)
                        <div class="cardAppeals {{$id == $item->id ? 'action' : ''}}">
                            <a href="/support/{{$item->id}}" style="text-decoration: unset;"><h3>@lang('phrases.Обращение')
                                    #{{$item->id}} @if ($item->status == 0)
                                        <span style="color:green;">@lang('phrases.Открыт')</span>
                                    @else
                                        <span style="color:red;">@lang('phrases.Закрыт')</span>
                                    @endif
                                    <span>{{$item->lastsuppmassage ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->lastsuppmassage->updated_at)->format('d.m.Y') : '' }}</span>
                                </h3>
                                <div class="text">{{$item->lastsuppmassage ? $item->lastsuppmassage->massage : '' }}</div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="main_btn btn" id="dialog_btn">@lang('phrases.Новое обращение')</div>
            </div>
            <div class="bodyDialog" id="bodyDialog">
                {{--{{ dd($support) }}--}}

                @if($support)
                    <div class="topLine">@lang('phrases.Обращение') #{{$id}} @if ($support->status == 0)
                            <span style="color:green;">@lang('phrases.Открыт')</span>
                        @else
                            <span style="color:red;">@lang('phrases.Закрыт')</span>
                        @endif</div>
                    <div class="midleLine" id="messBody">
                        @foreach ($support->AllSupportMassage as $message)
                            @if (!$message->admin)
                                <div class="bubble thisUser">
                                    <div class="name">Я</div>
                                    <div class="massageBody">{{$message->massage}}</div>
                                    <div
                                        class="time">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $support->created_at)->format('d M Y в H:i')}}</div>
                                </div>
                            @else
                                <div class="bubble appUser">
                                    <div class="name">Administrator</div>
                                    <div class="massageBody">{{$message->massage}}</div>
                                    <div
                                        class="time">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $support->created_at)->format('d M Y в H:i')}}</div>
                                </div>
                            @endif
                        @endforeach
                        {{-- <div class="bubble thisUser">
                            <div class="name">Я</div>
                            <div class="massageBody">Пишу вам потому что мне скучно, поговорите со мной, пожалуйста!</div>
                            <div class="time">21 июня 2022</div>
                        </div>
                        <div class="bubble appUser">
                            <div class="name">Administrator</div>
                            <div class="massageBody">Пишите в телегу @fsbRF</div>
                            <div class="time">21 июня 2022</div>
                        </div> --}}
                    </div>
                    {!! Form::open(array('route' => 'sendSupportMass')) !!}
                    <div class="bottomLine">
                        <div @if ($support->status == 0) contenteditable="true" @endif class="sendContent"></div>
                        @if ($support->status == 0)
                            <textarea name="text" class="sendTextarea" cols="30" rows="10"></textarea>
                        @endif
                        {!! Form::hidden('id', $id, []) !!}
                        <button class="send"></button>
                    </div>
                    {!! Form::close() !!}
                    <div class="bottomLine close">
                        @lang('phrases.Обращение закрыто')
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
    <script>
        let contenteditableDiv = document.querySelector('.sendContent'),
            textAreaElement = document.querySelector('.sendTextarea');
        contenteditableDiv.addEventListener("input", function () {
            textAreaElement.value = contenteditableDiv.innerText;
        });
    </script>
    <script src="//comet-server.ru/CometServerApi.js" type="text/javascript"></script>
    <script type="text/javascript">
        function updateScroll() {
            var element = document.getElementById("messBody");
            element.scrollTop = element.scrollHeight;
        }

        updateScroll()
        /**
         * Подписываемся на получение сообщения из канала Pipe_name
         */
        CometServer().subscription("web_{{ $id }}", function (event) {
            // console.log("Мы получили ставку из канала ", event.data.text, event);
            let data = event.data.replace(/\'/g, "\"");
            // let json = JSON.stringify(data);
            let parsed = JSON.parse(data);
            console.log(parsed)
            if (parsed.admin == 'yes') {
                $('#messBody').append('<div class="bubble appUser">\
                        <div class="name">Administrator</div>\
                        <div class="massageBody">' + parsed.text + '</div>\
                        <div class="time">' + parsed.date + '</div>\
                    </div>')
                updateScroll()
            } else {
                $('#messBody').append('<div class="bubble thisUser">\
                            <div class="name">Я</div>\
                            <div class="massageBody">' + parsed.text + '</div>\
                            <div class="time">' + parsed.date + '</div>\
                        </div>')
                updateScroll()
            }
        })

        /**
         * Подключение к комет серверу. Для возможности принимать команды.
         * dev_id ваш публичный идентифиукатор разработчика
         */
        CometServer().start({
            dev_id: 3820
        })
    </script>
@endsection
