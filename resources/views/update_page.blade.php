@extends('layouts.admin')

@section('content')

<!-- Таблица врачей -->
<h3 class="pt-2 pl-2 mb-3">Изменение данных медицинских сотрудников</h3>
<table class="table table-sm border-left border-right border-bottom table-responsive-md">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">userID</th>
            <th scope="col">Ф.И.О.</th>
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
            <td class="align-middle">{{ $doctor->spec }}</td>
            <td class="align-middle">{{ $doctor->quality }}</td>
            <td class="align-middle">{{ $doctor->salary }}</td>
            <td class="align-middle">{{ $doctor->exp }}</td>
            <td class="align-middle">{{ $doctor->cabinet }}</td>
            <td class="align-middle"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#updateM{{$doctor->id}}">Изменить</button></td>
        </tr>

        <div class="modal fade" id="updateM{{$doctor->id}}" tabindex="-1" role="dialog" aria-labelledby="updateM{{$doctor->id}}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateM{{$doctor->id}}Label">Подтверждение изменений</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="/medic/{{$doctor->id}}/update">
                        {{ csrf_field() }}
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="surname">Фамилия</label>
                                    <input type="text" class="form-control" id="surname" placeholder="{{ $doctor->surname }}" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="name">Имя</label>
                                    <input type="text" class="form-control" id="name" placeholder="{{ $doctor->name }}" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="patronymic">Отчество</label>
                                    <input type="text" class="form-control" id="patronymic" placeholder="{{ $doctor->patronymic }}" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="spec">Специальность</label>
                                    <input type="spec" class="form-control" id="spec" name="spec" placeholder="{{ $doctor->spec }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="quality">Квалификация</label>
                                    <select name="quality" id="quality" class="form-control">
                                        <option value="" selected disabled hidden>Не выбрано</option>
                                        <option value="1-я категория">1-я категория</option>
                                        <option value="2-я категория">2-я категория</option>
                                        <option value="3-я категория">3-я категория</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="salary">З./п.</label>
                                    <input type="text" class="form-control" id="salary" name="salary" placeholder="{{ $doctor->salary }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="exp">Cтаж</label>
                                    <input type="text" class="form-control" id="exp" name="exp" placeholder="{{ $doctor->exp }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cabinet">Номер кабинета</label>
                                    <input type="text" class="form-control" id="cabinet" name="cabinet" placeholder="{{ $doctor->cabinet }}">
                                </div>
                            </div>
                            <p>При отмене никаких изменений не произойдет.</p>
                            <div class="d-flex justify-content-end modal-footer mb-0 pb-0">
                                <button type="submit" class="btn btn-info ml-2 mr-2">Изменить</button>
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Отмена</button>
                            </div>
                        </form>
                    </div>
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
            <td class="align-middle">{{ $employee->rank }}</td>
            <td class="align-middle">{{ $employee->salary }}</td>
            <td class="align-middle">{{ $employee->exp }}</td>
            <td class="align-middle"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#updateE{{$employee->id}}">Изменить</button></td>
        </tr>

        <div class="modal fade" id="updateE{{$employee->id}}" tabindex="-1" role="dialog" aria-labelledby="updateE{{$employee->id}}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateE{{$employee->id}}Label">Подтверждение изменений</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="/employer/{{$employee->id}}/update">
                        {{ csrf_field() }}
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="surname">Фамилия</label>
                                    <input type="text" class="form-control" id="surname" placeholder="{{ $employee->surname }}" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="name">Имя</label>
                                    <input type="text" class="form-control" id="name" placeholder="{{ $employee->name }}" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="patronymic">Отчество</label>
                                    <input type="text" class="form-control" id="patronymic" placeholder="{{ $employee->patronymic }}" readonly>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="rank">Должность</label>
                                    <input type="text" class="form-control" id="rank" name="rank" placeholder="{{ $employee->rank }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="salary">З./п.</label>
                                    <input type="text" class="form-control" id="salary" name="salary" placeholder="{{ $employee->salary }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="exp">Cтаж</label>
                                    <input type="text" class="form-control" id="exp" name="exp" placeholder="{{ $employee->exp }}">
                                </div>
                            </div>
                            <p>При отмене никаких изменений не произойдет.</p>
                            <div class="d-flex justify-content-end modal-footer mb-0 pb-0">
                                <button type="submit" class="btn btn-info ml-2 mr-2">Изменить</button>
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Отмена</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @endforeach
    </tbody>
</table>

@endsection