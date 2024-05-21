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
                    <h3>Центр обучения</h3>
                </div>
                <div class="answers__body">
                    @foreach ($mainFaq as $faq)
                        <details class="answers__details">
                            <summary class="answers__summary">@lang('proxies::phrases.' . $faq->question)</summary>
                            <div class="answers__text">
                                <p>{!! trans('proxies::phrases.' . $faq->answer) !!}</p>
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
