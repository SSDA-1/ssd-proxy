@extends('proxies::templates.'. (new Ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.app')

@section('content')
    <h1>@lang('proxies::phrases.У вас проблемы с оплатой')</h1>
@endsection
