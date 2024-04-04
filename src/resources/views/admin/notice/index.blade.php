@extends('admin.app')

@section('summernote')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('phrases.Настройка уведомлений')</h2>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="tabs">
        <div class="tabs__nav background">
            <button class="tabs__btn tabs__btn_active first">@lang('phrases.Уведомления')</button>
            <button class="tabs__btn">@lang('phrases.Рекламные уведомления')</button>
        </div>
        <div class="tabs__content block-background">
            <div class="tabs__pane tabs__pane_show flex-block">
                <form action="{{ route('allSettingsSiteSave') }}" method="POST" class="flex">
                    @csrf
                    <div class="padding-20">
                        <div class="row">
                            <div class="field">
                                <h3>@lang('phrases.Уведомления')</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">
                                    @lang('phrases.В этом разделе вы можете настроить текст сообщения которое будет отправлятся в различных').
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">@lang('phrases.Сообщение при блокировке прокси (вслучае не оплаты)'):</div>
                                <textarea name="detail" class="select-multiple summernote"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">@lang('phrases.Сообщение при удалении прокси (вслучае не оплаты)'):</div>
                                <textarea name="detail" class="select-multiple summernote"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">@lang('phrases.Напоминание об оплате'):</div>
                                <textarea name="detail" class="select-multiple summernote"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">@lang('phrases.Сообщение при покупке прокси'):</div>
                                <textarea name="detail" class="select-multiple summernote"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="footer-block">
                        <button type="submit" class="btn btn-primary">@lang('phrases.Сохранить')</button>
                    </div>
                </form>
            </div>
            <div class="tabs__pane flex-block">
                <form action="{{ route('ceoSettingsSiteSave') }}" method="POST">
                    @csrf
                    <div class="padding-20">
                        <div class="row">
                            <div class="field">
                                <h3>@lang('phrases.Рекламные письма')</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">
                                    @lang('phrases.В этом разделе вы можете создать/отредактировать реклавное сообщение').
                                </div>
                            </div>
                        </div>
                        <div class="row group">
                            <div class="row">
                                <div class="field">
                                    <div class="wrap-input title">
                                        <div class="title-field">@lang('phrases.Отправить сообщение в Telegram')?</div>
                                        <input type="checkbox" id="telegram" name="telegram_check" {{-- {{ $siteSettingModel->qiwi_pay == 1 ? 'checked' : '' }} --}}>
                                        <label for="telegram" data-onlabel="" data-offlabel="" class="lb1"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <div class="title-field">@lang('phrases.Сообщение'):</div>
                                    <textarea name="detail" class="select-multiple summernote"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <div class="title-field">
                                    @lang('phrases.Дата и время рассылки')
                                </div>
                                <input type="datetime-local" name="" id="" class="input-text">
                            </div>
                            <div class="field">
                                <div class="title-field">
                                    @lang('phrases.Дата последней рассылки')
                                </div>
                                <div class="input-text">14.03.23 @lang('phrases.ИЛИ Рассылок еще не было')</div>
                            </div>
                        </div>
                    </div>
                    <div class="footer-block not-radius">
                        <button type="submit" class="btn btn-primary">@lang('phrases.Сохранить')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="indent"></div>
@endsection

@section('script')
    <script src="{{ asset('admin/js/tabs.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                placeholder: '@lang('phrases.Текст сообщения')',
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']]
                ]
            });
        });
    </script>
@endsection
