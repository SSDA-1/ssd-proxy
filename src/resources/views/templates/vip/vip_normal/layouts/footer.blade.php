        <!-- Подвал -->
        <div class="footer">
            <div class="wrapper-footer">
                <div class="logo">
                    <a href="/">SSD PROXY</a>
                </div>
                {{--{{dd($settingsData)}}--}}
                {{-- <div class="footer-block-1">
                    <div class="footer__title">Товары:</div>
                    <nav>
                        <ul>
                            <li id="footer-dropdown">
                                <a href="#">IPv4 прокси</a>
                            </li>
                            <li>
                                <a href="#">IPv6 прокси</a>
                            </li>
                            <li>
                                <a href="#">Мобильные прокси</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="footer-block-2">
                    <div class="footer__title">Компания:</div>
                    <nav>
                        <ul>
                            <li>
                                <a href="reviews.html">Отзывы</a>
                            </li>
                            <li>
                                <a href="faq.html">FAQ</a>
                            </li>
                            <li>
                                <a href="rules.html">Оферта</a>
                            </li>
                            <li>
                                <a href="rules.html">Политика
                                    конфиденциальности</a>
                            </li>
                            <li>
                                <a href="rules.html">Условия</a>
                            </li>
                        </ul>
                    </nav>
                </div> --}}
                <div class="wrapper-footer-block">
                <div class="footer__title">Навигация:</div>
                    <nav class="foot-menu">
                        <ul>
                            @foreach ($menusSite as $menu2)
                                @if($menu2->type_menu == 2)
                                    <li><a href="{{ $menu2->link }}">{{ $menu2->name }}</a></li>
                                @endif
                            @endforeach
                        </ul>
                    </nav>
                </div>
                <div class="wrapper-footer-block">
                    <div class="row">
                        {{-- <div class="footer-block-3">
                            <div class="footer__title">Клиент-центр:</div>
                            <nav>
                                <ul>
                                    <li>
                                        <a href="lk.html">Личный
                                            кабинет</a>
                                    </li>
                                    <li>
                                        <a href="">Партнерский
                                            кабинет</a>
                                    </li>
                                </ul>
                            </nav>
                        </div> --}}
                        <div class="footer-block-4">
                            <div class="footer__title">Контакты:</div>
                            <nav class="footer-social">
                                <ul>
                                    </li>
                                    @if ($settingsData->telegram)
                                        <li>
                                            <a href="tg://{{$settingsData->telegram}}" target="_blank">
                                                <i class="fa fa-paper-plane"></i>Телеграм
                                            </a>
                                        </li>
                                    @endif
                                    @if ($settingsData->email)
                                        <li>
                                            <a href="mailto:{{$settingsData->email}}">
                                                <i class="fa fa-envelope"></i>{{$settingsData->email}}
                                            </a>
                                        </li>
                                    @endif
                                    @if ($settingsData->skype)
                                        <li>
                                            <a href="{{$settingsData->skype}}">
                                                <i class="fa fa-comments"></i>WhatsApp
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="footer-block-5">
                        <nav class="footer-join">
                            <div class="footer__title">Присоединяйтесь:</div>
                            <ul>
                                {{--<li>
                                    <a href="https://vk.com/" target="_blank">
                                        <i class="fa fa-vk"></i> Vkontakte
                                    </a>
                                </li>
                                <li>
                                    <a href="https://t.me/" target="_blank">
                                        <i class="fa fa-paper-plane"></i> Telegram
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.facebook.com/" target="_blank">
                                        <i class="fa fa-facebook-square"></i> Facebook
                                    </a>
                                </li>--}}
                                @foreach ($menusSite as $menu3)
                                    @if($menu3->type_menu == 3)
                                        @if($menu3->name == 'vk')
                                        <li><a href="{{ $menu3->link }}"><i class="fa fa-vk"></i> Vkontakte</a></li>
                                        @elseif($menu3->name == 'facebook')
                                        <li><a href="{{ $menu3->link }}"><i class="fa fa-facebook"></i> Facebook</a></li>
                                        @elseif($menu3->name == 'telegram')
                                        <li><a href="{{ $menu3->link }}"><i class="fa fa-paper-plane"></i> Telegram</a></li>
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
