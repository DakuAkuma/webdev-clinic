@extends('layout')

<!-- Страница для работы с записями на прием -->
@section('title')Запись на прием@endsection

@section('content')

@if(count($hot) != 0)
    <h3 class="pl-4 mb-4">Записи на сегодня, не забудьте посетить специалистов</h3>
    <div class="container">
        <div class="row">
            @foreach($hot as $id => $visit)
            <div class="card mr-2" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Запись #{{$id+1}}</h5>
                    <h6 class="card-subtitle mb-3 text-muted">{{array_last($doctors->where('id', $visit->id_medic)->all())->spec}}</h6>
                    <p class="card-text m-0">{{ array_last($doctors->where('id', $visit->id_medic)->all())->surname . " " . array_last($doctors->where('id', $visit->id_medic)->all())->name . " " . array_last($doctors->where('id', $visit->id_medic)->all())->patronymic }}</p>
                    <p class="card-text m-0">Каб. {{ array_last($doctors->where('id', $visit->id_medic)->all())->cabinet }}</p>
                    <p class="card-text m-0">{{ str_replace(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'], date("l, d.m.Y", strtotime($visit->date))) }}</p>
                    <div class="d-flex justify-content-end">
                        <a href="/visits/{{$visit->id}}/update" class="btn btn-warning">Изменение записи</a>
                        <a href="/visits/{{$visit->id}}/delete" class="btn btn-danger">Отмена записи</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endif

<h3 class="pl-4">Записаться на приём</h3>

@if(count($actual) != 0)
    <h3 class="pl-4 mb-4">Записи на сегодня, не забудьте посетить специалистов</h3>
    <div class="container">
        <div class="row">
            @foreach($actual as $id => $visit)
            <div class="card mr-2" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Предстоящая запись #{{$id+1}}</h5>
                    <h6 class="card-subtitle mb-3 text-muted">{{array_last($doctors->where('id', $visit->id_medic)->all())->spec}}</h6>
                    <p class="card-text m-0">{{ array_last($doctors->where('id', $visit->id_medic)->all())->surname . " " . array_last($doctors->where('id', $visit->id_medic)->all())->name . " " . array_last($doctors->where('id', $visit->id_medic)->all())->patronymic }}</p>
                    <p class="card-text m-0">Каб. {{ array_last($doctors->where('id', $visit->id_medic)->all())->cabinet }}</p>
                    <p class="card-text m-0">{{ str_replace(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'], date("l, d.m.Y", strtotime($visit->date))) }}</p>
                    <div class="row">
                        <a href="/visits/{{$visit->id}}/update" class="btn btn-warning ">Изменение записи</a>
                        <a href="/visits/{{$visit->id}}/delete" class="btn btn-danger">Отмена записи</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endif

@if(count($archive) != 0)
    <h3 class="pl-4 mb-4">Архив записей</h3>
    <div class="container">
        <div class="row">
            @foreach($archive as $id => $visit)
            <div class="card mr-2" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Архивная запись #{{$id+1}}</h5>
                    <h6 class="card-subtitle mb-3 text-muted">{{array_last($doctors->where('id', $visit->id_medic)->all())->spec}}</h6>
                    <p class="card-text m-0">{{ array_last($doctors->where('id', $visit->id_medic)->all())->surname . " " . array_last($doctors->where('id', $visit->id_medic)->all())->name . " " . array_last($doctors->where('id', $visit->id_medic)->all())->patronymic }}</p>
                    <p class="card-text m-0">Каб. {{ array_last($doctors->where('id', $visit->id_medic)->all())->cabinet }}</p>
                    <p class="card-text m-0">{{ str_replace(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'], date("l, d.m.Y", strtotime($visit->date))) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endif

@endsection