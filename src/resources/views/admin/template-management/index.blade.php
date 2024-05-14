@extends('admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Шаблоны')</h2>
        </div>
        {{-- <div class="buttons">
            <a class="btn btn-success" href="{{ route('create-template') }}"> Добавить Шаблон</a>
        </div> --}}
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="flex-block list">
        @foreach ($sortedTemplates as $template)
            <div class="block-background template">
                <div class="row">
                    <div class="field">
                        <div class="input-text cover-wrap">
                            <img src="" class="cover">
                            <div class="type">{{ $template->type }}</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <div class="template-name">{{ $template->name }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <div class="template-name">@lang('proxies::phrases.Стоимость'): {{ $template->cost }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        @if ($user_templates->contains($template->id))
                            @foreach ($user_templates as $temp)
                                @if ($temp->id == $template->id)
                                    @if ($temp->pivot->is_active == 1)
                                        <p class="btn btn-template stirring">@lang('proxies::phrases.Активен')</p>
                                    @else
                                        {!! Form::open([
                                            'method' => 'UPDATE',
                                            'data-fetch' => 'none',
                                            'route' => ['change-template', $template->id],
                                            'style' => 'display:inline',
                                        ]) !!}
                                        <button type="submit" class="btn btn-template stirring">@lang('proxies::phrases.Активировать')</button>
                                        {!! Form::close() !!}
                                    @endif
                                @endif
                            @endforeach
                        @else
                            <a href="{{ route('buy-template', $template->id) }}" class="btn btn-template">@lang('proxies::phrases.Купить')</a>
                        @endif
                        <a href="{{ route('show-template', $template->id) }}" class="btn btn-template">@lang('proxies::phrases.Подробнее')</a>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="indent"></div>

    {{-- <div class="block-background">
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Type</th>
                <th>Cost</th>
                <th>Status</th>
                <th width="280px">Действие</th>
            </tr>

            @foreach ($allTemplates as $template)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $template->name }}</td>
                    <td>{{ $template->type }}</td>
                    <td>{{ $template->cost }}</td>
                    <td>
                        @if ($user_templates->contains($template->id))
                            <p>У вас уже есть этот шаблон</p>
                            @foreach ($user_templates as $temp)
                                @if ($temp->id == $template->id)
                                    @if ($temp->pivot->is_active == 1)
                                        <p style="color: #36bb2c">Template is active</p>
                                    @else
                                        {!! Form::open([
                                            'method' => 'UPDATE',
                                            'data-fetch' => 'none',
                                            'route' => ['change-template', $template->id],
                                            'style' => 'display:inline',
                                        ]) !!}
                                        <button type="submit" class="btn btn-danger">Let's activate this template</button>
                                        {!! Form::close() !!}
                                    @endif
                                @endif
                            @endforeach
                        @else
                            <a href="{{ route('buy-template', $template->id) }}">Buy</a>
                        @endif
                        <a href="{{ route('show-template', $template->id) }}">Info</a>
                    </td>
                    <td class="dayst">
                        <a class="btn btn-action" href=""><i class="fa-regular fa-eye"></i></a>
                        <a class="btn btn-action" href=""><i
                                class="fa-regular fa-pen-to-square"></i></a>

                    </td>

                </tr>
            @endforeach
        </table>
    </div> --}}
@endsection
