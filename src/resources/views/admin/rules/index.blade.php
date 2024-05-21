@extends('proxies::admin.app')

@section('content')
<div class="header-page">
    <div class="title-page">
        <h2>@lang('proxies::phrases.Правила сайта')</h2>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<div class="block-background">
    <table class="table table-bordered">
        <thead>
            <tr class="tr-name">
                <th>No</th>
                <th>@lang('proxies::phrases.Текст')</th>
                <th>@lang('proxies::phrases.Действие')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rules as $rule)
            <tr>
                <td>{{ ++$i }}</td>
                <td>@if(strlen($rule->text)>40)
                    {!!  mb_substr( $rule->text, 0, 40 ).'...'  !!} 
                    @else
                    {!! $rule->text !!}
                    @endif
                </td>
                <td class="dayst">
                    <a class="btn btn-action" href="{{ route('rules.edit',$rule->id) }}"><i class="fa-regular fa-pen-to-square"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection