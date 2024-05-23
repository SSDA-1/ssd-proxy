@extends('proxies::templates.' . (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() . '.layouts.app')

@section('style')
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/lk.css{{ '?' . time() }}">
@endsection
@section('body-class')
personal-area
@endsection 

@section('content')
    <div class="lk-block">
        @include('proxies::admin.lk.menu')
        <div class="lk-content">
            <div class="wrap-form form-payment">
                <div class="wrap-title">
                    <h3>@lang('proxies::phrases.Пополнение баланса')</h3>
                </div>
                {!! Form::open(['method' => 'POST', 'route' => 'PaymentPlusMoney', 'class' => 'payment-form balance']) !!}
                <div class="wrap-btn-modal balance">
                    <label for="balance">@lang('proxies::phrases.Введите сумму пополнения')</label>
                    <div class="amont-sum">
                        <div class="inp-sum">
                            <input type="text" name="balance" id="balance" placeholder="$ 100" style="color: black"
                                min="{{ $settingsData->min_replenishment_amount }}" />
                            <span>@lang('proxies::phrases.Минимальная сумма пополнения') ${{ $settingsData->min_replenishment_amount }}</span>
                        </div>
                        <button class="btn no-hover">@lang('proxies::phrases.Пополнить')</button>
                    </div>
                </div>
                <div class="payment-systems">
                    <div class="title">@lang('proxies::phrases.Выберите способ оплаты')</div>
                    <ul>
                        @if ($settingsData->qiwi_pay > 0)
                            <li>
                                <input type="radio" name="gateway" id="qiwi" value="qiwi" class="custom-radio">
                                <label for="qiwi">
                                    <img src="/vendor/ssda-1/proxies/assets/img/qiwi.png" />
                                </label>
                            </li>
                        @endif 
                        @if ($settingsData->youmoney_pay > 0)
                            <li>
                                <input type="radio" name="gateway" id="yoomoney" value="yoomoney" class="custom-radio">
                                <label for="yoomoney">
                                    <img src="/vendor/ssda-1/proxies/assets/img/ymoney.png" />
                                </label>
                            </li>
                        @endif
                        @if ($settingsData->demo_pay > 0)
                            <li>
                                <input type="radio" name="gateway" id="demo" value="demo" class="custom-radio">
                                <label for="demo">
                                    <span>@lang('proxies::phrases.Демо пополнение')</span>
                                </label>
                            </li>
                        @endif
                        @if ($settingsData->freekassa_pay > 0)
                            <li>
                                <input type="radio" name="gateway" id="freekassa" value="freekassa" class="custom-radio">
                                <label for="freekassa">
                                    <img src="/vendor/ssda-1/proxies/assets/img/freekassa.png" />
                                </label>
                                <span class="text">Оплата картой</span>
                            </li>
                        @endif
                        @if ($settingsData->betatransfer_pay > 0)
                            <li>
                                <input type="radio" name="gateway" id="betatransfer" value="betatransfer"
                                    class="custom-radio">
                                <label for="betatransfer">
                                    <img src="/vendor/ssda-1/proxies/assets/img/betatransfer.png" />
                                </label>
                            </li>
                        @endif
                        @if ($settingsData->capitalist_pay > 0)
                            <li>
                                <input type="radio" name="gateway" id="capitalist" value="capitalist"
                                    class="custom-radio">
                                <label for="capitalist">
                                    <span>Capitalist</span>
                                </label>
                                <span class="text">Оплата Capitalist</span>
                            </li>
                        @endif
                        @if ($settingsData->usdtchecker_pay > 0)
                            <li>
                                <input type="radio" name="gateway" id="usdtchecker" value="usdtchecker"
                                    class="custom-radio">
                                <label for="usdtchecker">
                                    <span>Zerocryptopay</span>
                                </label>
                                <span class="text">Оплата криптовалютами</span>
                            </li>
                        @endif
                    </ul>
                </div>
                {!! Form::close() !!}
            </div>
            <style>
                .done {
                    display: none;
                    position: absolute;
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
        </div>
    </div>
@endsection

@section('modal')
@endsection

@section('javascript')
    <script>
        var balanceInput = document.getElementById("balance");

        balanceInput.addEventListener("input", function() {
            var value = parseInt(balanceInput.value);

            if (value < {{ $settingsData->min_replenishment_amount }}) {
                balanceInput.setCustomValidity("@lang('proxies::phrases.Значение не может быть ниже') {{ $settingsData->min_replenishment_amount }}");
            } else {
                balanceInput.setCustomValidity(""); // Сбросить сообщение об ошибке
            }
        });
    </script>
@endsection
