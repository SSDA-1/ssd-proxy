@extends('proxies::templates.'. (new ssda1\proxies\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.app')

@section('content')
    <h1>@lang('phrases.У вас проблемы с оплатой')</h1>
@endsection
