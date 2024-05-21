@extends('proxies::admin.app')

@section('content')
<div class="header-page">
    <div class="title-page">
        <h2>FAQ</h2>
    </div>
    <div class="buttons">
        <a class="btn btn-success" href="{{ route('faq-adm.create') }}">@lang('proxies::phrases.Добавить вопрос')</a>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<div class="block-background">
    <div class="title-block">
        <h3>@lang('proxies::phrases.Список вопросов')</h3>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr class="tr-name">
                <th>No</th>
                <th>@lang('proxies::phrases.Вопрос')</th>
                <th>@lang('proxies::phrases.Ответ')</th>
                <th>@lang('proxies::phrases.Действие')</th>
            </tr>
        </thead>
        <tbody> 
            @if($faq->isNotEmpty())
            @foreach ($faq as $faqs)
            <tr>
                <td>{{ ++$i }}</td>
                <td>@if(strlen($faqs->question)>40)
                    {{  mb_substr( $faqs->question, 0, 40 ).'...'  }} 
                    @else
                    {{ $faqs->question }}
                    @endif
                </td>
                <td>@if(strlen($faqs->answer)>60)
                    {!!  mb_substr( $faqs->answer, 0, 50 ).'...'  !!} 
                    @else
                    {!! $faqs->answer !!} 
                    @endif
                </td>
                <td class="dayst">
                    <form action="{{ route('faq-adm.destroy',$faqs->id) }}" method="POST" data-fetch="none">
                        <a class="btn btn-action" href="{{ route('faq-adm.edit',$faqs->id) }}"><i class="fa-regular fa-pen-to-square"></i></a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
            @else
            <td colspan="4" class="absent">@lang('proxies::phrases.Записи отсутствуют')</td>
            @endif
        </tbody>
    </table>
</div>

{{-- {!! $faq->links() !!} --}}
{!! $faq->links('vendor.pagination.default') !!}

@endsection