<div class="modal">
    <div class="background">
        <div class="body bodyFirst">
            <div class="title">@lang('proxies::phrases.Вы точно хотите удалить это')?</div>
            <div style="padding: 15px;dsiplay: none;" id="modalDesc" class="block-background">
                <select id="selectServerExport" class="select-multiple"></select>
            </div>
            <div class="footer-block">
                <form action="" method="POST" id="modalForm" class="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delite">@lang('proxies::phrases.Удалить')</button>
                </form>
                <form action="" method="POST" id="modalFormExport">
                    @csrf
                    <input type="hidden" name="server" id="serverHiddenModal">
                    <button type="submit" class="btn btn-delite"
                        style="background-color: #557dfc;">@lang('proxies::phrases.Импортировать')</button>
                </form>
            </div>
        </div>
        <style>
            .modal .background .body.bodyEdit {
                max-width: 500px;
            }
        </style>
        <div class="body bodyEdit" style="display: none;">
            <div class="title">@lang('proxies::phrases.Вы точно хотите удалить это')?</div>
            <form action="{{route('addTimeProxy')}}" method="POST" data-fetch="yes" id="modalFormEdit">
                @csrf
                <div class="mass block-background">
                    <div class="row">
                        <div class="field">
                            <div class="title-field">@lang('proxies::phrases.Количество Дней'):</div>
                            <div class="wrap-input">
                                <input type="number" id="days" name="days" class="input-text" placeholder="1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="field">
                            <div class="title-field">@lang('proxies::phrases.Количество Времени'):</div>
                            <div class="wrap-input">
                                <input type="time" id="time" class="input-text" placeholder="0" name="time" value="00:00">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="proxis"></div>
                <div class="footer-block">
                    <button type="submit" class="btn btn-delite" style="background-color: #557dfc;">@lang('proxies::phrases.Сохранить')</button>
                </div>
            </form>
        </div>
    </div>
</div>
