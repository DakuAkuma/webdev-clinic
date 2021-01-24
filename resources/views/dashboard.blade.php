@extends('layouts.admin')

@section('content') 

<!-- Таблица пользователей -->
<h3 class="pt-2 pl-2 mb-3">Список пользователей информационной системы</h3>
<table class="table table-sm border-left border-right border-bottom table-responsive-md">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Логин</th>
            <th scope="col">Пароль</th>
            <th scope="col">Роль пользователя в ИС</th>
            <th scope="col">Дата создания</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <th scope="row" class="align-middle">{{ $user->id }}</th>
            <td class="align-middle">{{ $user->login }}</td>
            <td class="align-middle">**********</td>
            <td class="align-middle">{{ str_replace(['patient', 'medic', 'employer', 'admin'], ['Пациент', 'Врач', 'Сотрудник', 'Администратор'], $user->role) }}</td>
            <td class="align-middle">{{ $user->created_at }}</td>
            <td class="align-middle"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#updateU{{$user->id}}" {{ ($user->role == "admin") ? 'disabled' : ' '}}>Изменить данные</button></td>
            <td class="align-middle"><button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteU{{$user->id}}" {{ ($user->role == "admin") ? 'disabled' : ' '}}>Удалить</button></td>
        </tr>

        <div class="modal fade" id="deleteU{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="deleteU{{$user->id}}Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteU{{$user->id}}Label">Подтверждение действий</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body">
                    Вы уверены, что хотите удалить данного пользователя? <br> 
                    Отмена действий и их последствий невозможна.
                    </div>
                <div class="modal-footer">
                <a class="btn btn-danger" href="/admin/{{$user->id}}/delete">Удалить</a>
                <button type="button" class="btn btn-success" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
        </div>

        <div class="modal fade" id="updateU{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="updateU{{$user->id}}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateU{{$user->id}}Label">Подтверждение изменений</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="/admin/{{$user->id}}/update">
                        {{ csrf_field() }}
                            <div class="form-row">
                                <div class="form-group col-md-1">
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="login">Логин</label>
                                    <input type="text" class="form-control" id="login" name="login" placeholder="{{ $user->login }}">
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

        @endforeach
    </tbody>
</table>


<!-- Таблица пациентов -->
<h3 class="pt-2 pl-2 mb-3">Список пациентов больницы</h3>
<table class="table table-sm border-left border-right border-bottom table-responsive-md">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">userID</th>
            <th scope="col">Ф.И.О.</th>
            <th scope="col">Телефон</th>
            <th scope="col">Адрес проживания</th>
            <th scope="col">Дата рождения</th>
            <th scope="col">Состояние</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($patients as $patient)
        <tr>
            <th scope="row" class="align-middle">{{ $patient->id }}</th>
            <td class="align-middle">{{ $patient->user_id }}</td>
            <td class="align-middle">{{ $patient->surname . " " . substr($patient->name,0,2)."." . " " . substr($patient->patronymic,0,2)."." }}</td>
            <td class="align-middle">{{ $patient->tel }}</td>
            <td class="align-middle">{{ $patient->address }}</td>
            <td class="align-middle">{{ date('d.m.Y', strtotime($patient->birthdate)) }}</td>
            <td class="align-middle">{{ $patient->status }}</td>
            <td class="align-middle"><button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteP{{$patient->id}}">Удалить</button></td>
        </tr>

        <div class="modal fade" id="deleteP{{$patient->id}}" tabindex="-1" role="dialog" aria-labelledby="deleteP{{$patient->id}}Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteP{{$patient->id}}Label">Подтверждение действий</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body">
                    Вы уверены, что хотите удалить данного пользователя? <br> 
                    Отмена действий и их последствий невозможна.
                    </div>
                <div class="modal-footer">
                <a class="btn btn-danger" href="/patient/{{$patient->id}}/delete">Удалить</a>
                <button type="button" class="btn btn-success" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>

        @endforeach
    </tbody>
</table>

<!-- Таблица врачей -->
<h3 class="pt-2 pl-2 mb-3">Список медицинских сотрудников</h3>
<table class="table table-sm border-left border-right border-bottom table-responsive-md">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">userID</th>
            <th scope="col">Ф.И.О.</th>
            <th scope="col">Телефон</th>
            <th scope="col">Специальность</th>
            <th scope="col">Квалификация</th>
            <th scope="col">З./п.</th>
            <th scope="col">Стаж</th>
            <th scope="col">Номер кабинета</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($doctors as $doctor)
        <tr>
            <th scope="row" class="align-middle">{{ $doctor->id }}</th>
            <td class="align-middle">{{ $doctor->user_id }}</td>
            <td class="align-middle">{{ $doctor->surname . " " . substr($doctor->name,0,2)."." . " " . substr($doctor->patronymic,0,2)."." }}</td>
            <td class="align-middle">{{ empty($doctor->tel) ? "Отсутствует" : $doctor->tel }}</td>
            <td class="align-middle">{{ $doctor->spec }}</td>
            <td class="align-middle">{{ $doctor->quality }}</td>
            <td class="align-middle">{{ $doctor->salary }}</td>
            <td class="align-middle">{{ $doctor->exp }}</td>
            <td class="align-middle">{{ $doctor->cabinet }}</td>
            <td class="align-middle"><button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteM{{$doctor->id}}">Удалить</button></td>
        </tr>

        <div class="modal fade" id="deleteM{{$doctor->id}}" tabindex="-1" role="dialog" aria-labelledby="deleteM{{$doctor->id}}Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteM{{$doctor->id}}Label">Подтверждение действий</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body">
                    Вы уверены, что хотите удалить данного пользователя? <br> 
                    Отмена действий и их последствий невозможна.
                    </div>
                <div class="modal-footer">
                <a class="btn btn-danger" href="/medic/{{$doctor->id}}/delete">Удалить</a>
                <button type="button" class="btn btn-success" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>

        @endforeach
    </tbody>
</table>

<!-- Таблица сотрудников -->
<h3 class="pt-2 pl-2 mb-3">Список сотрудников</h3>
<table class="table table-sm border-left border-right border-bottom table-responsive-md">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">userID</th>
            <th scope="col">Ф.И.О.</th>
            <th scope="col">Телефон</th>
            <th scope="col">Должность</th>
            <th scope="col">З./п.</th>
            <th scope="col">Стаж</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $employee)
        <tr>
            <th scope="row" class="align-middle">{{ $employee->id }}</th>
            <td class="align-middle">{{ $employee->user_id }}</td>
            <td class="align-middle">{{ $employee->surname . " " . substr($employee->name,0,2)."." . " " . substr($employee->patronymic,0,2)."." }}</td>
            <td class="align-middle">{{ empty($employee->tel) ? "Отсутствует" : $doctor->tel }}</td>
            <td class="align-middle">{{ $employee->rank }}</td>
            <td class="align-middle">{{ $employee->salary }}</td>
            <td class="align-middle">{{ $employee->exp }}</td>
            <td class="align-middle"><button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteE{{$employee->id}}">Удалить</button></td>
        </tr>

        <div class="modal fade" id="deleteE{{$employee->id}}" tabindex="-1" role="dialog" aria-labelledby="deleteE{{$employee->id}}Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteE{{$employee->id}}Label">Подтверждение действий</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body">
                    Вы уверены, что хотите удалить данного пользователя? <br> 
                    Отмена действий и их последствий невозможна.
                    </div>
                <div class="modal-footer">
                <a class="btn btn-danger" href="/employee/{{$employee->id}}/delete">Удалить</a>
                <button type="button" class="btn btn-success" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>

        @endforeach
    </tbody>
</table>

@endsection