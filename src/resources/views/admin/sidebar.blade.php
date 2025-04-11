<nav class="sidebar @if( Auth::user()->sidebarmoke == 0)close-sidebar @endif">
    <header>
        <div class="image-text">
            <span class="image">
                <a href="/">{{ $settingsData->name }}</a>
            </span>

            <div class="text logo-text">
                <span class="name">@lang('proxies::phrases.Привет'), </span>
                <span class="profession"> {{ Auth::user()->name }}</span>
            </div>
        </div>

        <i class='bx bx-chevron-right toggle'></i>
    </header>

    <div class="menu-bar">
        <div class="menu">

            <!-- <li class="search-box">
                <i class='bx bx-search icon'></i>
                <input type="text" placeholder="Search...">
            </li> -->

            <ul class="menu-links">
                <li class="nav-link">
                    <a href="/admin-panel">
                        <i class='bx bx-home-alt icon'></i>
                        <span class="text nav-text">@lang('proxies::phrases.Дашборд')</span>
                    </a>
                </li>

                {{-- Submenu --}}
                <li class="nav-link sub-close">
                    <a href="#" class="sub-link">
                        <i class='bx bx-notepad icon'></i>
                        <span class="text nav-text">@lang('proxies::phrases.Сайт')</span>
                    </a>
                    <ul class="submenu-close">
                        <li class="nav-link">
                            <a href="/admin/advantag">
                                <i class='bx bx-wallet icon'></i>
                                <span class="text nav-text">@lang('proxies::phrases.Преимущества')</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="/admin/rules">
                                <i class='bx bx-wallet icon'></i>
                                <span class="text nav-text">@lang('proxies::phrases.Правила сайта')</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="/admin/partners">
                                <i class='bx bx-wallet icon'></i>
                                <span class="text nav-text">@lang('proxies::phrases.Партнеры')</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="{{ route('news.index') }}">
                                <i class='bx bx-notepad icon'></i>
                                <span class="text nav-text">@lang('proxies::phrases.Блог')</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="{{ route('menu.index') }}">
                                <i class='bx bx-menu icon'></i>
                                <span class="text nav-text">@lang('proxies::phrases.Меню')</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="{{ route('faq-adm.index') }}">
                                <i class='bx bx-wallet icon'></i>
                                <span class="text nav-text">FAQ</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="{{ route('reviews-adm.index') }}">
                                <i class='bx bx-wallet icon'></i>
                                <span class="text nav-text">@lang('proxies::phrases.Отзывы')</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- /Submenu --}}

                <li class="nav-link">
                    <a href="{{ route('proxy.index') }}">
                        <i class='bx bx-collapse icon'></i>
                        <span class="text nav-text">@lang('proxies::phrases.Прокси')</span>
                    </a>
                </li>

                {{-- Submenu --}}
                <li class="nav-link sub-close">
                    <a href="#" class="sub-link">
                        <i class='bx bx-notepad icon'></i>
                        <span class="text nav-text">@lang('proxies::phrases.Реф. система')</span>
                    </a>
                    <ul class="submenu-close">
                        <li class="nav-link">
                            <a href="{{route('withdrawalrequest.index')}}">
                                <i class='bx bx-notepad icon'></i>
                                <span class="text nav-text">@lang('proxies::phrases.Заявки на вывод')</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="/admin/statistics/withdrawalrequest">
                                <i class='bx bx-menu icon'></i>
                                <span class="text nav-text">@lang('proxies::phrases.Статистика')</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- /Submenu --}}

                <li class="nav-link">
                    <a href="{{ route('users.index') }}">
                        <i class='bx bx-user icon'></i>
                        <span class="text nav-text">@lang('proxies::phrases.Пользователи')</span>
                    </a>
                </li>

                <li class="nav-link">
                    <a href="{{ route('roles.index') }}">
                        <i class='bx bx-layer icon'></i>
                        <span class="text nav-text">@lang('proxies::phrases.Роли')</span>
                    </a>
                </li>

                <!-- <li class="nav-link">
                    <a href="{{ route('template-management') }}">
                        <i class='bx bx-dock-right icon'></i>
                        <span class="text nav-text">Шаблоны</span>
                    </a>
                </li> -->

                <li class="nav-link">
                    <a href="{{ route('allSettingSite') }}">
                        <i class='bx bx-cog icon'></i>
                        <span class="text nav-text">@lang('proxies::phrases.Настройки')</span>
                    </a>
                </li>
               <li class="nav-link">
                    <a href="{{ route('logs.index') }}">
                        <i class='bx bx-cog icon'></i>
                        <span class="text nav-text">@lang('proxies::phrases.Логи')</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="bottom-content">
            <li class="nav-link">
                <a href="/admin/support">
                    <i class='bx bx-support icon'></i>
                    <span class="text nav-text">@lang('proxies::phrases.Тех. поддержка')</span>
                </a>
            </li>
            <li class="">
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class='bx bx-log-out icon'></i>
                    <span class="text nav-text">@lang('proxies::phrases.Выход')</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>

            <li class="mode">
                <div class="sun-moon">
                    <i class='bx bx-moon icon moon'></i>
                    <i class='bx bx-sun icon sun'></i>
                </div>
                <span class="mode-text text">@lang('proxies::phrases.Ночь')</span>

                <div class="toggle-switch">
                    <span class="switch"></span>
                </div>
            </li>

        </div>
    </div>

</nav>
