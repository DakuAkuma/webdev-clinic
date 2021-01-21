<!-- Наш шаблон -->
@extends('layout')

<!-- Название страницы, её title -->
@section('title')Про нас@endsection

@if(session('userInfo'))

@section('link')<a class="p-2 text-light" href="/users/profile/{{session('userInfo')->id}}">Личный кабинет</a>@endsection

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
    <h1 class="text-center">Информация про нас</h1>

@endsection