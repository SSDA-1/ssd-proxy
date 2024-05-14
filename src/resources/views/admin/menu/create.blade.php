@extends('admin.app')
@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Создание нового пункта меню')</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('menu.index') }}"><i class="bx bx-left-arrow-alt icon"></i> @lang('proxies::phrases.Назад')</a>
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

    <form action="{{ route('menu.store') }}" method="POST" data-fetch="none">
        @csrf
        <div class="block-background basement-form">
            <div class="row">
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Название'):</div>
                    <input type="text" placeholder="@lang('proxies::phrases.Название')" name="name" id="name" class="input-text">
                    <input type="text" placeholder="@lang('proxies::phrases.Название')_en" name="name_en" id="name_en" class="input-text">
                    <select id="socName" style="display:none;" class="select-multiple">
                        <option value="vk">@lang('proxies::phrases.ВКонтакте')</option>
                        <option value="facebook">Facebook</option>
                        <option value="telegram">@lang('proxies::phrases.Телеграм')</option>
                        <option value="skype">Skype</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="youtube">YouTube</option>
                        <option value="email">Email</option>
                    </select>
                </div>
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Ссылка'):</div>
                    <input type="text" placeholder="@lang('proxies::phrases.Ссылка')" name="link" class="input-text">
                </div>
                <div class="field">
                    <div class="title-field">@lang('proxies::phrases.Тип меню'):</div>
                    <select name="type_menu" id="type_menu" class="select-multiple">
                        <option value="1">@lang('proxies::phrases.Верхнее и мобильное меню')</option>
                        <option value="2">@lang('proxies::phrases.Подвал сайта')</option>
                        <option value="3">@lang('proxies::phrases.Социальные сети')</option>
                    </select>
                </div>
            </div>
            <div class="row group" id="local">
                <div class="row">
                    <div class="field">
                        <div class="title-field">@lang('proxies::phrases.Расположение'):</div>
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <div class="wrap-input title">
                            <div class="title-field">Header (@lang('proxies::phrases.Шапка')):</div>
                            <input type="checkbox" placeholder="@lang('proxies::phrases.Ссылка')" name="header" id="highload1" class="input-text">
                            <label for="highload1" data-onlabel="" data-offlabel="" class="lb1"></label>
                        </div>
                    </div>
                    <div class="field">
                        <div class="wrap-input title">
                            <div class="title-field">Footer (@lang('proxies::phrases.Подвал')):</div>
                            <input type="checkbox" placeholder="@lang('proxies::phrases.Ссылка')" name="footer" id="youmoney" class="input-text">
                            <label for="youmoney" data-onlabel="" data-offlabel="" class="lb1"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-block">
            <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
        </div>
    </form>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#type_menu').change(function() {
                let name = document.getElementById('name');
                let nameEn = document.getElementById('name_en');
                let socName = document.getElementById('socName');
                let local = document.getElementById('local');
                period = $('#type_menu :selected').val();
                if (period == 3) {
                    name.style.display = 'none';
                    name.removeAttribute('name');
                    nameEn.style.display = 'none';
                    nameEn.removeAttribute('name');
                    local.style.display = 'block';
                    socName.style.display = 'block';
                    socName.setAttribute('name', 'name');
                } else {
                    name.style.display = 'block';
                    nameEn.style.display = 'block';
                    local.style.display = 'none';
                    socName.removeAttribute('name')
                    socName.style.display = 'none';
                    name.setAttribute('name', 'name');
                    name.setAttribute('name', 'name_en');
                }
            });

            // Выполнить код сразу после загрузки страницы
            $('#type_menu').trigger('change');
        });
    </script>
@endsection
