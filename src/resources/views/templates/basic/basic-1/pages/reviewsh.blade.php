@extends('templates.'. (new App\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.app')

@section('content')
<div class="wraper-reviews-block">
    <h1>@lang('phrases.Отзывы о нас')</h1>
    <div class="wraper-reviews-section">
        <div class="card-reviews">
            <div class="img-reviews">
                <img src="assets/img/1.jpg" alt="">
            </div>
            <div class="content-reviews">
                <p>@lang('phrases.Приобретались 10 прокси для работы с соц.сетями, для теста взяли сначала на 1 неделю')</p>
                <div class="signature">
                    <span>Sasha Lazarenko</span>
                    <a href="">facebook</a>
                </div>
            </div>
        </div>
        <div class="card-reviews">
            <div class="img-reviews">
                <img src="assets/img/1.jpg" alt="">
            </div>
            <div class="content-reviews">
                <p>@lang('phrases.Приобретались 10 прокси для работы с соц.сетями, для теста взяли сначала на 1 неделю')/p>
                <div class="signature">
                    <span>Sasha Lazarenko</span>
                    <a href="">facebook</a>
                </div>
            </div>
        </div>
        <div class="card-reviews">
            <div class="img-reviews">
                <img src="assets/img/1.jpg" alt="">
            </div>
            <div class="content-reviews">
                <p>@lang('phrases.Приобретались 10 прокси для работы с соц.сетями, для теста взяли сначала на 1 неделю')</p>
                <div class="signature">
                    <span>Sasha Lazarenko</span>
                    <a href="">facebook</a>
                </div>
            </div>
        </div>
        <div class="card-reviews">
            <div class="img-reviews">
                <img src="assets/img/1.jpg" alt="">
            </div>
            <div class="content-reviews">
                <p>@lang('phrases.Приобретались 10 прокси для работы с соц.сетями, для теста взяли сначала на 1 неделю')</p>
                <div class="signature">
                    <span>Sasha Lazarenko</span>
                    <a href="">facebook</a>
                </div>
            </div>
        </div>
        <div class="card-reviews">
            <div class="img-reviews">
                <img src="assets/img/1.jpg" alt="">
            </div>
            <div class="content-reviews">
                <p>@lang('phrases.Приобретались 10 прокси для работы с соц.сетями, для теста взяли сначала на 1 неделю')</p>
                <div class="signature">
                    <span>Sasha Lazarenko</span>
                    <a href="">facebook</a>
                </div>
            </div>
        </div>
        <div class="card-reviews">
            <div class="img-reviews">
                <img src="assets/img/1.jpg" alt="">
            </div>
            <div class="content-reviews">
                <p>@lang('phrases.Приобретались 10 прокси для работы с соц.сетями, для теста взяли сначала на 1 неделю')</p>
                <div class="signature">
                    <span>Sasha Lazarenko</span>
                    <a href="">facebook</a>
                </div>
            </div>
        </div>
        <div class="card-reviews">
            <div class="img-reviews">
                <img src="assets/img/1.jpg" alt="">
            </div>
            <div class="content-reviews">
                <p>@lang('phrases.Приобретались 10 прокси для работы с соц.сетями, для теста взяли сначала на 1 неделю')</p>
                <div class="signature">
                    <span>Sasha Lazarenko</span>
                    <a href="">facebook</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
