@extends('admin.app')

@section('content')
    <div class="header-page">
        <div class="title-page">
            <h2>@lang('proxies::phrases.Управление пользователями')</h2>
        </div>
        <div class="buttons">
            {{-- <a class="btn btn-success" data-title="Вы точно хотите экспортировать прокси с системы Кракен?" data-fetch="yes" data-action="{{ route('exportUsers') }}" data-modal="exportusers"  style="background-color: #607cd5;margin-right: 15px;letter-spacing: 1.15px;font-size: 14px;padding: 10px 25px;display: flex;justify-content: center;align-items: center;" href="#"><span>Экспортировать с Кракена</span></a> --}}
            <a class="btn btn-success" href="{{ route('users.create') }}"> @lang('proxies::phrases.Создать нового пользователя')</a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="block-background">
        <div class="title-block">
            <h3>@lang('proxies::phrases.Список пользователей')</h3>
        </div>
        <div class="row"></div>

        <form id="search-form">
            <div class="row" style="justify-content: space-between">
                <input type="text" name="query" placeholder="@lang('proxies::phrases.Поиск пользователей')" class="input-text"
                    style="max-width: 90%">
                <button type="submit" class="btn btn-primary">@lang('proxies::phrases.Искать')</button>
            </div>
        </form>

        <table class="table table-bordered" id="user-table">
            <tr>
                <th>No</th>
                <th>@lang('proxies::phrases.Имя')</th>
                <th>Email</th>
                <th>@lang('proxies::phrases.Телеграмм')</th>
                <th>@lang('proxies::phrases.Баланс')</th>
                <th>@lang('proxies::phrases.Роль')</th>
                <th width="280px">@lang('proxies::phrases.Действие')</th>
            </tr>
            <tbody id="user-table-body">
                @foreach ($data as $key => $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><a
                                href="https://t.me/{{ str_replace('@', '', $user->telegram_name) }}">{{ $user->telegram_name }}</a>
                        </td>
                        <td>
                            @if ($user->balance == 0)
                                <div class="">0 $</div>
                            @else
                                <div class="">{{ $user->balance }} $</div>
                            @endif
                        </td>
                        <td>
                            @if (!empty($user->getRoleNames()))
                                @foreach ($user->getRoleNames() as $v)
                                    <label class="badge badge-success">{{ $v }}</label>
                                @endforeach
                            @endif
                        </td>
                        <td class="dayst">
                            <a class="btn btn-action" href="{{ route('users.show', $user->id) }}"><i
                                    class="fa-regular fa-eye"></i></a>
                            <a class="btn btn-action" href="{{ route('users.edit', $user->id) }}"><i
                                    class="fa-regular fa-pen-to-square"></i></a>
                            @can('user-delete')
                                <button type="submit"
                                    data-title="@lang('proxies::phrases.Вы точно хотите удалить Пользователя с сайта')? <br> Ps: @lang('proxies::phrases.В системе Кракен его нужно удалить вручную')"
                                    data-action="{{ route('users.destroy', $user->id) }}" data-modal="del"
                                    class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                            @endcan
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- {!! $data->render() !!} --}}
    {!! $data->links('vendor.pagination.default') !!}
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchForm = document.getElementById('search-form');
            var userTableBody = document.getElementById('user-table-body');

            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();

                var query = document.querySelector('input[name="query"]').value;

                fetch('/users/search?query=' + encodeURIComponent(query))
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        var results = data.users;
                        var html = '';

                        results.forEach(function(user) {
                            html += '<tr>';
                            html += '<td>' + user.id + '</td>';
                            html += '<td>' + user.name + '</td>';
                            html += '<td>' + user.email + '</td>';
                            var telegramName = user.telegram_name ? user.telegram_name.replace(
                                /@/g, '') : '';
                            html += '<td><a href="https://t.me/' + telegramName + '">' +
                                telegramName + '</a></td>';
                            html += '<td>' + user.balance + '</td>';
                            html += '<td></td>';
                            html += '<td class="dayst">\
                                            <a class="btn btn-action" href="/users/' + user.id + '/show/"><i class="fa-regular fa-eye"></i></a>\
                                            <a class="btn btn-action" href="/users/' + user.id +
                                '/edit/"><i class="fa-regular fa-pen-to-square"></i></a></td>';
                            html += '</tr>';
                        });

                        userTableBody.innerHTML = html;
                    })
                    .catch(function(error) {
                        console.error('Ошибка:', error);
                    });
            });
        });

        function htmlspecialchars(str) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };

            return str.replace(/[&<>"']/g, function(m) {
                return map[m];
            });
        }
    </script>
@endsection
