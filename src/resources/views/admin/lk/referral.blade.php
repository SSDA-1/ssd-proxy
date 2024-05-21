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
            <section class="lk1 ref">
                <div class="wrap">
                    <div class="data-ref data-sum">
                        <div class="wrap-ref">
                            <h3>@lang('proxies::phrases.Ваша партнерская ссылка')</h3>
                            <div class="ref-link copy" id="block1" data-target="block1">
                                <div>{{ route('register', ['ref' => auth()->user()->referral_code]) }}</div>
                                <img src="/assets/img/copy-w.svg" />
                            </div>
                        </div>
                        <div class="wrap-ref-act">
                            <div class="wrap-ref">
                                <h3>@lang('proxies::phrases.Рекламные материалы')</h3>
                                <div class="wrap-ref link-dow">
                                    <a href="{{ asset($settingsData->promotional_materials) }}"
                                        class="btn button">@lang('proxies::phrases.Скачать')</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wrap-form form-payment text-ref">
                        <div class="wrap-ref">
                            <h3>@lang('proxies::phrases.Как это работает')</h3>
                            <div class="text">
                                <p>@lang('proxies::phrases.Используйте свою уникальную ссылку, приводите трафик по ссылке и получайте процент с каждой продажи по этой ссылке пожизненно.')</p>
                                <br>
                                <p>@lang('proxies::phrases.Обратите внимание, что оплата своего аккаунта по собственной реферальной ссылке не допускается. В этом случае реферальные бонусы начислены не будут.')</p>
                            </div>
                        </div>
                        <div class="wrap-ref">
                            <h3>@lang('proxies::phrases.Вывод накопленных средств')</h3>
                            <div class="text">
                                <p>@lang('proxies::phrases.Бонусные деньги можно выводить на свою банковскую карту, крипто кошелек или на Capitalist.')</p><br>
                                <p>@lang('proxies::phrases.Вы будете стабильно зарабатывать в течении всего периода, пока приведенные вами рефералы будут приобретать и продлевать свои прокси.')</p>
                            </div>
                        </div>
                        <div class="wrap-ref wrap-ref-sum">
                            <div class="ref-sum">
                                <div class="logo">
                                    <img src="/assets/img/logo-w.png" alt="ads-proxy" />
                                </div>
                                <div class="sum">
                                    @if ($siteSettingModel->referral_balance_enabled == 1)
                                        @if (Auth::user()->referral_balance == 0)
                                            0 $
                                        @else
                                            {{ Auth::user()->referral_balance }} $
                                        @endif
                                    @else
                                        @if (Auth::user()->balance == 0)
                                            0 $
                                        @else
                                            {{ Auth::user()->balance }} $
                                        @endif
                                    @endif
                                </div>
                                <div class="commission">
                                    {{ $siteSettingModel->deposit_percentage }}% @lang('proxies::phrases.Ваша текущая комиссия')
                                </div>
                            </div>
                            <div class="btn button {{ Auth::user()->referral_balance <= $siteSettingModel->minimum_withdrawal_amount ? '' : 'js-open-modal' }}"
                                data-modal="conclusion" data-title="Заявка на вывод"
                                {{ Auth::user()->referral_balance <= $siteSettingModel->minimum_withdrawal_amount ? 'disabled' : '' }}>
                                @lang('proxies::phrases.Вывести')
                            </div>
                        </div>
                    </div>

                    <div class="data-ref">
                        <h3>@lang('proxies::phrases.Ваши рефералы')</h3>
                        <ul>
                            <li>
                                <div class="number">{{ $referrerReferralsTodey }}</div>
                                @lang('proxies::phrases.За сегодня')
                            </li>
                            <li>
                                <div class="number">{{ $referrerReferralsWeek }}</div>
                                @lang('proxies::phrases.За неделю')
                            </li>
                            <li>
                                <div class="number">{{ $referrerReferralsMontch }}</div>
                                @lang('proxies::phrases.За месяц')
                            </li>
                            <li>
                                <div class="number">{{ $referrerReferralsAll }}</div>
                                @lang('proxies::phrases.За все время')
                            </li>
                        </ul>
                    </div>
                    {{-- <div class="data-ref data-sum">
                        <div class="wrap-ref">
                            <h3>@lang('proxies::phrases.Ваша партнерская ссылка')</h3>
                            <div class="ref-link copy" id="block1" data-target="block1">
                                <div>{{ route('register', ['ref' => auth()->user()->referral_code]) }}</div>
                                <img src="/assets/img/copy-w.svg" />
                            </div>
                        </div>
                        <div class="wrap-ref-act">
                            <div class="wrap-ref">
                                <h3>@lang('proxies::phrases.Мой баланс'):</h3>
                                <div class="ref-sum">
                                    <div class="sum">
                                        @if ($siteSettingModel->referral_balance_enabled == 1)
                                            @if (Auth::user()->referral_balance == 0)
                                                0 $
                                            @else
                                                {{ Auth::user()->referral_balance }} $
                                            @endif
                                        @else
                                            @if (Auth::user()->balance == 0)
                                                0 $
                                            @else
                                                {{ Auth::user()->balance }} $
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="wrap-ref">
                                <div class="btn button no-hover js-open-modal" data-modal="conclusion"
                                    data-title="Заявка на вывод">@lang('proxies::phrases.Вывести')</div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </section>
            <div class="indent"></div>
            <section class="table-ref">
                <div class="wrap">
                    <div class="table">
                        {{-- <h3>@lang('proxies::phrases.История зачислений')</h3> --}}
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('proxies::phrases.Дата')</th>
                                    <th>@lang('proxies::phrases.Сумма')</th>
                                    <th>@lang('proxies::phrases.Операция')</th>
                                    <th>@lang('proxies::phrases.Тип')</th>
                                    <th>@lang('proxies::phrases.Примечание')</th>
                                    <th>@lang('proxies::phrases.Баланс до')</th>
                                    <th>@lang('proxies::phrases.Баланс после')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($referrerHistoryOperation as $key => $operation)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($operation->created_at)->format('d.m.Y') }}</strong><br>
                                            <span>{{ \Carbon\Carbon::parse($operation->created_at)->format('H:i:s') }}</span>
                                        </td>
                                        <td>{{ $operation->amount }}$</td>
                                        {!! $operation->type == 'plus'
                                            ? '<td class="plus">' . __('proxies::phrases.Начисление') . '</td>'
                                            : '<td class="minus">' . __('proxies::phrases.Списание') . '</td>' !!}
                                        <td>{{ $siteSettingModel->deposit_percentage }}%, @lang('proxies::phrases.От'):
                                            {{ Ssda1\proxies\Models\User::find($operation->referred_by)->name }}</td>
                                        <td>{{ $operation->duration }} @lang('proxies::phrases.дней'), {{ $operation->quantity }}
                                            @lang('proxies::phrases.шт'), {{ $operation->country ?? '' }}</td>
                                        <td>{{ $operation->balance_before ?? '-' }}$</td>
                                        <td>{{ $operation->balance_after ?? '-' }}$</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="radius" colspan="8">@lang('proxies::phrases.Зачисления отсутсвуют')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- <div class="table">
                        <h3>@lang('proxies::phrases.История вывода средств')</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>@lang('proxies::phrases.Дата')</th>
                                    <th>@lang('proxies::phrases.Время')</th>
                                    <th class="radius-r">@lang('proxies::phrases.Карта')</th>
                                    <th class="mob-none">@lang('proxies::phrases.Статус')</th>
                                    <th class="mob-none">@lang('proxies::phrases.Сумма')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawalRequest as $request)
                                    @php
                                        $status = $request->status == 0 ? __('proxies::phrases.Активна') : ($request->status == 1 ? __('proxies::phrases.В работе') : __('proxies::phrases.Выполнена'));
                                    @endphp
                                    <tr>
                                        <td>
                                            @if (!$request->execution_date)
                                                {{ $status }}
                                            @else
                                                {{ \Carbon\Carbon::parse($request->execution_date)->format('d.m.Y') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (!$request->execution_date)
                                                {{ $status }}
                                            @else
                                                {{ \Carbon\Carbon::parse($request->execution_date)->format('H:i:s') }}
                                            @endif
                                        </td>
                                        <td class="radius-r">**** **** **** {{ substr($request->card_number, -4) }}</td>
                                        <td class="mob-none">{{ $status }}</td>
                                        <td class="mob-none">{{ $request->amount }}$</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="radius" colspan="5">@lang('proxies::phrases.Заявки отсутствуют')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div> --}}
                </div>
            </section>
            <div class="indent"></div>
        </div>
    </div>
