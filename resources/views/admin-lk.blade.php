@extends('layouts.app')

<!-- Личный кабинет администратора -->
@section('title')Личный кабинет администратора@endsection

@section('content')

<div class="container text-justify ml-4">
    <p>Приветствую тебя, идущий на вахту, одинокий админ. Надеюсь, что хоть сегодня тебе не будет скучно с твоей админ. панелью.</p>
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
        <!-- Окно для подтверждения изменения данных УЗ (окно) -->
        <div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="updateLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
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
                            <div class="form-group col-md-1">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="login">Логин</label>
                                <input type="text" class="form-control" id="login" name="login" placeholder="{{ session('userInfo')->login }}">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="password">Пароль</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="{{ '**********' }}">
                            </div>
                            <div class="form-group col-md-1">
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