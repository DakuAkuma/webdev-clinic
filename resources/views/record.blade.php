@extends('layout')

<!-- Страница для добавления записей в мед. карту -->
@section('title')Создание медицинской записи@endsection

@section('content')

<h3 class="pl-4">Создать запись</h3>

<div class="container border rounded bg-white mb-2 pr-4">
    <form action="/records/add">
        <h5 class="ml-4 pt-1">Выберите пациента</h5>
        <div class="row d-flex justify-content-around align-items-center">
            <div class="form-group col-md-10">
                <select class="form-control" id="patient" name="patient">
                    <option value="" selected disabled hidden>{{ (empty($_REQUEST['patient'])) ? "Не выбрано" : $_REQUEST['patient'] }}</option>
                    @foreach($patients as $patient)
                    <option>{{$patient->surname . " " . $patient->name . " " . $patient->patronymic}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <button class="btn btn-dark btn-block" type="submit">Выбрать</button>
            </div>
        </div>
    </form>
</div>

@if(!empty($_REQUEST['patient']))

@if(count($records) == 0)

<div class="container-fluid border rounded w-auto pb-5 mb-2">
    <h3 class="pl-5 pt-1 pb-1">Медицинские записи: {{ $_REQUEST['patient'] }}</h3>
    <h5 class="text-center">Записи отсутствуют</h5>
    <div class="row float-right pr-3">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#create">Добавить запись</button>
    </div>
</div>

@else

<div class="container-fluid border rounded w-auto pb-5 mb-2">
    <h4 class="pl-5 pt-1 pb-1">Медицинские записи: {{ $_REQUEST['patient'] }}</h4>
    
    <table class="table table-borderless table-responsive">
        <thead class="">
            <tr>
            <th scope="col" class="align-middle">#</th>
            <th scope="col" class="align-middle">Дата</th>
            <th scope="col" class="align-middle">Диагноз</th>
            <th scope="col" class="align-middle">Нетрудоспособен</th>
            <th scope="col" class="align-middle">Амбулаторное лечение</th>
            <th scope="col" class="align-middle">Диспансерный учет</th>
            <th scope="col" class="align-middle">Примечание</th>
            <th scope="col" class="align-middle">Врач</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $id => $record)
            <tr>
                <th scope="row" class="align-middle">{{$id+1}}</th>
                <td class="align-middle">{{ (empty($visits)) ? "Отсутствует" : date("d.m.Y", strtotime($visits[$id]->date)) }}</td>
                <td class="align-middle">{{$illnesses[$id]->illness}}</td>
                <td class="align-middle">{{$record->disabled}}</td>
                <td class="align-middle">{{$record->ambulary}}</td>
                <td class="align-middle">{{$record->dispans}}</td>
                @if(empty($record->note))
                <td class="align-middle">Отсутствует</td>
                @else
                <td>{{$record->note}}</td>
                @endif
                <td class="align-middle">{{ $doctors[$id]->surname . " " . substr($doctors[$id]->name,0,2)."." . " " . substr($doctors[$id]->patronymic,0,2)."." }} ({{$doctors[$id]->spec}})</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row float-right pr-3">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#create">Добавить запись</button>
    </div>
</div>

<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="createLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form action="/records/add/validate" method="post">
        {{ csrf_field() }}
            <div class="modal-header">
                <h5 class="modal-title" id="createLabel">Добавление записи</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="form-row">
                <div class="form-group col pt-1">
                    <label for="fio">Полное ФИО пациента</label>
                    <input class="form-control" id="fio" name="fio" type="text" value="{{ $_REQUEST['patient'] }}" readonly>
                </div>
            </div>
            <div class="form-row">
            <div class="form-group col pt-1">
                    <label for="disabled">Срок нетрудоспособности</label>
                    <input class="form-control" id="disabled" name="disabled" type="text">
                </div>
                <div class="form-group col pt-1">
                    <label for="ambulary">Амбулаторное лечение</label>
                    <select name="ambulary" id="ambulary" class="form-control">
                        <option value="" selected disabled hidden>Не выбрано</option>
                        <option value="Требуется">Требуется</option>
                        <option value="Не требуется">Не требуется</option>
                        <option value="Срочно требуется">Срочно требуется</option>
                    </select>
                </div>
                <div class="form-group col pt-1">
                    <label for="dispans">Диспансерный учет</label>
                    <select name="dispans" id="dispans" class="form-control">
                        <option value="" selected disabled hidden>Не выбрано</option>
                        <option value="Не наблюдается">Не наблюдается</option>
                        <option value="Поставлен на учет">Поставлен на учет</option>
                        <option value="Наблюдается">Наблюдается</option>
                        <option value="Поставлен на учет">Снят с учета</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6 pt-1">
                    <label for="illness">Диагноз</label>
                    <select name="illness" id="illness"class="form-control">
                        <option value="" selected disabled hidden>Не выбрано</option>
                        @foreach($allIlls as $ill)
                        <option value="{{$ill->illness}}">{{$ill->illness}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6 pt-1">
                    <label for="date">Дата посещения</label>
                    <select name="date" id="date"class="form-control">
                        <option value="" selected disabled hidden>Отсутствует</option>
                        @foreach($actual as $visit)
                        <option value="{{$visit->date}}">{{ date("d.m.Y", strtotime($visit->date)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <label for="note">Примечание <small>(Не более 500 символов)</small></label>
                <textarea class="form-control" id="note" name="note" rows="2" placeholder="Введите дополнительную информацию либо оставьте поле пустым"></textarea>
            <div>
            <div class="form-row pl-3 pt-2">
                <button type="submit" class="btn btn-success mr-2">Добавить запись</button>
                <button type="button" class="btn btn-dark" data-dismiss="modal">Отмена</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endif

@endif

@endsection