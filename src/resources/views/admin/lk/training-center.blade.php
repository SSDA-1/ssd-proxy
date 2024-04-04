@extends('templates.' . (new App\Http\Controllers\TemplateController())->getUserTemplateDirectory() . '.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/lk.css') }}{{ '?' . time() }}">
@endsection
@section('body-class')
personal-area
@endsection

@section('content')
    <div class="lk-block">
        @include('admin.lk.menu')
        <div class="lk-content">
            <div class="wrap-form form-payment">
                <div class="wrap-title">
                    <h3>Центр обучения</h3>
                </div>
                <div class="answers__body">
                    @foreach ($mainFaq as $faq)
                        <details class="answers__details">
                            <summary class="answers__summary">@lang('phrases.' . $faq->question)</summary>
                            <div class="answers__text">
                                <p>{!! trans('phrases.' . $faq->answer) !!}</p>
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
