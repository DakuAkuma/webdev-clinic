<!DOCTYPE html>
<html lang="ru" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- CSS и JavaScript -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/9a5c681441.js" crossorigin="anonymous"></script>
    <!-- Переопределенные стили для некоторых случаев -->
    <style>
        a, a:hover, a:active {
            text-decoration: none;
        };
    </style>
</head>
<body class="bg-light text-dark d-flex flex-column h-100">
    <main role="main" class="flex-shrink-0">
        <!-- Панель навигации -->
        <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-success border-bottom shadow">
            <h5 class="my-0 mr-md-auto font-weight-normal text-light">Поликлиника №155</h5>
            <nav class="my-2 my-md-0 mr-md-3">
                <a class="p-2 text-light" href="/">Главная</a>
                <a class="p-2 text-light" href="/about">Про нас</a>
                @if(session('userInfo'))
                    <a class="p-2 text-light" href="/users/profile">Личный кабинет</a>
                @else
                    <a class="p-2 text-light" href="/users/sign_in">Войти</a>
                @endif
            </nav>
            @if(session('userInfo'))
                @if(session('userInfo')->role == "patient")
                    <a class="btn btn-danger" href="/visits/add">Записаться на прием</a>
                @elseif(session('userInfo')->role == "medic")
                    <a class="btn btn-danger" href="/records/add">Добавить запись</a>
                @elseif(session('userInfo')->role == "admin")
                    <a class="btn btn-danger" href="/admin">Административная панель</a>
                @endif
            @else
                <a class="btn btn-danger" href="/users/sign_up">Прикрепиться</a>
            @endif
        </div>
        <!-- Основной "контент страницы" -->
        <div class="container mb-2">
            <!-- Обработка статусов для уведомлений -->
            @if(session('status'))

            @if(session('status') == "successful update")

            <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>Данные пользователя успешно изменены!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            @elseif(session('status') == "successful delete")

            <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Удаление пользователя завершено успешно!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            @elseif(session('status') == "successful create")

            <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Новый пользователь создан успешно!</strong>
            <p>Для входа в учетную запись, используйте придуманный логин и пароль по умолчанию</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            @elseif(session('status') == "no such user")

            <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Попытка входа несуществующим пользователем!</strong>
            <p>Создайте учетную запись для того, чтобы успешно авторизоваться</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            @elseif(session('status') == "incorrect password")

            <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Неверно введен пароль!</strong>
            <p>Проверьте правильность введенного пароля и повторите попытку входа</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            @endif

            @endif
            <!-- Вывод ошибок при валидации -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h4>Ой-ой-ой. Кажется, произошла ошибка</h4>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ str_replace(['login', 'password', 'surname', 'name', 'patronymic', 'address', 'phone', 'class', 'seria', 'nomer', 'birthdate'],['Логин', 'Пароль', 'Фамилия', 'Имя', 'Отчество', 'Адрес', 'Номер телефона', 'Социальный статус', 'Серия паспорта', 'Номер паспорта', 'Дата рождения'],$error) }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @yield('content')
        </div>
    </main>
    <footer class="mt-auto d-flex flex-column flex-md-row align-items-center p-3 px-md-4 bg-success bg-success text-light border-top shadow-lg">
        <div class="container text-center">
            <span>Почтовый индекс: 107222, телефон для записи: 8 (495) 252-11-22, а также 8 (495) 252-11-23.</span>
            <br>
            <span>Адрес: Страна N, город M, улица 1905-го года, д.12, корпус 1</span>
        </div>
    </footer>
</body>
</html>