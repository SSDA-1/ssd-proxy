@extends('templates.'. (new App\Http\Controllers\TemplateController())->getUserTemplateDirectory() .'.layouts.app')

@section('content')

<div class="wraper-faq-block">
    <h1>@lang('phrases.САМЫЕ ЧАСТОЗАДАВАЕМЫЕ ВОПРОСЫ')</h1>
    <div class="faq-block">

        @php
            $i = 0;
        @endphp
        <div class="left-faq-block">
            @foreach ($faqs as $faq)
                @if($i == 0)
                    <div class="FAQ-tab-item active" data-tab="{{ $i++ }}">
                @else
                    <div class="FAQ-tab-item" data-tab="{{ $i++ }}">
                @endif
                    {{ $faq->question }}
                    <div class="FAQ-tab-item-arrow">
                        <i class="fa fa-angle-down"></i>
                    </div>
                </div>
            @endforeach
        </div>
        @php
            $i = 0;
        @endphp
        <div class="right-faq-block">
            @foreach ($faqs as $faq)
            @if($i == 0)
                <div class="FAQ-tabs-wrap sticky is-sticky active" data-tab-content="{{ $i++ }}">
            @else
                <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="{{ $i++ }}">
            @endif
                    {!! $faq->answer !!}
                </div>
            @endforeach
        </div>


        {{-- <div class="left-faq-block">
            <div class="FAQ-tab-item active" data-tab="1">
                Я оплатил, как скоро я получу прокси?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="2">
                Как совершить настройку прокси?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="3">
                Прокси продаются в одни руки?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="4">
                Эти прокси использовались до меня?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="5">
                Как часто вы обновляете ваши прокси?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="6">
                Скидки на срок оплаты и количество прокси суммируются?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="7">
                Если я оплачиваю прокси на несколько месяцев, могу ли я менять их?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="8">
                Могу ли я заменить прокси в течении оплаченного периода?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="9">
                Каким способом я могу совершить оплату?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="10">
                Смогу ли я оплатить уже используемые адреса на дополнительный срок?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="11">
                Как продлить прокси?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="12">
                На сайте предоставлено два вида прокси, в чем разница между ними? Какие подойдут для
                меня?

                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="13">
                Зачем нужны прокси?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="14">
                В чем принципиальная разница между вашим прокси сервисом и другими подобными?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="15">
                Сколько занимает выдача прокси после оплаты?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="16">
                Есть ли у вас партнерская программа?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="17">
                25 порт открыт?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="18">
                Могу ли я получить прокси из разных сетей/подсетей?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="19">
                Как понять, из скольких подсетей мне выдали прокси?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="20">
                Сколько параллельных потоков/подключений можно использовать с ваших прокси?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="21">
                Как происходит авторизация прокси?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="22">
                Какие протоколы поддерживают ваши прокси?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="23">
                Есть ли у вас правила и запреты на использование ваших прокси и сервиса?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
            <div class="FAQ-tab-item" data-tab="24">
                Что делать, если прокси не работает?
                <div class="FAQ-tab-item-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </div>
        </div>
        <div class="right-faq-block">
            <div class="FAQ-tabs-wrap sticky is-sticky active" data-tab-content="1">
                <p>Мы знаем как время дорого для вас, поэтому стараемся выдать прокси моментально. Но, для того,
                    чтобы подобрать максимально подходящие адреса для вашей цели, требуется немного времени.
                    Если прокси не были получены в течение 30 минут, обратитесь, пожалуйста, к консультанту на
                    сайте</p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="2">
                <p>Мы позаботились о том, чтобы работа с прокси была максимально простой для Вас, поэтому
                    подготовили все основные инструкции по настройке в нашем блоге.

                    Обратите внимание, что в настройке прокси в самописной программе мы помочь не сможем!</p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="3">
                <p>На нашем сайте предоставлены исключительно индивидуальные прокси. Используя их вы можете не
                    беспокоиться о действиях "соседа", которые могут привести к плачевным последствиям</p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="4">
                <p>К сожалению, на этот вопрос мы не можем дать ответ, т.к ни мы, не провайдер, у которого
                    арендуются IP адреса не ведем статистики использования ип адресов.</p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="5">
                <p>Мы стараемся для наших клиентов обновлять как можно чаще список наших прокси. Это относится
                    как к количеству подсетей, так и к списку стран. Сейчас уже можем предоставить Вам прокси из
                    53 стран и около 800 подсетей</p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="6">
                <p>Да. Например при покупке 100 прокси на 12 месяцев Вы получится 27% скидки. 15% будет скидка
                    за объем и 12% скидка на срок покупки. </p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="7">
                <p>Мы ценим клиентов, которые остаются с нами. Поэтому помимо скидок, предоставленных при оплате
                    прокси на срок более месяца, вы получите возможность замены адресов раз в месяц по запросу
                </p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="8">
                <p>Мы предоставляем качественные прокси, которые подбираются под вашу цель, но если вдруг вам по
                    каким-то причинам не подошел полученный адрес - обратитесь к он-лайн консультанту. В течение
                    первых суток мы гарантируем замену прокси, а в течение всего последующего времени - полную
                    техническую поддержку</p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="9">
                <p>Наш сайт поддерживает все популярные системы оплат: WebMoney, QIWI, Yandex, MasterCard, VISA,
                    Сбербанк Онлайн, Альфа-клик, Privat24, BitCoin, PerfectMoney и мобильные платежи: МТС,
                    Билайн.</p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="10">
                <p>Да, конечно. Продлить прокси вы можете в <a href="">личном
                        кабинете</a> . Мы ценим ваше
                    доверие, поэтому за оплату прокси на срок более месяца предоставляем скидки до 12%. </p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="11">
                <p>В <a href="">личном кабинете</a>, во вкладке "панель
                    управления" необходимо отметить галочками прокси, которые хотите продлить, выбрать срок,
                    способ оплаты и нажать "Продлить". После успешной оплаты срок действия прокси будет продлен
                </p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="12">
                <p>Действительно, мы предоставляем прокси IPv4 и IPv6. Подключение к IPv6 идет через адрес IPv4,
                    используя разные порты, из-за чего при выходе вы получаете уникальный прокси IPv6.
                    Также, не маловажным есть то, что IPv4, в отличии от нового протокола 6 версии подходят
                    практически для всех сайтов и программ. Проверить работоспособность IPv6 с вашим сайтом
                    можете <a href="" target="_blank">здесь</a>
                </p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="13">
                <p>Смена IP и DNS - Вы можете скрыть свой реальный IP адрес и DNS просто подключив прокси;
                    Анонимность в сети - Анонимное и безопасное использование интернета, скрытие интернет
                    активности от своего провайдера;
                    Обход блокировок - Снятие ограничений сервисов по IP, GEO данным, порту и протоколу. Ваш
                    интернет становится свободным;
                    Защита от хакеров - Атаки хакеров ложатся на наш прокси-сервер. Злоумышленники не смогут
                    узнать реальный IP.</p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="14">
                <p>Мы всегда стараемся улучшать наш сервис, помогать Вам в решении проблем или просто помочь с
                    выбором прокси. Конечно, у нас тоже бывают проблемы, но мы максимально быстро пытаемся их
                    решить, дабы наши клиенты всегда могли работать спокойно и легко, используя наши прокси</p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="15">
                <p>Выдача прокси происходит в автоматическом режиме сразу после оплаты.
                    В случае, если в вашем кабинете не отобразились прокси сразу - обратитесь в техподдержку.
                </p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="16">
                <p>Да, теперь мы готовы порадовать Вас не только качественными прокси, а и реферальной
                    программой, с помощью которой Вы, Ваши друзья и коллеги смогут быстро и легко заработать</p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="17">
                <p>Нет, 25 порт закрыт. Email рассылка запрещена.</p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="18">
                <p>Да, конечно. Мы всегда предоставляем прокси с максимально возможным разбросом подсетей. Если
                    какая-то вам не подойдет - мы сделаем замену!</p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="19">
                <p>На нашем счету не одна сотня прокси-серверов, и мы стараемся предоставить клиенту прокси с
                    максимально разными подсетями. Вы можете самостоятельно убедится в этом. Прокси делится на 2
                    блока хх.хх.55.55 – где хх.хх. это сеть, а 55.55 это уже подсеть. Если какая-то подсеть не
                    подойдет для Вашей цели - напишите оператору и мы сделаем замену</p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="20">
                <p>Наши прокси максимально стабильны при работе с различными целями. На данный момент для многих
                    очень важно использовать прокси на нескольких устройствах. Мы ни в коем случае не
                    ограничиваем наших пользователей в этом, но создание на одном прокси большого количества
                    потоков приведет к снижению скорости. Исходя из этого, для идеальной работы с прокси мы
                    рекомендуем использовать его не более чем на 3х устройствах</p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="21">
                <p>Так как наши прокси предоставляются только в одни руки, для его использования нужно пройти
                    авторизацию. Мы даем на выбор два способа:

                    по логину и паролю - эти данные необходимо будет ввести при настройке и по IP - прокси будет
                    работать на вашем пк без ввода дополнительных данных</p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="22">
                <p>Наши прокси поддерживают все необходимые протоколы для работы: HTTP, HTTPS, SOCKS5, данные по
                    которым будут предоставлены вам после оплаты</p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="23">
                <p>К сожалению не все пользователи добросовестные, поэтому чтобы предоставить Вам максимально
                    качественный незаспамленный прокси - мы установили ограничения, с которыми Вы можете
                    ознакомится <a href="">здесь</a></p>
            </div>
            <div class="FAQ-tabs-wrap sticky is-sticky" data-tab-content="24">
                <p>Прокси тоже имеет свойство "ломаться", мы стараемся не допускать подобного, но если вы
                    все-таки обнаружили проблему - обратитесь к консультанту на сайте. Мы в сети 24/7</p>
            </div>

        </div> --}}
    </div>
</div>

@endsection
@section('script')
<script>
var tabNavs = document.querySelectorAll(".FAQ-tab-item");
var tabPanes = document.querySelectorAll(".FAQ-tabs-wrap");

for (var i = 0; i < tabNavs.length; i++) {

    tabNavs[i].addEventListener("click", function(e) {
        e.preventDefault();
        var activeTabAttr = e.target.getAttribute("data-tab");

        for (var j = 0; j < tabNavs.length; j++) {
            var contentAttr = tabPanes[j].getAttribute("data-tab-content");

            if (activeTabAttr === contentAttr) {
                tabNavs[j].classList.add("active");
                tabPanes[j].classList.add("active");
            } else {
                tabNavs[j].classList.remove("active");
                tabPanes[j].classList.remove("active");
            }
        };
    });
}
</script>
@endsection
