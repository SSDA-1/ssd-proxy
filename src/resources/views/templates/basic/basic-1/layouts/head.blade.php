<!-- Меню настолка -->
<header class="main header center">
    <div class="header__logo">
        <a href="/"><img src="/assets/img/logo.svg" alt="ads-proxy"></a>
    </div>
    <nav class="header__nav">
        @foreach ($menusSite as $menu1)
            @if ($menu1->type_menu == 1)
                <a href="{{ $menu1->link }}" class="header__link">@lang('proxies::phrases.' . $menu1->name)</a>
            @endif
        @endforeach
        <div class="footer__language" style="align-items: center; padding: 0;">
            <div class="footer__language-text">@lang('proxies::phrases.Язык'):</div>
            <div class="footer__language" style="padding: 0;">
                @if (App::isLocale('en'))
                    <a href="{{ route('locale', ['language' => 'ru']) }}" class="footer__language-summary">RU</a>
                @else
                    <a href="{{ route('locale', ['language' => 'en']) }}" class="footer__language-summary">EN</a>
                @endif
            </div>
        </div>
    </nav>
    <div class="header__social">
        @foreach ($menusSite as $menu4)
            @if ($menu4->top_botton == 2)
                @if ($menu4->name == 'telegram')
                    <a href="{{ $menu4->link }}" target="_blank"><img src="/assets/img/telegramm.svg"
                            alt="telegramm"></a>
                @endif
                @if ($menu4->name == 'youtube')
                    <a href="{{ $menu4->link }}" target="_blank"><img src="/assets/img/youtube.svg" alt="youtube"></a>
                @endif
                @if ($menu4->name == 'email')
                    <a href="{{ $menu4->link }}" target="_blank"><img src="/assets/img/mymir.svg" alt="mymir"></a>
                @endif
            @endif
        @endforeach
    </div>
    @guest
        <a href="/login" class="btn header__private-btn none no-hover">@lang('proxies::phrases.Войти')</a>
    @else
        <a href="/lk" class="btn header__private-btn none no-hover">@lang('proxies::phrases.Личный кабинет')</a>
    @endguest
    {{-- <div class="menu-btn" id="nav-icon1">
        <span></span>
        <span></span>
        <span></span> 
    </div> --}}



    {{-- <div class="wrap">
        <div class="logo">
            @if ($settingsData->logo)
                <a href="/"><img src="{{ $settingsData->logo }}"></a>
            @else
                <a href="/">{{ $settingsData->name }}</a>
            @endif
        </div>
        <nav>
            <ul>
                @foreach ($menusSite as $menu1)
                    @if ($menu1->type_menu == 1)
                        <li><a href="{{ $menu1->link }}">{{ $menu1->name }}</a></li>
                    @endif
                @endforeach
            </ul>
        </nav>
        <div class="auth">
            <ul>
                @guest
                <li class="login"><a href="{{ route('login') }}"><img src="/assets/img/user.svg">Войти</a></li>
                @else
                <li class=""><a href="/control-panel"><img src="/assets/img/avatar.png">{{ Auth::user()->name }}</a></li>
                <li class="">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <img src="/assets/img/log-out.svg">Выйти
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
                @endguest
            </ul>
        </div>
    </div> --}}
    @include(
        'proxies::templates.' .
            (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() .
            '.layouts.mob-menu')
    {{-- <div class="wrap mob">
        <div class="menu-btn">
            <span></span>
            <span></span>
            <span></span>
        </div>
        @guest
        <div class="auth">
            <ul>
                <li class="login"><a href="/login"><img src="/assets/img/user.svg">Войти</a></li>
            </ul>
        </div>
        @endguest
        <div class="menu">
            <div class="warp-menu">
                <div class="menu-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="title-menu text-gradient">
                    Меню
                </div>
            </div>
            @auth
            <div class="auth-mob">
                <ul>
                    <li><a href="/lk"><img src="/assets/img/avatar.png">Don410</a></li>
                    <li><a href="/lk">Профиль</a></li>
                    <li><a href="/control-panel">Панель управления</a></li>
                    @can('admin-panel')<li><a href="/admin-panel">Панель администратора</a></li>@endcan
                    <li class="line"></li>
                    <li><a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
                            <img src="/assets/img/log-out.svg">Выйти
                        </a>
                    </li>
                </ul>
            </div>
            @endauth
            <nav>
                <ul>
                    @foreach ($menusSite as $menu1)
                        @if ($menu1->type_menu == 1)
                            <li><a href="{{ $menu1->link }}">{{ $menu1->name }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </nav>
        </div>
        <div class="ground"></div>

    </div> --}}
</header>










{{-- <div class="wraper-header">
    <div class="header">
        <div class="top-menu">
            <div class="logo-social">
                <div class="logo">
                    @if ($settingsData->logo)
                        <a href="/"><img src="{{ $settingsData->logo }}"></a>
                    @else
                        <a href="/">{{ $settingsData->name }}</a>
                    @endif
                </div>
                <div class="social">
                    <span class="line"></span>
                    <ul>
                        @foreach ($menusSite as $menu3)
                            @if ($menu3->type_menu == 3)
                                @if ($menu3->name == 'vk')
                                    <li><a href="{{ $menu3->link }}"><i class="fa fa-vk"></i></a></li>
                                @elseif($menu3->name == 'facebook')
                                    <li><a href="{{ $menu3->link }}"><i class="fa fa-facebook"></i></a></li>
                                @elseif($menu3->name == 'telegram')
                                    <li><a href="{{ $menu3->link }}"><i class="fa fa-paper-plane"></i></a></li>
                                @elseif($menu3->name == 'skype')
                                    <li><a href="{{ $menu3->link }}"><i class="fa fa-skype"></i></a></li>
                                @elseif($menu3->name == 'whatsapp')
                                    <li><a href="{{ $menu3->link }}"><i class="fa fa-comments"></i></a></li>
                                @endif
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="menu-right">
                <ul>
                    

                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Войти') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Зарегистрироватся') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <a href="/control-panel"><i class="fa fa-user"></i>{{ Auth::user()->name }}</a>
                            </a>
                        </li>
                        <li class="nav-item">
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Выйти') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest

                </ul>
            </div>
            <div class="menu_container">
                <a href="#" class="mobile_menu"><i class="fa fa-bars"></i></a>
            </div>
        </div>
    </div>
</div> --}}
