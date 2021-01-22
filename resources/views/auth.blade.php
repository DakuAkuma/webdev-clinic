<!-- Наш шаблон -->
@extends('layout')

@if(session('userInfo'))

{{ url('/users/profile') }}

@else

<!-- title -->
@section('title')Вход на сайт@endsection

@section('content')
    <!-- Контент страницы -->
    <h2 class="text-center">Вход на сайт</h1>

    <!-- Форма для аутентификации -->
    <div class="container w-50 rounded border shadow-sm pb-3 pt-1">
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