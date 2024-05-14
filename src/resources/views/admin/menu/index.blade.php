@extends('admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Меню')</h2>
        </div>
        <div class="buttons">
            <a class="btn btn-success" href="{{ route('menu.create') }}">@lang('proxies::phrases.Добавить пункт')</a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="block-background bottom-indent">
        <div class="title-block">
            <h3>@lang('proxies::phrases.Верхнее и мобильное меню')</h3>
            <span class="closeOpen mobUp"><i class='bx bx-chevron-down open1'></i><i
                    class='bx bx-chevron-up close1 no'></i></span>
        </div>
        <table class="table table-bordered mobUp no">
            <thead>
                <tr class="tr-name">
                    <th>No</th>
                    <th>@lang('proxies::phrases.Название')</th>
                    <th>@lang('proxies::phrases.Ссылка')</th>
                    <th>@lang('proxies::phrases.Действие')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($menu as $menu1)
                    @if ($menu1->type_menu == 1)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $menu1->name }}</td>
                            <td>{{ $menu1->link }}</td>
                            <td class="dayst">
                                <form action="{{ route('menu.destroy', $menu1->id) }}" method="POST" data-fetch="none">
                                    <a class="btn btn-action" href="{{ route('menu.show', $menu1->id) }}"><i
                                            class="fa-regular fa-eye"></i></a>
                                    <a class="btn btn-action" href="{{ route('menu.edit', $menu1->id) }}"><i
                                            class="fa-regular fa-pen-to-square"></i></a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @php
        $i = 0;
    @endphp
    <div class="block-background bottom-indent">
        <div class="title-block">
            <h3>@lang('proxies::phrases.Подвал сайта')</h3>
            <span class="closeOpen footerMenu"><i class='bx bx-chevron-down open2'></i><i
                    class='bx bx-chevron-up close2 no'></i></span>
        </div>
        <table class="table table-bordered footerMenu no">
            <thead>
                <tr class="tr-name">
                    <th>No</th>
                    <th>@lang('proxies::phrases.Название')</th>
                    <th>@lang('proxies::phrases.Ссылка')</th>
                    <th>@lang('proxies::phrases.Действие')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($menu as $menu2)
                    @if ($menu2->type_menu == 2)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $menu2->name }}</td>
                            <td>{{ $menu2->link }}</td>
                            <td class="dayst">
                                <form action="{{ route('menu.destroy', $menu2->id) }}" method="POST">
                                    <a class="btn btn-action" href="{{ route('menu.show', $menu2->id) }}"><i
                                            class="fa-regular fa-eye"></i></a>
                                    <a class="btn btn-action" href="{{ route('menu.edit', $menu2->id) }}"><i
                                            class="fa-regular fa-pen-to-square"></i></a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @php
        $i = 0;
    @endphp
    <div class="block-background bottom-indent">
        <div class="title-block">
            <h3>@lang('proxies::phrases.Социальные сети')</h3>
            <span class="closeOpen socMenu"><i class='bx bx-chevron-down open3'></i><i
                class='bx bx-chevron-up close3 no'></i></span>
        </div>
        <table class="table table-bordered socMenu no">
            <thead>
                <tr class="tr-name">
                    <th>No</th>
                    <th>@lang('proxies::phrases.Название')</th>
                    <th>@lang('proxies::phrases.Ссылка')</th>
                    <th>@lang('proxies::phrases.Действие')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($menu as $menu3)
                    @if ($menu3->type_menu == 3)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $menu3->name }}</td>
                            <td>{{ $menu3->link }}</td>
                            <td class="dayst">
                                <form action="{{ route('menu.destroy', $menu3->id) }}" method="POST">
                                    <a class="btn btn-action" href="{{ route('menu.show', $menu3->id) }}"><i
                                            class="fa-regular fa-eye"></i></a>
                                    <a class="btn btn-action" href="{{ route('menu.edit', $menu3->id) }}"><i
                                            class="fa-regular fa-pen-to-square"></i></a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('script')
    <script>
        let open = document.querySelector("div.open");
        let close = document.querySelector("div.close");
        let i = 1;

        $('.mobUp').click(function() {
            if (i == 1) {
                $(".close1").removeClass('no');
                $(".open1").toggleClass('no');
                $(".table.table-bordered.mobUp").removeClass('no');
                i--
            } else {
                $(".close1").toggleClass('no');
                $(".open1").removeClass('no');
                $(".table.table-bordered.mobUp").toggleClass('no');
                i++
            }
        });
        $('.footerMenu').click(function() {
            if (i == 1) {
                $(".close2").removeClass('no');
                $(".open2").toggleClass('no');
                $(".table.table-bordered.footerMenu").removeClass('no');
                i--
            } else {
                $(".close2").toggleClass('no');
                $(".open2").removeClass('no');
                $(".table.table-bordered.footerMenu").toggleClass('no');
                i++
            }
        });
        $('.socMenu').click(function() {
            if (i == 1) {
                $(".close3").removeClass('no');
                $(".open3").toggleClass('no');
                $(".table.table-bordered.socMenu").removeClass('no');
                i--
            } else {
                $(".close3").toggleClass('no');
                $(".open3").removeClass('no');
                $(".table.table-bordered.socMenu").toggleClass('no');
                i++
            }
        });
    </script>
@endsection
