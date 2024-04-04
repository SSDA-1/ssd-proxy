<!-- Меню настолка -->
<div class="data-demo">
    <div class="info">Это деманстрационная версия программы SEASIDE Proxy, предназначеная исключительно для ознакомления: Логин и пароль от панели администратора <b>Demo@ssdigital.ru Demo123</b></div>
    <div class="pay-info">Вы можете в любой момент оформать подписку на программу SEASIDE Proxy на сайте <a href="http://ssd-p.ru" target="_blank">http://ssd-p.ru</a></div>
</div> 
<div class="wraper-header">
    <div class="header">
        <div class="top-menu">
            <div class="logo-social">
                <div class="logo">
                    {{-- <a href="/">SSD Proxy</a> --}}
                     {{--{{dd($settingsData)}}--}}
                    @if ($settingsData->logo)
                        <a href="/"><img src="{{$settingsData->logo}}"></a>
                    @else
                        <a href="/">{{$settingsData->name}}</a>
                    @endif
                </div>
                <div class="social">
                    <span class="line"></span>
                    <ul>
                        {{--<li><a href=""><i class="fa fa-vk"></i></a></li>
                            <li><a href=""><i class="fa fa-facebook"></i></a></li>
                            <li><a href=""><i class="fa fa-paper-plane"></i></a></li>
                            <li><a href=""><i class="fa fa-skype"></i></a></li>
                            <li><a href=""><i class="fa fa-comments"></i></a></li>--}}
                        @foreach ($menusSite as $menu3)
                            @if($menu3->type_menu == 3)
                                @if($menu3->name == 'vk')
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
                    {{-- <li><a href="/">Главная</a></li>
                    <li><a href="/rules">Условия</a></li>
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/reviews">Отзывы</a></li>
                    <li><a href="/blog">Блог</a></li>
                    <li><a href="">Контакты</a></li>
                    <li><a href="">Партнёрская программа </a></li> --}}

                    @foreach ($menusSite as $menu1)
                        @if($menu1->type_menu == 1)
                            <li><a href="{{ $menu1->link }}">{{ $menu1->name }}</a></li>
                        @endif
                    @endforeach

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
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
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
</div>
