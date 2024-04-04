@extends('templates.'. (new App\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.app')

@section('content')

<div class="wraper-page-block">
        {{-- <h1>Правили магазина и согласие клиента</h1> --}}
        <div class="content-page">
            {!! $rules->text !!}
        </div>
</div>

@endsection
