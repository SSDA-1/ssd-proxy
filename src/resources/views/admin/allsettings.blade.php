@extends('proxies::admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Настройки сайта')</h2>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="settings-tabs">
        <div class="tabs__nav background">
            <button class="tabs__btn tabs__btn_active first">@lang('proxies::phrases.Основные')</button>
            <button class="tabs__btn">@lang('proxies::phrases.СЕО')</button>
            <button class="tabs__btn">@lang('proxies::phrases.Платёжные системы')</button>
            <button class="tabs__btn">@lang('proxies::phrases.Уведомления')</button>
            <button class="tabs__btn last">@lang('proxies::phrases.Реферальная система')</button>
        </div>
        <div class="tabs__content block-background">
            <div class="tabs__pane tabs__pane_show flex-block">
                <form action="{{ route('allSettingsSiteSave') }}" method="POST" class="flex"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="padding-20">
                        <div class="row">
                            <div class="field">
                                <h3>@lang('proxies::phrases.Основные')</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Название сайта'):</div>
                                <input type="text" placeholder="@lang('proxies::phrases.Название сайта')" name="name_site"
                                    value="{{ $siteSettingModel->name }}" class="input-text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Фавикон'):</div>
                                <div class="wrap-input">
                                    <input type="file" placeholder="icon" name="icon" value=""
                                        @if ($siteSettingModel->icon) class="input-text copy" @else class="input-text" @endif>
                                    @if ($siteSettingModel->icon)
                                        <button type="submit" data-title="@lang('proxies::phrases.Вы точно хотите удалить Иконку сайта')?"
                                            data-action="{{ route('delimageSetting') }}" data-type="icon" data-modal="del"
                                            class="btn-input-delite">@lang('proxies::phrases.Удалить')</button>
                                    @endif
                                </div>
                            </div>
                            @if ($siteSettingModel->icon)
                                <div class="field icon">
                                    <img src="{{ $siteSettingModel->icon }}" style="width: 30px;height: 30px;"
                                        id="iconFile">
                                </div>
                            @endif
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Логотип Сайта (вместо названия)'):</div>
                                <div class="wrap-input">
                                    <input type="file" placeholder="logo" name="logo" value=""
                                        @if ($siteSettingModel->logo) class="input-text copy" @else class="input-text" @endif>
                                    @if ($siteSettingModel->logo)
                                        <button type="submit" data-title="@lang('proxies::phrases.Вы точно хотите удалить Логотип')?"
                                            data-action="{{ route('delimageSetting') }}" data-modal="del" data-type="logo"
                                            class="btn-input-delite">@lang('proxies::phrases.Удалить')</button>
                                    @endif
                                </div>
                            </div>
                            @if ($siteSettingModel->logo)
                                <div class="field icon">
                                    <img src="{{ $siteSettingModel->logo }}" style="max-width: 100px;" id="logoFile">
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">Telegram:</div>
                                <input type="text" placeholder="Telegram" name="telegram"
                                    value="{{ $siteSettingModel->telegram }}" class="input-text">
                            </div>
                            <div class="field">
                                <div class="title-field">E-mail:</div>
                                <input type="text" placeholder="Email" name="email"
                                    value="{{ $siteSettingModel->email }}" class="input-text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">WhatsApp:</div>
                                <input type="text" placeholder="Skype" name="skype"
                                    value="{{ $siteSettingModel->skype }}" class="input-text">
                            </div>
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Адрес'):</div>
                                <input type="text" placeholder="@lang('proxies::phrases.Адрес')" name="address"
                                    value="{{ $siteSettingModel->address }}" class="input-text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <h3>@lang('proxies::phrases.Сотрудничество')</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">Telegram:</div>
                                <input type="text" placeholder="Telegram" name="cooperation_tg"
                                    value="{{ $siteSettingModel->cooperation_tg }}" class="input-text">
                            </div>
                            <div class="field">
                                <div class="title-field">Email:</div>
                                <input type="text" placeholder="Email" name="cooperation_email"
                                    value="{{ $siteSettingModel->cooperation_email }}" class="input-text">
                            </div>
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Телефон'):</div>
                                <input type="text" placeholder="+7 (000) 000 00-00" name="cooperation_tel"
                                    value="{{ $siteSettingModel->cooperation_tel }}" class="input-text">
                            </div>
                        </div>
                    </div>
                    <div class="footer-block">
                        <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
                    </div>
                </form>
            </div>
            <div class="tabs__pane flex-block">
                <form action="{{ route('ceoSettingsSiteSave') }}" method="POST">
                    @csrf
                    <div class="padding-20">
                        <div class="row">
                            <div class="field">
                                <h3>@lang('proxies::phrases.СЕО')</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Описание сайта'):</div>
                                <input type="text" placeholder="@lang('proxies::phrases.Описание сайта')" name="ceo_desc" class="input-text"
                                    value="{{ $siteSettingModel->ceo_desc }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Ключевые слова сайта (Указывать через запятую)'):</div>
                                <input type="text" placeholder="@lang('proxies::phrases.Сайт, Продажа проксей, прокси')" class="input-text"
                                    name="ceo_keywords" value="{{ $siteSettingModel->ceo_keywords }}">
                            </div>
                        </div>
                    </div>
                    <div class="footer-block not-radius">
                        <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
                    </div>
                </form>
                <form action="{{ route('ceoSettingsSiteSave') }}" method="POST">
                    @csrf
                    <div class="padding-20">
                        <div class="row">
                            <div class="field">
                                <h3>@lang('proxies::phrases.Метрики')</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">Google:</div>
                                <textarea name="google_m" id="" cols="30" rows="10" class="textarea" placeholder="@lang('proxies::phrases.Код метрики')">{{ $siteSettingModel->google_m }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">Yandex:</div>
                                <textarea name="yandex_m" id="" cols="30" rows="10" class="textarea" placeholder="@lang('proxies::phrases.Код метрики')">{{ $siteSettingModel->yandex_m }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="footer-block">
                        <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
                    </div>
                </form>
            </div>
            <div class="tabs__pane flex-block">
                <form action="{{ route('PaymentSettingsAdmin') }}" method="POST" class="flex">
                    @csrf
                    <div class="padding-20">
                        <div class="row">
                            <div class="field">
                                <h3>@lang('proxies::phrases.Платёжные системы')</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">@lang('proxies::phrases.Минимальная сумма пополнения')</div>
                                <input type="number" name="min_replenishment_amount"
                                    value="{{ $siteSettingModel->min_replenishment_amount }}" class="input-text">
                            </div>
                        </div>
                        <div class="row group">
                            <div class="row">
                                <div class="field">
                                    <div class="wrap-input title">
                                        <div class="title-field">Qiwi:</div>
                                        <input type="checkbox" id="highload1" name="qiwi_pay"
                                            {{ $siteSettingModel->qiwi_pay == 1 ? 'checked' : '' }}>
                                        <label for="highload1" data-onlabel="" data-offlabel="" class="lb1"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="text" placeholder="@lang('proxies::phrases.Публичный ключ')" name="qiwi_public"
                                        class="input-text" value="{{ $siteSettingModel->qiwi_public }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="text" placeholder="@lang('proxies::phrases.Приватный ключ')" name="qiwi_private"
                                        class="input-text" value="{{ $siteSettingModel->qiwi_private }}">
                                </div>
                            </div>
                        </div>
                        <div class="row group">
                            <div class="row">
                                <div class="field">
                                    <div class="wrap-input title">
                                        <div class="title-field">Юmoney:</div>
                                        <input type="checkbox" id="youmoney" name="youmoney_pay"
                                            {{ $siteSettingModel->youmoney_pay == 1 ? 'checked' : '' }}>
                                        <label for="youmoney" data-onlabel="" data-offlabel="" class="lb1"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="text" placeholder="@lang('proxies::phrases.Публичный ключ')" name="youmoney_public"
                                        class="input-text" value="{{ $siteSettingModel->youmoney_public }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="text" placeholder="@lang('proxies::phrases.Приватный ключ')" name="youmoney_private"
                                        class="input-text" value="{{ $siteSettingModel->youmoney_private }}">
                                </div>
                            </div>
                        </div>
                        <div class="row group">
                            <div class="row">
                                <div class="field">
                                    <div class="wrap-input title">
                                        <div class="title-field">FreeKassa:</div>
                                        <input type="checkbox" id="highload2" name="freekassa_pay"
                                            {{ $siteSettingModel->freekassa_pay == 1 ? 'checked' : '' }}>
                                        <label for="highload2" data-onlabel="" data-offlabel="" class="lb1"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="text" placeholder="@lang('proxies::phrases.ID магазина')" name="freekassa_id"
                                        class="input-text" value="{{ $siteSettingModel->freekassa_id }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="text" placeholder="@lang('proxies::phrases.Приватный ключ')" name="freekassa_secret"
                                        class="input-text" value="{{ $siteSettingModel->freekassa_secret }}">
                                </div>
                            </div>
                        </div>
                        <div class="row group">
                            <div class="row">
                                <div class="field">
                                    <div class="wrap-input title">
                                        <div class="title-field">Betatransfer:</div>
                                        <input type="checkbox" id="highload3" name="betatransfer_pay"
                                            {{ $siteSettingModel->betatransfer_pay == 1 ? 'checked' : '' }}>
                                        <label for="highload3" data-onlabel="" data-offlabel="" class="lb1"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="text" placeholder="@lang('proxies::phrases.Публичный ключ')" name="betatransfer_public"
                                        class="input-text" value="{{ $siteSettingModel->betatransfer_public }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="text" placeholder="@lang('proxies::phrases.Приватный ключ')" name="betatransfer_secret"
                                        class="input-text" value="{{ $siteSettingModel->betatransfer_secret }}">
                                </div>
                            </div>
                        </div>
                        <div class="row group">
                            <div class="row">
                                <div class="field">
                                    <div class="wrap-input title">
                                        <div class="title-field">Capitalist:</div>
                                        <input type="checkbox" id="highload4" name="capitalist_pay"
                                            {{ $siteSettingModel->capitalist_pay == 1 ? 'checked' : '' }}>
                                        <label for="highload4" data-onlabel="" data-offlabel="" class="lb1"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="text" placeholder="@lang('proxies::phrases.ID магазина')" name="capitalist_id"
                                        class="input-text" value="{{ $siteSettingModel->capitalist_id }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="text" placeholder="@lang('proxies::phrases.Приватный ключ')" name="capitalist_secret"
                                        class="input-text" value="{{ $siteSettingModel->capitalist_secret }}">
                                </div>
                            </div>
                        </div>
                        <div class="row group">
                            <div class="row">
                                <div class="field">
                                    <div class="wrap-input title">
                                        <div class="title-field">USDT Checker:</div>
                                        <input type="checkbox" id="highload5" name="usdtchecker_pay"
                                            {{ $siteSettingModel->usdtchecker_pay == 1 ? 'checked' : '' }}>
                                        <label for="highload5" data-onlabel="" data-offlabel="" class="lb1"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="text" placeholder="@lang('proxies::phrases.Token магазина')" name="usdtchecker_token"
                                        class="input-text" value="{{ $siteSettingModel->usdtchecker_token }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="text" placeholder="@lang('proxies::phrases.Приватный ключ') (Secret Key)"
                                        name="usdtchecker_secret" class="input-text"
                                        value="{{ $siteSettingModel->usdtchecker_secret }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="wrap-input title">
                                    <div class="title-field">@lang('proxies::phrases.Тестовая оплата'):</div>
                                    <input type="checkbox" id="demopay" name="demo_pay"
                                        {{ $siteSettingModel->demo_pay == 1 ? 'checked' : '' }}>
                                    <label for="demopay" data-onlabel="" data-offlabel="" class="lb1"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer-block">
                        <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
                    </div>
                </form>
            </div>
            <div class="tabs__pane flex-block">
                <form action="{{ route('NoticeSettingsAdmin') }}" method="POST" class="flex">
                    @csrf
                    <div class="padding-20">
                        <div class="row">
                            <div class="field">
                                <h3>@lang('proxies::phrases.Уведомления')</h3>
                            </div>
                        </div>
                        <div class="row group">
                            <div class="row">
                                <div class="field">
                                    <div class="wrap-input title">
                                        <div class="title-field">Telegram:</div>
                                        <div class="wrap-buttons">
                                            @if ($settingNotice->telegram_token)
                                                <a href="https://api.telegram.org/bot{{ $settingNotice->telegram_token }}/setWebhook?url=https://{!! $_SERVER['HTTP_HOST'] !!}/telegram/webhook"
                                                    class="btn btn-primary"
                                                    style="position: relative;top: -9px;">@lang('proxies::phrases.Активировать бота')</a>
                                            @endif
                                            <input type="checkbox" id="telegram" name="telegram_check"
                                                {{ $settingNotice->telegram_check == 1 ? 'checked' : '' }}>
                                            <label for="telegram" data-onlabel="" data-offlabel=""
                                                class="lb1"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="text" placeholder="@lang('proxies::phrases.Токен бота')" name="telegram_token"
                                        class="input-text" value="{{ $settingNotice->telegram_token }}">
                                </div>
                                <div class="field">
                                    <input type="text" placeholder="@lang('proxies::phrases.Ссылка на бота')" name="telegram_link"
                                        class="input-text" value="{{ $settingNotice->telegram_link }}">
                                </div>
                            </div>
                        </div>
                        <div class="row group">
                            <div class="row">
                                <div class="field">
                                    <div class="wrap-input title">
                                        <div class="title-field">@lang('proxies::phrases.Сторонняя почта'):</div>
                                        <input type="checkbox" id="third_email" name="third_email"
                                            {{ $settingNotice->third_email == 1 ? 'checked' : '' }}>
                                        <label for="third_email" data-onlabel="" data-offlabel=""
                                            class="lb1"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="text" placeholder="Host" name="third_email_host" class="input-text"
                                        value="{{ $settingNotice->third_email_host }}">
                                </div>
                                <div class="field">
                                    <input type="text" placeholder="Port" name="third_email_port" class="input-text"
                                        value="{{ $settingNotice->third_email_port }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="text" placeholder="Username" name="third_email_username"
                                        class="input-text" value="{{ $settingNotice->third_email_username }}">
                                </div>
                                <div class="field">
                                    <input type="text" placeholder="Password" name="third_email_password"
                                        class="input-text" value="{{ $settingNotice->third_email_password }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="text" placeholder="Encryption" name="third_email_encryption"
                                        class="input-text" value="{{ $settingNotice->third_email_encryption }}">
                                </div>
                                <div class="field">
                                    <input type="text" placeholder="From address" name="third_email_address"
                                        class="input-text" value="{{ $settingNotice->third_email_address }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer-block">
                        <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
                    </div>
                </form>
            </div>
            <div class="tabs__pane flex-block">
                <form action="{{ route('referallSettingsSiteSave') }}" method="POST" class="flex" enctype="multipart/form-data">
                    @csrf
                    <div class="padding-20">
                        <div class="row">
                            <div class="field">
                                <h3>@lang('proxies::phrases.Реферальная система')</h3>
                            </div>
                        </div>
                        <div class="row group">
                            <div class="row">
                                <div class="field">
                                    <div class="wrap-input title">
                                        <div class="title-field">@lang('proxies::phrases.Процент от пополнения'):</div>
                                    </div>
                                    <input type="number" placeholder="0" name="deposit_percentage" class="input-text"
                                        value="{{ $siteSettingModel->deposit_percentage }}">
                                </div>
                                <div class="field">
                                    <div class="wrap-input title">
                                        <div class="title-field">@lang('proxies::phrases.Минимальная сумма вывода'):</div>
                                    </div>
                                    <input type="number" placeholder="0" name="minimum_withdrawal_amount"
                                        class="input-text" value="{{ $siteSettingModel->minimum_withdrawal_amount }}">
                                </div>
                            </div>
                        </div>

                        <div class="row group">
                            <div class="row">
                                <div class="field">
                                    <div class="wrap-input title">
                                        <div class="title-field">@lang('proxies::phrases.Рекламные материалы'):</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <input type="file" name="promotional_materials" 
                                        class="input-text" value="{{ $siteSettingModel->promotional_materials }}">
                                </div>
                            </div>
                        </div>

                        <div class="row group">
                            <div class="row">
                                <div class="field">
                                    <div class="wrap-input title">
                                        <div class="title-field">@lang('proxies::phrases.Карта'):</div>
                                        <input type="checkbox" id="card" name="card_output"
                                            {{ $siteSettingModel->card_output == 1 ? 'checked' : '' }}>
                                        <label for="card" data-onlabel="" data-offlabel="" class="lb1"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="wrap-input title">
                                    <div class="title-field">@lang('proxies::phrases.Электронный кошелёк'):</div>
                                    <input type="checkbox" id="ecash" name="ecash_output"
                                        {{ $siteSettingModel->ecash_output == 1 ? 'checked' : '' }}>
                                    <label for="ecash" data-onlabel="" data-offlabel="" class="lb1"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="wrap-input title">
                                    <div class="title-field">usdt_trc_20_output:</div>
                                    <input type="checkbox" id="usdt_trc" name="usdt_trc_20_output"
                                        {{ $siteSettingModel->usdt_trc_20_output == 1 ? 'checked' : '' }}>
                                    <label for="usdt_trc" data-onlabel="" data-offlabel="" class="lb1"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="wrap-input title">
                                    <div class="title-field">@lang('proxies::phrases.Электронный кошелёк'):</div>
                                    <input type="checkbox" id="capitalist" name="capitalist_output"
                                        {{ $siteSettingModel->capitalist_output == 1 ? 'checked' : '' }}>
                                    <label for="capitalist" data-onlabel="" data-offlabel="" class="lb1"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="wrap-input title">
                                    <div class="title-field">@lang('proxies::phrases.Раздельный баланс'):</div>
                                    <input type="checkbox" id="balance_enebled" name="referral_balance_enabled"
                                        {{ $siteSettingModel->referral_balance_enabled == 1 ? 'checked' : '' }}>
                                    <label for="balance_enebled" data-onlabel="" data-offlabel=""
                                        class="lb1"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer-block">
                        <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Сохранить')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="indent"></div>

    <div class="block-background" id="update" style="display: none;">
        <h2>Обновление системы</h2>
        <div class="row">
            <div class="field">
                <div class="title-field">Текущая версия: <span id="current-version">Загрузка...</span></div>
                <div class="title-field">Доступная версия: <span id="latest-version">Загрузка...</span></div>
                <div class="title-field">Список изменений:</div>
                <div class="wrap-input">
                    <ul class="list" id="changelog-list">
                        <li>Загрузка информации об обновлениях...</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="field">
                <div class="btn btn-primary" id="update-button">Обновить</div>
            </div>
            <div class="field">
                <div id="update-status" style="display: none; margin-top: 10px;"></div>
            </div>
        </div>
    </div>

    <style>
        #update {
            margin-top: 20px;
        }
        #update .title-field {
            font-weight: bold;
            margin-bottom: 10px;
        }
        #update .list {
            padding-left: 20px;
            margin-bottom: 15px;
        }
        #update .list li {
            margin-bottom: 5px;
        }
        #update-status {
            color: #557dfc;
            font-weight: bold;
        }
    </style>
