@extends('admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('phrases.Редактирование пункта меню'): {{ $menu->name }}</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('menu.index') }}"><i class="bx bx-left-arrow-alt icon"></i> @lang('phrases.Назад')</a>
        </div>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger block-background">
            <strong>@lang('phrases.Упс')!</strong> @lang('phrases.Были некоторые проблемы с вашим вводом').<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('menu.update', $menu->id) }}" method="POST" data-fetch="none">
        @csrf
        @method('PUT')
        <div class="block-background basement-form">
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('phrases.Название'):</div>
                    <input type="text" placeholder="@lang('phrases.Название')"
                        @if ($menu->type_menu == 3) style="display:none;" @else name="name" @endif id="name"
                        value="{{ $menu->name }}" class="input-text">
                    <div class="title-field">@lang('phrases.Название')_en:</div>
                    <input type="text" placeholder="@lang('phrases.Название')_en"
                           @if ($menu->type_menu == 3) style="display:none;" @else name="name_en" @endif id="name_en"
                           value="{{ $menu->name_en }}" class="input-text">
                    <select id="socName" @if ($menu->type_menu == 3) name="name" @else style="display:none;" @endif
                        value="{{ $menu->name }}" class="select-multiple">
                        @if ($menu->name == 'vk')
                            <option value="vk" selected>@lang('phrases.ВКонтакте')</option>
                        @else
                            <option value="vk">@lang('phrases.ВКонтакте')</option>
                        @endif
                        @if ($menu->name == 'facebook')
                            <option value="facebook" selected>Facebook</option>
                        @else
                            <option value="facebook">Facebook</option>
                        @endif
                        @if ($menu->name == 'telegram')
                            <option value="telegram" selected>@lang('phrases.Телеграм')</option>
                        @else
                            <option value="telegram">@lang('phrases.Телеграм')</option>
                        @endif
                        @if ($menu->name == 'skype')
                            <option value="skype" selected>Skype</option>
                        @else
                            <option value="skype">Skype</option>
                        @endif
                        @if ($menu->name == 'whatsapp')
                            <option value="whatsapp" selected>WhatsApp</option>
                        @else
                            <option value="whatsapp">WhatsApp</option>
                        @endif
                    </select>
                </div>
                <div class="field">
                    <div class="title-field">@lang('phrases.Ссылка'):</div>
                    <input type="text" name="link" value="{{ $menu->link }}" class="input-text"
                        placeholder="Ссылка">
                </div>
                <div class="field">
                    <div class="title-field">@lang('phrases.Тип меню'):</div>
                    <select name="type_menu" id="type_menu" value="{{ $menu->type_menu }}" class="select-multiple">
                        <option value="1">@lang('phrases.Верхнее и мобильное меню')</option>
                        <option value="2">@lang('phrases.Подвал сайта')</option>
                        @if ($menu->type_menu == 3)
                            <option value="3" selected>@lang('phrases.Социальные сети')</option>
                        @else
                            <option value="3">@lang('phrases.Социальные сети')</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="row group" id="local">
                <div class="row">
                    <div class="field">
                        <div class="title-field">@lang('phrases.Расположение'):</div>
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <div class="wrap-input title">
                            <div class="title-field">Header (@lang('phrases.Шапка')):</div>
                            <input type="checkbox" placeholder="@lang('phrases.Ссылка')" name="header" id="highload1" class="input-text"
                                {{ $menu->top_botton == 1 ? 'checked' : '' }}>
                            <label for="highload1" data-onlabel="" data-offlabel="" class="lb1"></label>
                        </div>
                    </div>
                    <div class="field">
                        <div class="wrap-input title">
                            <div class="title-field">Footer (@lang('phrases.Подвал')):</div>
                            <input type="checkbox" placeholder="@lang('phrases.Ссылка')" name="footer" id="youmoney" class="input-text"
                                {{ $menu->top_botton == 2 ? 'checked' : '' }}>
                            <label for="youmoney" data-onlabel="" data-offlabel="" class="lb1"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-block">
            <button type="submit" class="btn btn-primary">@lang('phrases.Сохранить')</button>
        </div>
    </form>

@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#type_menu').change(function() {
                let name = document.getElementById('name');
                let socName = document.getElementById('socName');
                let local = document.getElementById('local');
                period = $('#type_menu :selected').val();
                if (period == 3) {
                    name.style.display = 'none';
                    name.removeAttribute('name');
                    local.style.display = 'block';
                    socName.style.display = 'block';
                    socName.setAttribute('name', 'name');
                } else {
                    name.style.display = 'block';
                    local.style.display = 'none';
                    socName.removeAttribute('name')
                    socName.style.display = 'none';
                    name.setAttribute('name', 'name');
                }
            });

            // Выполнить код сразу после загрузки страницы
            $('#type_menu').trigger('change');
        });
    </script>
@endsection
