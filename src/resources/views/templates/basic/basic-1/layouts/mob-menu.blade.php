<div class="wrap mob">
    <div class="menu-btn">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="menu">
        <div class="warp-menu">
            <div class="menu-btn">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="title-menu text-gradient">
                @lang('proxies::phrases.Меню')
            </div>
        </div>
        @guest
            <div class="auth">
                <a href="/login" class="btn header__private-btn">@lang('proxies::phrases.Войти')</a>
            </div>
        @endguest
        @auth
            <div class="auth-mob">
                <ul>
                    <li>
                        <a href="/lk">
                            {{-- <img src="/assets/img/avatar.png"> --}}
                            {{ Auth::user()->name }}
                        </a>
                    </li>
                    <li><a href="/lk">@lang('proxies::phrases.Профиль')</a></li>
                    <li><a href="/control-panel">@lang('proxies::phrases.Панель управления')</a></li>
                    @can('admin-panel')
                        <li><a href="/admin-panel">@lang('proxies::phrases.Панель администратора')</a></li>
                    @endcan
                    <li class="line"></li>
                    <li><a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
                            <img src="/vendor/ssda-1/proxies/assets/img/log-out.svg">@lang('proxies::phrases.Выйти')
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

</div>
