@extends('proxies::admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Тех. Поддержка - Обращение') #{{ $support->id }}</h2>
        </div>
        <div class="buttons">
            <form action="{{ route('closeSupp') }}" method="POST" data-fetch="none">
                @csrf
                <input type="hidden" name="id" value="{{ $support->id }}">
                <button type="submit" class="btn btn-delite"><span>@lang('proxies::phrases.Закрыть обращение')</span></button>
            </form>
            <a class="btn btn-success" href="{{ route('support.index') }}"><i class="bx bx-left-arrow-alt icon"></i>
                @lang('proxies::phrases.Назад')</a>
        </div>
    </div>

    <div class="block-background chat">
        <div class="bodyDialog" id="bodyDialog">
            <div class="topLine">@lang('proxies::phrases.Обращение') #{{ $support->id }} @if ($support->status == 0)
                    <span style="color:green;">@lang('proxies::phrases.Открыт')</span>
                @else
                    <span style="color:red;">@lang('proxies::phrases.Закрыт')</span>
                @endif
            </div>
            <div class="midleLine" id="messBody">
                @foreach ($support->AllSupportMassage as $message)
                    @if (!$message->admin)
                        <div class="bubble thisUser">
                            <div class="name">{{ $support->user->name }}</div>
                            <div class="massageBody">{{ $message->massage }}</div>
                            <div class="time">
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $support->created_at)->format('d M Y в H:i') }}
                            </div>
                        </div>
                    @else
                        <div class="bubble appUser">
                            <div class="name">Administrator</div>
                            <div class="massageBody">{{ $message->massage }}</div>
                            <div class="time">
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $support->created_at)->format('d M Y в H:i') }}
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            {!! Form::open(['route' => 'sendSupportMassAdmin','data-fetch' => 'yes']) !!}
            <div class="bottomLine">
                <div contenteditable="true" class="sendContent"></div>
                <textarea name="text" class="sendTextarea" cols="30" rows="10"></textarea>
                {!! Form::hidden('id', $support->id, []) !!}
                <button type="submit" class="btn send"></button>
            </div>
            {!! Form::close() !!}
            <div class="bottomLine close">
                @lang('proxies::phrases.Обращение закрыто')
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function updateScroll() {
            var element = document.getElementById("messBody");
            element.scrollTop = element.scrollHeight;
        }
        updateScroll()
        let contenteditableDiv = document.querySelector('.sendContent'),
            textAreaElement = document.querySelector('.sendTextarea');
        contenteditableDiv.addEventListener("input", function() {
            textAreaElement.value = contenteditableDiv.innerText;
        });
    </script>
    <script src="//comet-server.ru/CometServerApi.js" type="text/javascript"></script>
    <script type="text/javascript">
        /** 
         * Подписываемся на получение сообщения из канала Pipe_name
         */
        CometServer().subscription("web_{{ $support->id }}", function(event) {
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
                                <div class="name">Admin1</div>\
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
