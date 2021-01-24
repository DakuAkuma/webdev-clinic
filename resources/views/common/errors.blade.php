<!-- Вывод ошибок при валидации -->
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h4>Ой-ой-ой. Кажется, произошла ошибка</h4>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ str_replace(['login', 'password', 'surname', 'name', 'patronymic', 'address', 'phone', 'class', 'seria', 'nomer', 'birthdate', 'illness', 'disabled', 'ambulary', 'dispans', 'spec', 'quality', 'salary', 'exp', 'cabinet'], ['Логин', 'Пароль', 'Фамилия', 'Имя', 'Отчество', 'Адрес', 'Номер телефона', 'Социальный статус', 'Серия паспорта', 'Номер паспорта', 'Дата рождения', 'Диагноз', 'Срок нетрудоспособности', 'Амбулаторное лечение', 'Диспансерный учет', 'Специальность', 'Квалификация', 'З./п.', 'Стаж', 'Номер кабинета'], $error) }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif