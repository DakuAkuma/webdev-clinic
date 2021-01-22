<!-- Наш шаблон -->
@extends('layout')

<!-- Название страницы, её title -->
@section('title')Создание новой учетной записи@endsection

@if(session('userInfo'))

{{ header('/users/profile') }}

@endif

@section('content')
<!-- Контент страницы -->
<h1 class="text-center">Прикрепление к поликлинике</h1>

<div class="conatiner text-center">
    <p>Для успешного прикрепления к поликлинике введите данные, указанные ниже. Все данные обязательны для ввода. </p>
</div>
<!-- Форма для получения данных -->
<div class="container w-75 mb-2 rounded border shadow-sm">
    <h2>Анкета для прикрепления к поликлинике</h2>
    <form method="post" action="/users/sign_up/validate">
    {{ csrf_field() }}
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="surname">Фамилия</label>
                <input type="text" class="form-control" id="suranme" name="surname" placeholder="Иванов">
            </div>
            <div class="form-group col-md-4">
                <label for="name">Имя</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Иван">
            </div>
            <div class="form-group col-md-4">
                <label for="patronymic">Отчество <small>(при наличии)</small></label>
                <input type="text" class="form-control" id="patronymic" name="patronymic" placeholder="Иванович">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-5">
                <label for="address">Адрес</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="г. Москва, ул. Подбельского, д.15, кв.255">
            </div>
            <div class="form-group col-md-2">
                <label for="login">Логин</label>
                <input type="text" class="form-control" id="login" maxlength="32" name="login" placeholder="Akuma">
            </div>
            <div class="form-group col-md-1">
            </div>
            <div class="form-group col-md-2">
                <label for="seria">Серия паспорта</label>
                <input type="text" class="form-control" id="seria" maxlength="4" name="seria" placeholder="1234">
            </div>
            <div class="form-group col-md-2">
                <label for="nomer">Номер паспорта</label>
                <input type="text" class="form-control" id="nomer" maxlength="6" name="nomer" placeholder="123456">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="phone">Номер телефона</label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="+792500001234">
            </div>
            <div class="form-group col-md-3">
                <label for="birthdate">Дата рождения</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate">
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
            <div class="form-group col-md-1">
            </div>
            <div class="form-group col-md-2">
                <button type="submit" class="btn btn-outline-success mt-4">Отправить</button>
            </div>
        </div>
    </form>
</div>
<div class="conatiner text-justify">
    <p class="text-center">В качестве логина при входе используйте указаный вами логин, в качестве пароля - комбинацию серии и номера паспорта <strong>(слитно)</strong>.</p>
    <p><strong>Например:</strong> вы выбрали в качестве логина: "Чебурашка", а в качестве серии и номера паспорта ввели "4515" и "221144" соответственно, значит для входа логином будет: "Чебурашка", а паролем: "4515221144".</p>
    <p class="font-italic">Не переживайте, вы всегда сможете изменить логин и пароль в личном кабинете, после успешной регистрации.</p>
</div>
@endsection