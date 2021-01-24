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
<span>Для входа в учетную запись, используйте придуманный логин и пароль по умолчанию</span>
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
</div>

@elseif(session('status') == "no such user")

<div class="alert alert-warning alert-dismissible fade show" role="alert">
<strong>Попытка входа несуществующим пользователем!</strong>
<span>Создайте учетную запись для того, чтобы успешно авторизоваться</span>
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
</div>

@elseif(session('status') == "incorrect password")

<div class="alert alert-warning alert-dismissible fade show" role="alert">
<strong>Неверно введены данные!</strong>
<span>Повторите попытку входа более внимательно</span>
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
</div>

@elseif(session('status') == "bad date - weekends")

<div class="alert alert-danger alert-dismissible fade show" role="alert">
<strong>Введите день в пределах рабочей недели (понедельник-пятница)!</strong>
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
</div>

@elseif(session('status') == "visit added")

<div class="alert alert-success alert-dismissible fade show" role="alert">
<strong>Запись создана успешно!</strong>
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
</div>

@elseif(session('status') == "visit updated")

<div class="alert alert-info alert-dismissible fade show" role="alert">
<strong>Запись изменена успешно!</strong>
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
</div>

@elseif(session('status') == "visit deleted")

<div class="alert alert-warning alert-dismissible fade show" role="alert">
<strong>Запись удалена успешно!</strong>
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
</div>

@elseif(session('status') == "visits - same spec")

<div class="alert alert-danger alert-dismissible fade show" role="alert">
<strong>Запрещено иметь более 1 записи к врачу одной специальности!</strong>
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
</div>

@elseif(session('status') == "non authorized")

<div class="alert alert-warning alert-dismissible fade show" role="alert">
<strong>Авторизуйтесь для доступа к личному кабинету!</strong>
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
</div>

@elseif(session('status') == "passport error")

<div class="alert alert-danger alert-dismissible fade show" role="alert">
<strong>Некорректное заполнение данных!</strong>
<span>Серия и номер паспорта должны быть заполнены</span>
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
</div>

@endif

@endif