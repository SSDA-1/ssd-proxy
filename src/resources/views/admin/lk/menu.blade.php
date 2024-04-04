<div class="wrap-lk-menu">
    <div class="lk-menu">
        <div class="title">
            @if ($settingsData->referral_balance_enabled == 1)
                @if (Auth::user()->balance == 0)
                    <div class=""><span>@lang('phrases.Баланс'): </span><span>0$</span></div> 
                @else
                <div class=""><span>@lang('phrases.Баланс'): </span><span>{{ Auth::user()->balance }} $</span></div>
                @endif

                @if (Auth::user()->referral_balance == 0)
                <div class=""><span>@lang('phrases.Реф. баланс'): </span><span>0$</span></div>
                @else
                <div class=""><span>@lang('phrases.Реф. баланс'): </span><span>{{ Auth::user()->referral_balance }}$</span></div>
                @endif

            @else 

                @if (Auth::user()->balance == 0)
                <div class=""><span>@lang('phrases.Баланс'): </span><span>0$</span></div>
                @else
                <div class=""><span>@lang('phrases.Баланс'): </span><span>{{ Auth::user()->balance }}$</span></div>
                @endif

            @endif
        </div>
        <ul>
            @can('admin-panel')
                <li><a href="/admin-panel">@lang('phrases.Панель администратора')</a></li>
            @endcan
            <li><a @if (Request::is('buy-proxy')) class="active" @endif href="/buy-proxy">@lang('phrases.Купить прокси')</a></li>
            <li><a @if (Request::is('control-panel')) class="active" @endif href="/control-panel">@lang('phrases.Мои прокси')</a></li>
            <li><a @if (Request::is('replenishment')) class="active" @endif href="/replenishment">@lang('phrases.Пополнить баланс')</a></li>
            <li><a @if (Request::is('referral')) class="active" @endif href="/referral">@lang('phrases.Реферальная программа')</a>
            </li>
            <li><a @if (Request::is('lk')) class="active" @endif href="/lk">@lang('phrases.Настройки')</a></li>
            
            <li><a @if (Request::is('support') || Request::is('support/*') || Request::is('help')) class="active" @endif href="/help">@lang('phrases.Тех поддержка')</a></li>

            <li><a @if (Request::is('training-center')) class="active" @endif href="/training-center">@lang('phrases.Центр обучения')</a></li>
            <li><a @if (Request::is('partners')) class="active" @endif href="/partners">@lang('phrases.Партнеры и промокоды')</a></li>
            {{-- @can('admin-panel') class="no-border-1" @endcan --}}

            <li @can('admin-panel') class="no-border" @endcan>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    @lang('phrases.Выйти')
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>
