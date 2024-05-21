@extends('proxies::templates.'. (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.app')

@section('style')
    <link rel="stylesheet" href="/vendor/ssda-1/proxies/assets/css/lk.css{{ '?' . time() }}">
@endsection
@section('body-class')
personal-area
@endsection

@section('content')
<style>
    .wrap-support {
        margin: 50px 0 50px;
        display: flex;
        width: var(--width-1200);
    }
    .wrap-title {width: var(--width-1200); display: flex;}
    .wrap-support .bodyDialog {
        height: calc(100vh - 286px);
        width: 70%;
        border: 2px solid #3a4763;
        border-radius: 5px;
        display: flex;
        flex-direction: column;
        max-height: 600px;
    }
    .wrap-support .bodyDialog .topLine {padding: 15px;border-bottom: 1px solid #3a4763; flex-direction: row;color: #3a4763; justify-content: space-between; display: flex;}
    .wrap-support .bodyDialog .midleLine {flex: 1 auto;}
    .wrap-support .bodyDialog .midleLine .bubble {padding: 10px; display: flex; flex-direction: column; position: relative;}
    .wrap-support .bodyDialog .midleLine .bubble:after {position: absolute; content: ' '; top: 38px; width: 0; height: 0;}
    .wrap-support .bodyDialog .midleLine .bubble .time {font-size:14px;}
    .wrap-support .bodyDialog .midleLine .bubble .name {padding-right: 10px; padding-bottom: 3px; color: #3546be; font-weight: 600;}
    .wrap-support .bodyDialog .midleLine .bubble .massageBody {max-width: 50%; border-radius: 5px; margin-bottom: 5px; word-break: break-all; position: relative;}
    .wrap-support .bodyDialog .midleLine .bubble.thisUser .time {text-align: end;}
    .wrap-support .bodyDialog .midleLine .bubble.thisUser {align-items: flex-end;}
    .wrap-support .bodyDialog .midleLine .bubble.thisUser:after {right: 3px; border-top: 5px solid transparent; border-bottom: 5px solid transparent; border-left: 7px solid #3546bebd;}
    .wrap-support .bodyDialog .midleLine .bubble.appUser {align-items: flex-start;}
    .wrap-support .bodyDialog .midleLine .bubble.appUser .name {padding-left: 10px;padding-right: 0px;}
    .wrap-support .bodyDialog .midleLine .bubble.appUser:after {left: 3px; border-top: 5px solid transparent; border-bottom: 5px solid transparent; border-right: 7px solid #f7f7f7;}
    .wrap-support .bodyDialog .midleLine .bubble.thisUser .massageBody {background-color: #3546bebd; padding: 10px 10px 10px 20px; color: #fff;}
    .wrap-support .bodyDialog .midleLine .bubble.appUser .massageBody {background-color: #f7f7f7; padding: 10px 20px 10px 10px;}
    .wrap-support .bodyDialog .bottomLine {border-top: 1px solid #3a4763;padding: 10px; display: flex;justify-content: space-between;}
    .wrap-support .bodyDialog .bottomLine .send {border: none;width: 24px; height: 24px; background: url(/assets/img/send.png) no-repeat center center; background-size: contain; cursor: pointer; margin-left: 10px;}
    .wrap-support .bodyDialog .bottomLine div[contenteditable="true"] {outline: none;width: 100%;}
    .wrap-support .bodyDialog .bottomLine.close {justify-content: center; color: #b3b2b2; display:none;}
    .wrap-support .bodyDialog .bottomLine .sendTextarea {display: none;}
    .wrap-support .pastAppeals {padding-right: 20px; display: flex; flex-direction: column; justify-content: space-between; width: 30%;}
    .wrap-support .pastAppeals h2 {margin-bottom:10px; font-size: 26px;}
    .wrap-support .pastAppeals .cardAppeals {padding: 10px 20px; padding-bottom: 5px; padding-left: 10px; background-color: #f7f7f7a8; margin-bottom: 5px; cursor: pointer; border-radius: 5px;}
    .wrap-support .pastAppeals .cardAppeals.action {background-color: #ebebf9; border: 1px solid #3546be;}
    .wrap-support .pastAppeals .cardAppeals h3 {display: flex; justify-content: space-between; padding-bottom: 5px; margin-bottom: 10px; align-items: flex-end; border-bottom: 1px solid #bfbfbf;}
    .wrap-support .pastAppeals .cardAppeals h3 span {font-size: 14px;}
    .wrap-support .btn:hover {background-color: #DA5583; color: #fff;}
    #dialog_btn a {color: #fff;text-decoration: none}
    .wrap-support .pastAppeals .warp-Appeals {max-height: 530px; width: 100%; overflow: auto; margin-bottom: 5px; padding-right: 10px;}
    #element::-webkit-scrollbar, #messBody::-webkit-scrollbar {width: 5px; background-color: #f9f9fd;}
    #element::-webkit-scrollbar-thumb, #messBody::-webkit-scrollbar-thumb  {border-radius: 10px; background-color: #3546be}
    #element::-webkit-scrollbar-track, #messBody::-webkit-scrollbar-track  {-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.2); border-radius: 10px; background-color: #f9f9fd;}
    @media (max-width: 450px) {
        .wrap-support {
        flex-direction: column;
        gap: 40px;
        }
        .wrap-support .pastAppeals {
        width: 100%;
        min-height: 300px;
        padding: 0;
        }
        .wrap-support .pastAppeals .warp-Appeals {
        padding-right: 0;
        max-height: 250px;
        }
        .wrap-support .bodyDialog {
        width: 100%;
        }}
</style>
<div class="lk-block">
    @include('proxies::admin.lk.menu')
    <div class="lk-content">
        <div class="wrap-title">
            <h1>@lang('proxies::phrases.Техническая поддержка')</h1>
        </div>
        <div class="wrap-support">
            <div class="pastAppeals">
                <div class="warp-Appeals" id="element">
                    <h2>@lang('proxies::phrases.Прошлые обращения')</h2>
                    {{-- {{print($supportsList)}} --}}
                    @foreach ($supportsList as $item)
                        <div class="cardAppeals ">
                            <a href="/support/{{$item->id}}" style="text-decoration: unset;"><h3>@lang('proxies::phrases.Обращение') #{{$item->id}} @if ($item->status == 0) <span style="color:green;">Открыт</span> @else <span style="color:red;">Закрыт</span> @endif<span>{{$item->lastsuppmassage ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->lastsuppmassage->updated_at)->format('d.m.Y') : '' }}</span></h3>
                            <div class="text">{{$item->lastsuppmassage ? $item->lastsuppmassage->massage : '' }}</div></a>
                        </div>
                    @endforeach

                    {{-- <div class="cardAppeals">  {{$id == $item->id ? 'action' : ''}}
                        <h3>Обращение #541 <span style="color:green;">Открыт</span><span>21.06.22</span></h3>
                        <div class="text">Аааа... Я не могу купить прокси, памагите...</div>
                    </div> --}}
                </div>
                <a href="/newsupport" class="main_btn btn" id="dialog_btn">@lang('proxies::phrases.Новое обращение')</a>
            </div>
            <div class="bodyDialog" id="bodyDialog">
                <div class="midleLine" style="display: flex;align-items: center;justify-content: center;">
                    <p>@lang('proxies::phrases.Выберите или создайте новое обращение')</p>
                </div>
                <div class="bottomLine close">
                    @lang('proxies::phrases.Обращение закрыто')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script>
let contenteditableDiv = document.querySelector('.sendContent'),
    textAreaElement = document.querySelector('.sendTextarea');
contenteditableDiv.addEventListener("input", function(){
    textAreaElement.value = contenteditableDiv.innerText;
});
</script>
@endsection
