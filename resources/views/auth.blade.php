<!-- Наш шаблон -->
@extends('layout')

@if(session('userInfo'))

@section('link')<a class="p-2 text-light" href="/users/profile/{{session('userInfo')->id}}">Личный кабинет</a>@endsection

<!-- Личный кабинет пациента -->
@if(session('userInfo')->role == "patient")

<!-- Название страницы, её title -->
@section('title')Личный кабинет пациента@endsection

@section('button')<a class="btn btn-danger" href="/visits/add">Записаться на прием</a>@endsection

@section('content')

<h2 class="text-center mb-3">Добро пожаловать в личный кабинет</h2>
<h4 class="text-center mb-3">Здравствуйте, {{ session('user')->name . " " . session('user')->patronymic }}.</h3>

<div class="container pt-2">
    <div class="row mb-4">
        <div class="col-md-4">
            Ваше состояние: {{ session('user')->status }}. <br> 
            Вы являетесь: пациентом.
        </div>
        <div class="col-md-4">
            Ваш социальный статус: {{ session('user')->class }}. <br>
            Контактный телефон: {{ session('user')->tel }}.
        </div>
        <div class="col-md-4">
            Адрес проживания: {{ session('user')->address }}.
        </div>
    </div>
</div>
<div class="d-flex justify-content-center">
    <div class="row">
        <!-- Окно обновления данных (логин, пароль, паспортные данные, ФИО, соц. статус, номер телефона, адрес проживания) УЗ (кнопка) -->
        <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#update">Изменить данные</button>
        <!-- Просмотр записей -->
        <a href="/users/records/{{session('userInfo')->id}}" class="btn btn-success btn-block">Мед. карта</a>
        <!-- Подтверждение удаления УЗ (кнопка) -->
        <button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#delete">Удалить аккаунт</button>
        <!-- Модальное окно для подтверждения выхода из УЗ (кнопка) -->
        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#exit">Выйти</button>

        <!-- Модальное окно для подтверждения выхода из УЗ (окно) -->
        <div class="modal fade" id="exit" tabindex="-1" role="dialog" aria-labelledby="exitLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exitLabel">Подтверждение выхода</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Вы уверены, что хотите выйти?
            </div>
            <div class="modal-footer">
                <a class="btn btn-danger" href="/logout">Выйти</a>
                <button type="button" class="btn btn-success" data-dismiss="modal">Отмена</button>
            </div>
            </div>
        </div>
        </div>

        <!-- Модальное окно для подтверждения удаления УЗ (окно) -->
        <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLabel">Подтверждение удаления</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Вы уверены, что хотите удалить свой аккаунт? <br>
                Данное действие, как и его последствия невозможно отменить.
            </div>
            <div class="modal-footer">
                <a class="btn btn-danger" href="users/delete/{{session('userInfo')->id}}">Удалить</a>
                <button type="button" class="btn btn-success" data-dismiss="modal">Отмена</button>
            </div>
            </div>
        </div>
        </div>

        <!-- Окно для подтверждения изменения данных УЗ (окно) -->
        <div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="updateLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateLabel">Подтверждение изменений</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="surname">Фамилия</label>
                            <input type="text" class="form-control" id="surname" name="surname" placeholder="{{ session('user')->surname }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="name">Имя</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="{{ session('user')->name }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="patronymic">Отчество <small>(при наличии)</small></label>
                            <input type="text" class="form-control" id="patronymic" name="patronymic" placeholder="{{ session('user')->patronymic }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="seria">Серия</label>
                            <input type="text" class="form-control" id="seria" name="seria" placeholder="{{ substr(session('user')->passport,0,4) }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="nomer">Номер паспорта</label>
                            <input type="text" class="form-control" id="nomer" name="nomer" placeholder="{{ substr(session('user')->passport,5,10) }}">
                        </div>
                        <div class="form-group col-md-7">
                            <label for="address">Адрес</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="{{ session('user')->address }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="login">Логин</label>
                            <input type="text" class="form-control" id="login" name="login" placeholder="{{ session('userInfo')->login }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="password">Пароль</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="{{ '**********' }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="phone">Номер телефона</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="{{ session('user')->tel }}">
                        </div>
                        <div class="form-group col-md-3">
                        <label for="class">Социальный статус</label>
                            <select class="form-control" id="class" name="class">
                                <option>Учащийся</option>
                                <option>Работающий</option>
                                <option>Временно не работающий</option>
                                <option>Инвалид</option>
                                <option>Пенсионер</option>
                            </select>
                        </div>
                    </div>
                </form>
                <p>В случае отмены, все не сохраненные изменения будут аннулированы.</p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-info" href="users/update/{{session('userInfo')->id}}">Изменить</a>
                <button type="button" class="btn btn-warning" data-dismiss="modal">Отмена</button>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>

@endsection

<!-- Личный кабинет врача -->
@elseif(session('userInfo')->role == "medic")

@section('title')Учетная запись врача@endsection

@section('content')

@endsection

@elseif(session('userInfo')->role == "employer")

@section('content')

@endsection

<!-- Личный кабинет администратора -->
@elseif(session('userInfo')->role == "admin")

@section('button')<a class="btn btn-danger" href="/admin">Админ. панель</a>@endsection

@section('content')

<div class="container text-justify ml-4">
    <p>Приветствую тебя, идущий на вахту, одинокий админ. Надеюсь, что хоть сегодня тебе не будет скучно с твоей админ. панелью.</p>
</div>
<div class="d-flex justify-content-center">
    <div class="row">
        <!-- Модальное окно для изменения данных УЗ (кнопка) --> 
        <a href="/users/update/{{session('userInfo')->id}}" class="btn btn-info btn-block">Изменить данные</a>
        <!-- Модальное окно для подтверждения выхода из УЗ (кнопка) --> 
        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#exit">Выйти</button>

        <!-- Модальное окно для подтверждения выхода из УЗ (окно) -->
        <div class="modal fade" id="exit" tabindex="-1" role="dialog" aria-labelledby="exitLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exitLabel">Подтверждение выхода</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Вы уверены, что хотите выйти?
            </div>
            <div class="modal-footer">
                <a class="btn btn-danger" href="/logout">Выйти</a>
                <button type="button" class="btn btn-success" data-dismiss="modal">Отмена</button>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>

@endsection

@endif

@else

<!-- Название страницы, её title -->
@section('title')Вход на сайт@endsection

@section('link')<a class="p-2 text-light" href="/users/sign_in">Войти</a>@endsection

@section('button')<a class="btn btn-danger" href="/users/sign_up">Прикрепиться</a>@endsection

@section('content')
    <!-- Контент страницы -->
    <h2 class="text-center">Вход на сайт</h1>
    
    <!-- Вывод ошибок при валидации -->
    @if($errors->any())
        <div class="alert alert-danger">
            <h4>При попытке входа произошли следующие ошибки:</h4>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Форма для аутентификации -->
    <div class="container w-50">
        <form method="post" action="/users/sign_in/validate">
        {{ csrf_field() }}
            <div class="form-group">
                <label for="login">Логин</label>
                <input type="login" class="form-control" id="login" aria-describedby="loginHelp" name="login" placeholder="Login">
                <small id="loginHelp" class="form-text text-muted">Будьте крайне внимательны при вводе как логина, так и пароля.</small>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Введите пароль" aria-describedby="passwordHelp">
                <small id="passwordHelp" class="form-text text-muted">Пароль для пациентов по умолчанию - комбинация серии и номера паспорта, написаных слитно.</small>
            </div>
            <button type="submit" class="btn btn-dark">Войти</button>
        </form>
    </div>
@endsection

@endif