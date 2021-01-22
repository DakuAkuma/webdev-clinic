<!-- Наш шаблон -->
@extends('layout')

<!-- Название страницы, её title -->
@section('title')Главная страница@endsection

@if(session('userInfo'))

@section('link')<a class="p-2 text-light" href="/users/profile">Личный кабинет</a>@endsection

@if(session('userInfo')->role == "patient")

@section('button')<a class="btn btn-danger" href="/visits/add">Записаться на прием</a>@endsection

@elseif(session('userInfo')->role == "admin")

@section('button')<a class="btn btn-danger" href="/admin">Админ. панель</a>@endsection

@elseif(session('userInfo')->role == "medic")

@section('button')<a class="btn btn-danger" href="/records/add">Создать запись</a>@endsection

@elseif(session('userInfo')->role == "employer")

@section('button')@endsection

@endif

@else

@section('link')<a class="p-2 text-light" href="/users/sign_in">Войти</a>@endsection

@section('button')<a class="btn btn-danger" href="/users/sign_up">Прикрепиться</a>@endsection

@endif

@section('content')
    <!-- Контент страницы -->
    <h1 class="text-center mb-5">Добро пожаловать на наш сайт.</h1>
    <div class="conatainer">
    </div>
    <p>
        Число обслуживаемых, в настоящий момент, пациентов - {{ $amountPatients }}.
    </p>
    <p>
        В настоящий момент, общая численность персонала составляет {{ $amountDoctors + $amountEmploy }}, медиков из них - {{ $amountDoctors }}, остальные различный персонал.
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
@endsection