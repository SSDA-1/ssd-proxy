<header class="header center lk-header">
    <div class="wrap">
        <div class="header__logo">
            <a href="/"><img src="/vendor/ssda-1/proxies/assets/img/logo-w.png" alt="ads-proxy" /></a>
        </div>
        <nav class="header__nav">
            @foreach ($menusSite as $menu1)
                @if ($menu1->type_menu == 1)
                    <a class="header__link" href="{{ $menu1->link }}">{{ $menu1->name }}</a>
                @endif
            @endforeach
            <div class="footer__language">
                <div class="footer__language-text">@lang('proxies::phrases.Язык'):</div>
                <div class="footer__language">
                    @if (App::isLocale('en'))
                        <a href="{{ route('locale', ['language' => 'ru']) }}" class="footer__language-summary">RU</a>
                    @else
                        <a href="{{ route('locale', ['language' => 'en']) }}" class="footer__language-summary">EN</a>
                    @endif
                </div>
            </div>
        </nav>
        @guest
            <div class="btn header__private-btn">@lang('proxies::phrases.Личный кабинет')</div>
        @else
            <a class="avatar" href="/control-panel">
                {{-- <img src="/assets/img/avatar.png"> --}}
                {{ Auth::user()->name }}
            </a>
        @endguest
        @include(
            'proxies::templates.' .
                (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() .
                '.layouts.mob-menu')
    </div>
    <div class="title">
        <h1>@lang('proxies::phrases.Личный кабинет')</h1>
    </div>
</header>

<style>
    .done {
        display: none;
        /* position: absolute; */
        width: 100%;
        height: 100%;
        background: var(--gradient-3);
        z-index: 99;
        top: 0;
        left: 0;
        border-radius: 12px;
        justify-content: center;
        align-items: center;
    }

    .done.active {
        display: flex
    }
</style>
<div class="modal crypt" data-modal="crypt">
    <div class="done"> @lang('proxies::phrases.Спасибо, оплата прошла, одновите страницу') </div>
    <div class="wrap-title">
        <p class="modal__title">@lang('proxies::phrases.Пополнение баланса через Крипту')</p>
        <!--   Svg иконка для закрытия окна  -->
        <svg class="modal__cross js-modal-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path
                d="M23.954 21.03l-9.184-9.095 9.092-9.174-1.5-1.5-9.09 9.179-9.176-9.088-1.5 1.5 9.186 9.105-9.095 9.184 1.5 1.5 9.112-9.192 9.18 9.1z" />
        </svg>
    </div>
    {!! Form::open(['method' => 'POST', 'route' => 'PaymentPlusMoney', 'class' => 'payment-form']) !!}
    <div class="payment-systems">
        <p>@lang('proxies::phrases.Отправьте') <span id="amountUSDTChecker">5.910215</span> USDT TRC-20 @lang('proxies::phrases.на адрес'):</p>
        <p id="adressUSDTChecker">TrshdhHJHsbdD5DAd6DFAew48dDSAc4D</p>
        <div class="qrCode">

        </div>
    </div>
    <div class="wrap-btn-modal attention">
        <h2>@lang('proxies::phrases.ВНИМАНИЕ')!</h2>
        <p>@lang('proxies::phrases.Оплатить счёт необходимо в течении 10 минут отправив точную сумму').</p>
    </div>
    {!! Form::close() !!}
</div>
<div class="overlay js-overlay-modal"></div>