@endsection
@section('modal')
    <div class="modal" data-modal="conclusion">
        <div class="wrap-title">
            <p class="modal__title">@lang('proxies::phrases.Заявка на вывод')</p>
            <!--   Svg иконка для закрытия окна  -->
            <svg class="modal__cross js-modal-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                    d="M23.954 21.03l-9.184-9.095 9.092-9.174-1.5-1.5-9.09 9.179-9.176-9.088-1.5 1.5 9.186 9.105-9.095 9.184 1.5 1.5 9.112-9.192 9.18 9.1z" />
            </svg>
        </div>
        <form action="/fetch/send/payment" class="payment-form conclusion" method="POST">
            <div class="radio">
                <p>@lang('proxies::phrases.Куда вывести средства')?</p>
                <div class="wrap-radio">
                    @if ($siteSettingModel->card_output == 1)
                        <label for="cards">
                            <input type="radio" name="derivation" id="cards" value="cards" checked>
                            @lang('proxies::phrases.Карта')
                        </label>
                    @endif
                    @if ($siteSettingModel->ecash_output == 1)
                        <label for="wallets">
                            <input type="radio" name="derivation" id="wallets" value="wallets">
                            @lang('proxies::phrases.Электронный кошелёк')
                        </label>
                    @endif
                    @if ($siteSettingModel->usdt_trc_20_output == 1)
                        <label for="usdt_trc">
                            <input type="radio" name="derivation" id="usdt_trc" value="usdt_trc">
                            USDT TRC 20
                        </label>
                    @endif
                    @if ($siteSettingModel->capitalist_output == 1)
                        <label for="capitalist">
                            <input type="radio" name="derivation" id="capitalist" value="capitalist">
                            CAPITALIST
                        </label>
                    @endif
                </div>
            </div>
            <div class="warp-card wrap-wallet cards">
                @if ($siteSettingModel->card_output == 1)
                    <label for="card" class="label-card">
                        @lang('proxies::phrases.Номер карты')
                        <input class="card" type="text" name="card" id="card"
                            placeholder="0000 0000 0000 0000">
                    </label>
                @endif
                @if ($siteSettingModel->ecash_output == 1)
                    <label for="wallet" class="label-wallet">
                        @lang('proxies::phrases.Номер кошелька')
                        <input class="card" type="text" name="wallet" id="wallet" placeholder="0000000000000000">
                    </label>
                    <label for="name-wallet" class="label-name-wallet">
                        @lang('proxies::phrases.Название кошелька')
                        <input class="card" type="text" name="name-wallet" id="name-wallet" placeholder="Qiwi">
                    </label>
                @endif
                @if ($siteSettingModel->usdt_trc_20_output == 1)
                    <label for="usdt" class="label-usdt">
                        @lang('proxies::phrases.Номер кошелька')
                        <input class="card" type="text" name="usdt" id="usdt"
                            placeholder="0000000000000000">
                    </label>
                @endif
                @if ($siteSettingModel->capitalist_output == 1)
                    <label for="capitalist" class="label-capitalist">
                        @lang('proxies::phrases.Номер кошелька')
                        <input class="card" type="text" name="capitalist" id="capitalist"
                            placeholder="0000000000000000">
                    </label>
                @endif
            </div>
            <div class="warp-card">
                <label for="sum">
                    @lang('proxies::phrases.Сумма вывода')
                    <input class="card" min="{{ $siteSettingModel->minimum_withdrawal_amount }}"
                        max="{{ Auth::user()->balance }}" type="number" name="sum" id="sum"
                        placeholder="1200">
                </label>

            </div>
            <div class="message">
                @lang('proxies::phrases.Сообщение')
                <textarea name="" id="" cols="10" rows="2"></textarea>
            </div>
            <div class="wrap-btn-modal">
                <button type="submit" class="btn no-hover" style="width: 100%">
                    @lang('proxies::phrases.Вывести')
                </button>
            </div>
        </form>
    </div>

    <div class="overlay js-overlay-modal"></div>
