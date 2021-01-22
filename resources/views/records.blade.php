@extends('layout')

<!-- Страница для отображения записей -->
@section('title')Медицинская карта@endsection

@section('content')

<h2 class="text-center mb-2">Медицинская карта пациента №{{ session('user')->id }}.</h2>

<div class="container border rounded pb-2 w-50 mb-4">
    <h3 class="pl-4">Персональные данные</h3>
    <div class="row pl-4">
        Фамилия: {{ session('user')->surname }}.
    </div>
    <div class="row pl-4">
        Имя: {{ session('user')->name }}.
    </div>
    <div class="row pl-4">
        Отчество: {{ session('user')->patronymic }}.
    </div>
    <div class="row pl-4">
        Дата рождения: {{ date("d.m.Y", strtotime(session('user')->birthdate)) }} (полных лет: <? $d1 = new DateTime(date('d.m.Y')); $d2 = new DateTime(date('d.m.Y', strtotime(session('user')->birthdate))); echo $d2->diff($d1)->y; ?>).
    </div>
</div>

<div class="container-fluid border rounded pb-2 w-auto mb-2">
    <h3 class="pl-5 pt-1 pb-1">Медицинские записи</h3>
    
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
                <td class="align-middle">{{ (empty($visits)) ? "Отсутствует" : $visits[$id]->date }}</td>
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
</div>
<div class="container">
    <a class="btn btn-danger float-right" href="/users/profile">Вернуться к личный кабинет</a>
</div>
@endsection