@endsection
@section('script')
    <script src="/vendor/ssda-1/proxies/admin/js/tabs.js{{ '?' . time() }}"></script>
    <script>
        new ItcTabs('.settings-tabs', {}, 'settings-tabs');
        
        document.addEventListener('DOMContentLoaded', function() {
            checkForUpdates();
        });
        
        function checkForUpdates() {
            fetch('{{ route("check-updates") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.hasUpdate) {
                        // Показываем блок обновлений
                        document.getElementById('update').style.display = 'block';
                        
                        // Обновляем информацию о версиях
                        document.getElementById('current-version').textContent = data.currentVersion || 'Не определена';
                        document.getElementById('latest-version').textContent = data.latestVersion;
                        
                        // Парсим и отображаем список изменений из Markdown
                        const changelogList = document.getElementById('changelog-list');
                        changelogList.innerHTML = '';
                        
                        if (data.releaseNotes) {
                            // Простой парсер для маркированного списка в Markdown
                            const changes = data.releaseNotes.split('\n');
                            changes.forEach(change => {
                                const trimmedChange = change.trim();
                                if (trimmedChange.startsWith('- ') || trimmedChange.startsWith('* ')) {
                                    const li = document.createElement('li');
                                    li.textContent = trimmedChange.substring(2);
                                    changelogList.appendChild(li);
                                } else if (trimmedChange) {
                                    const li = document.createElement('li');
                                    li.textContent = trimmedChange;
                                    changelogList.appendChild(li);
                                }
                            });
                        } else {
                            const li = document.createElement('li');
                            li.textContent = 'Нет информации о изменениях';
                            changelogList.appendChild(li);
                        }
                    }
                })
                .catch(error => {
                    console.error('Ошибка при проверке обновлений:', error);
                });
        }
        
        // Обработчик нажатия на кнопку обновления
        document.getElementById('update-button').addEventListener('click', function() {
            if (confirm('Вы уверены, что хотите обновить систему? Это может занять некоторое время.')) {
                this.disabled = true;
                this.textContent = 'Обновление...';
                
                const updateStatus = document.getElementById('update-status');
                updateStatus.style.display = 'block';
                updateStatus.textContent = 'Запуск процесса обновления...';
                
                fetch('{{ route("update-system") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateStatus.textContent = 'Обновление запущено. Проверка статуса...';
                        
                        let checkCount = 0;
                        const maxChecks = 60; // Максимальное количество проверок (5 минут при интервале 5 секунд)
                        
                        // Периодически проверяем статус обновления
                        const checkInterval = setInterval(function() {
                            checkCount++;
                            
                            if (checkCount > maxChecks) {
                                clearInterval(checkInterval);
                                updateStatus.textContent = 'Превышено время ожидания. Обновление могло завершиться, но не удается получить статус.';
                                document.getElementById('update-button').disabled = false;
                                document.getElementById('update-button').textContent = 'Обновить';
                                return;
                            }
                            
                            updateStatus.textContent = `Проверка статуса обновления... (${checkCount}/${maxChecks})`;
                            
                            fetch('{{ route("update-status") }}?' + new Date().getTime())
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Ошибка сервера');
                                    }
                                    return response.json();
                                })
                                .then(statusData => {
                                    let statusText = statusData.message || 'Обновление в процессе...';
                                    
                                    if (statusData.progress) {
                                        statusText += ' - ' + statusData.progress;
                                    }
                                    
                                    updateStatus.textContent = statusText;
                                    
                                    if (statusData.status === 'completed') {
                                        clearInterval(checkInterval);
                                        updateStatus.textContent = 'Обновление успешно завершено! Перезагрузка страницы...';
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 2000);
                                    } else if (statusData.status === 'failed' || statusData.status === 'error') {
                                        clearInterval(checkInterval);
                                        updateStatus.textContent = 'Ошибка при обновлении: ' + statusData.message;
                                        if (statusData.error) {
                                            console.error('Детали ошибки:', statusData.error);
                                        }
                                        document.getElementById('update-button').disabled = false;
                                        document.getElementById('update-button').textContent = 'Обновить';
                                    }
                                })
                                .catch(error => {
                                    console.error('Ошибка при проверке статуса:', error);
                                    // Продолжаем проверку даже при ошибках отдельных запросов
                                    updateStatus.textContent = 'Ошибка проверки статуса. Ожидание завершения...';
                                });
                        }, 5000); // Проверяем каждые 5 секунд
                    } else {
                        updateStatus.textContent = 'Ошибка при запуске обновления: ' + (data.error || 'Неизвестная ошибка');
                        document.getElementById('update-button').disabled = false;
                        document.getElementById('update-button').textContent = 'Обновить';
                    }
                })
                .catch(error => {
                    console.error('Ошибка при обновлении:', error);
                    updateStatus.textContent = 'Ошибка сервера при запуске обновления';
                    document.getElementById('update-button').disabled = false;
                    document.getElementById('update-button').textContent = 'Обновить';
                });
            }
        });
    </script>
@endsection
