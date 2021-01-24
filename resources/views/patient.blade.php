@extends('layouts.app')

<!-- Название страницы, её title -->
@section('title')Личный кабинет пациента@endsection

@section('content')

<h2 class="text-center mb-3">Добро пожаловать в ваш личный кабинет</h2>
<h4 class="text-center mb-3">Здравствуйте, {{ session('user')->name . " " . session('user')->patronymic }}.</h4>

<div class="container pt-2">
    <div class="row mb-4">
        <div class="col-md-4">
            Ваше состояние: {{ session('user')->status }}. <br> 
            Вы являетесь пациентом.
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
        <a href="/records/view" class="btn btn-success btn-block">Мед. карта</a>
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
                <a class="btn btn-danger" href="/profile/delete">Удалить</a>
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
                <form method="post" action="/profile/update">
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
                                <option value="" selected disabled hidden>-</option>
                                <option>Учащийся</option>
                                <option>Работающий</option>
                                <option>Временно не работающий</option>
                                <option>Инвалид</option>
                                <option>Пенсионер</option>
                            </select>
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

@endsection