@endsection
@section('script')
    <script>
        const radioButtons = document.querySelectorAll('.wrap-radio input[type="radio"]');
        const wrapWallet = document.querySelector('.wrap-wallet');

        function handleRadioButtonChange() {
            wrapWallet.classList.remove('cards', 'wallets', 'usdt', 'capitalist');
            if (radioButtons[0].checked) {
                wrapWallet.classList.add('cards');
            } else if (radioButtons[1].checked) {
                wrapWallet.classList.add('wallets');
            } else if (radioButtons[2].checked) {
                wrapWallet.classList.add('usdt');
            } else if (radioButtons[3].checked) {
                wrapWallet.classList.add('capitalist');
            }
        }
        for (let i = 0; i < radioButtons.length; i++) {
            radioButtons[i].addEventListener('change', handleRadioButtonChange);
        }
    </script>
    <script>
        const copyBtns = document.querySelectorAll(".copy");

        copyBtns.forEach((copyBtn) => {
            copyBtn.addEventListener("click", () => {
                const target = copyBtn.getAttribute("data-target");
                let copyText;
                let originalContent;

                if (target.startsWith("link")) {
                    const linkEl = document.querySelector(`#${target}`);
                    if (linkEl === null) {
                        var originalSrc = copyBtn.src;
                        copyText = copyBtn.dataset.link;
                        copyBtn.src = '/assets/img/green_checkmark.svg';
                        setTimeout(function() {
                            copyBtn.src = originalSrc;
                        }, 1000);
                    } else {
                        copyText = linkEl.href;
                    }
                } else {
                    const copyTextContainer = document.querySelector(`#${target}`);
                    const tempTextareaEl = document.createElement("textarea");
                    originalContent = copyBtn.innerHTML;
                    tempTextareaEl.value = copyTextContainer.innerText;
                    document.body.appendChild(tempTextareaEl);
                    tempTextareaEl.select();
                    document.execCommand("copy");
                    document.body.removeChild(tempTextareaEl);
                    copyTextContainer.innerHTML = '<div style="width:100%;">@lang('proxies::phrases.Скопировано')</div>';
                    setTimeout(function() {
                        copyTextContainer.innerHTML = originalContent;
                    }, 1000);

                    return;
                }

                const tempTextareaEl = document.createElement("textarea");
                tempTextareaEl.value = copyText;
                document.body.appendChild(tempTextareaEl);
                tempTextareaEl.select();
                document.execCommand("copy");
                document.body.removeChild(tempTextareaEl);
            });
        });
    </script>
@endsection
