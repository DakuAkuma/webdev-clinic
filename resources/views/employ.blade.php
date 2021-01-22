@extends('layout')

<!-- Личный кабинет сотрудника не мед. персонала -->
@section('title')Личный кабинет сотрудника@endsection

@section('content')

<div class="container text-justify ml-4">
    <h3 class="text-center mb-2">Добро пожаловать в личный кабинет</h3>
    <h4 class="text-center mb-3">Здравствуйте, {{ session('user')->name . " " . session('user')->patronymic }}</h4>
    <div class="container pt-2">
    <div class="row mb-4">
        <div class="col-md-4">
            Полное ФИО: {{ session('user')->surname . " " . session('user')->name . " " . session('user')->patronymic }}.
            <br>
            Контактный телефон: {{ (empty(session('user')->tel)) ? "Не указан" :  session('user')->tel }}.
        </div>
        <div class="col-md-4">
            Должность: {{ session('user')->rank }}. <br> 
            Вы являетесь: сотрудником.
        </div>
        <div class="col-md-4">
            Ваша заработная плата: {{ session('user')->salary }} рублей. <br>
            Стаж работы: {{ session('user')->exp }}.
        </div>
    </div>
</div>
</div>
<div class="d-flex justify-content-center">
    <div class="row">
        <!-- Модальное окно для изменения данных УЗ (кнопка) --> 
        <!-- <a href="/users/update/{{session('userInfo')->id}}" class="btn btn-info btn-block">Изменить данные</a> -->
        <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#update">Изменить данные</button>
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
                <form method="post" action="/{{session('userInfo')->role}}/{{session('userInfo')->id}}/update">
                {{ csrf_field() }}
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
                            <input type="text" class="form-control" id="patronymic" value="{{ session('user')->patronymic }}" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="login">Логин</label>
                            <input type="text" class="form-control" id="login" name="login" placeholder="{{ session('userInfo')->login }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="password">Пароль</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="{{ '**********' }}">
                        </div>
                        <div class="form-group col-md-1">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="phone">Контактный телефон</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="{{ (empty(session('user')->tel)) ? 'Не указан' :  session('user')->tel }}">
                        </div>
                    </div>
                    <p>При отмене никаких изменений не произойдет.</p>
                    <div class="d-flex justify-content-end modal-footer mb-0 pb-0">
                        <button type="submit" class="btn btn-info ml-2 mr-2">Изменить</button>
                        <button type="button" class="btn btn-warning ml-2 mr-2" data-dismiss="modal">Отмена</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>

@endsection