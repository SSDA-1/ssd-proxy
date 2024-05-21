@extends('proxies::admin.app')

@section('content')

<div class="header-page">
    <div class="page-title">
        <div class="pull-left">
            <h2>@lang('proxies::phrases.Техническая поддержка')</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('roles.index') }}">@lang('proxies::phrases.Написать')</a>
        </div>
    </div>
</div>
@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong>@lang('proxies::phrases.Упс')!</strong> @lang('proxies::phrases.Были некоторые проблемы с вашим вводом').<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="content-block">
    <table class="table table-bordered">
        <thead>
            <tr class="tr-name">
                <th>No</th>
                <th>@lang('proxies::phrases.Тема')</th>
                <th>@lang('proxies::phrases.Дата создания')</th>
                <th>@lang('proxies::phrases.Дата изменения')</th>
                <th>@lang('proxies::phrases.Статус')</th>
                <th>@lang('proxies::phrases.Действие')</th>
            </tr>
        </thead>
        <tbody>

                    <tr>
                        <td colspan="6" style="text-align: center">@lang('proxies::phrases.Записи отсутствуют')</td>
                        {{-- <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="dayst">
                            <form action="" method="POST">
                                <a class="btn btn-info" href=""><i
                                        class="fa-regular fa-eye"></i></a>
                                <a class="btn btn-primary" href=""><i
                                        class="fa-regular fa-pen-to-square"></i></a>


                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td> --}}
                    </tr>
                
        </tbody>
    </table>
</div>

@endsection