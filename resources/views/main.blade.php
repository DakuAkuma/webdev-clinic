<!-- Наш шаблон -->
@extends('layouts.app')

<!-- Название страницы, её title -->
@section('title')Главная страница@endsection

@section('content')
    <!-- Контент страницы -->
    <h1 class="text-center mb-5">Добро пожаловать на наш сайт.</h1>
    <div class="container">
        <p>
            Число обслуживаемых, в настоящий момент, пациентов - {{ $amountPatients }}.
        </p>
        <p>
            В настоящий момент, общая численность персонала составляет {{ $amountDoctors + $amountEmployees }}, медиков из них - {{ $amountDoctors }}, остальные различный персонал.
        </p>
        <p>
            Для сообщения о ошибках/проблемах, обратитесь к администратору (контактный номер: 89254442211).
        </p>
        <p>
            В случае необходимости оказания срочной медицинской помощи, она оказывается без очереди и записи. Для получения помощи обратитесь ко врачу нужной вам специализации.
        </p>
        <p>
            В штатном порядке прием посетителей осуществляется с понедельника по пятницу с 8:00 до 20:00. Запись осуществляется на определенный день, в течение этого дня вы можете в любое время обратиться к специалисту.
        </p>
        <p>
        	Специально для новых пользователей нашего сайта, администрация создала <a href="https://docs.google.com/document/d/1_LkelctjKylFXq1lspHk0qfEjVaUpb5VugrncpicrQw/edit?usp=sharing">руководство пользователя</a> для более быстрого ознакомления с ресурсами сайта.
        </p>
    </div>
@endsection