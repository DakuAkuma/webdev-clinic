@extends('layouts.app')

<!-- Страница для работы с записями на прием -->
@section('title')Запись на прием@endsection

@section('content')

<h3 class="pl-4">Записаться на приём</h3>

<div class="container border rounded bg-white mb-2 pr-4">
    <form action="/visits">
        <h5 class="ml-4 pt-1">Выбор специальности врача</h5>
        <div class="row d-flex justify-content-around align-items-center">
            <div class="form-group col-md-10">
                <select class="form-control" id="spec" name="spec">
                    <option value="" selected disabled hidden>{{ (empty($_REQUEST['spec'])) ? "Не выбрано" : $_REQUEST['spec'] }}</option>
                    @foreach($specials as $id => $spec)
                    <option>{{$spec->spec}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2">
                <button class="btn btn-dark btn-block" type="submit">Выбрать</button>
            </div>
        </div>
    </form>
</div>

@if(!empty($_REQUEST['spec']))

<div class="container border rounded bg-white pt-3 mb-3 w-75">
    <table class="table table-bordered">
        <tbody>
            @foreach($pickable as $id => $doctor)
            <tr>
                <td class="align-middle">{{$doctor->surname . " " . $doctor->name . " " . $doctor->patronymic}}</td>
                <td class="align-middle text-center">Кабинет {{$doctor->cabinet}}</td>
                <!-- Кнопка для модального окна -->
                <td class="align-middle"><button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#create{{$doctor->id}}">Записаться</button></td>
            </tr>
            <!-- Модальное окно для передачи данных на валидацию -->
            <div class="modal fade" id="create{{$doctor->id}}" tabindex="-1" role="dialog" aria-labelledby="create{{$doctor->id}}Title" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="create{{$doctor->id}}Title">Создание записи</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <strong>Врач:</strong>&nbsp;{{$doctor->surname . " " . $doctor->name . " " . $doctor->patronymic}}.
                        <br>
                        <strong>Специальность:</strong>&nbsp;{{$doctor->spec}}.
                        <br>   
                        <strong>Кабинет:</strong>&nbsp;{{$doctor->cabinet}}.
                        <br>
                        <form method="post" action="/visits/add/{{$doctor->id}}">
                        {{ csrf_field() }}
                            <div class="form-row pl-2">
                                <div class="form-group">
                                    <label for="date">Дата посещения</label>
                                    <input type="date" class="form-control" id="date" name="date">
                                </div>
                            </div>
                            <div class="float-right">
                                <button type="submit" class="btn btn-success">Подтвердить</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
                            </div>
                        </form>
                        <br>
                        <p class="pt-4 mb-0 pb-0 font-italic text-justify">
                            После создания записи, в день, который вы выбрали, вы можете подойти к данному специалисту с 8:00 до 20:00, в порядке живой очереди.
                        </p>
                    </div>
                </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
    </div>
</div>

@endif

@if(count($hot) != 0)
    <div class="container mb-2 pl-0">
        <h3 class="pl-4 mb-3">Записи на сегодня, не забудьте посетить</h3>
        <div class="row pl-1">
            @foreach($hot as $id => $visit)
            <div class="card mr-2 mb-2" style="width: 17rem;">
                <div class="card-body">
                    <h5 class="card-title">Запись #{{$id+1}}</h5>
                    <h6 class="card-subtitle mb-3 text-muted">{{array_last($doctors->where('id', $visit->medic_id)->all())->spec}}</h6>
                    <p class="card-text m-0">{{ array_last($doctors->where('id', $visit->medic_id)->all())->surname . " " . array_last($doctors->where('id', $visit->medic_id)->all())->name . " " . array_last($doctors->where('id', $visit->medic_id)->all())->patronymic }}</p>
                    <p class="card-text m-0">Каб. {{ array_last($doctors->where('id', $visit->medic_id)->all())->cabinet }}</p>
                    <p class="card-text m-0 pb-2">{{ str_replace(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'], date("l, d.m.Y", strtotime($visit->date))) }}</p>
                    <div class="row">
                        <button type="button" class="btn btn-info btn-block mb-2" data-toggle="modal" data-target="#update{{array_last($doctors->where('id', $visit->medic_id)->all())->id}}">Изменить запись</button>
                        <!-- Window -->
                        <div class="modal fade" id="update{{array_last($doctors->where('id', $visit->medic_id)->all())->id}}" tabindex="-1" role="dialog" aria-labelledby="update{{array_last($doctors->where('id', $visit->medic_id)->all())->id}}Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="update{{array_last($doctors->where('id', $visit->medic_id)->all())->id}}Title">Изменение записи</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <strong>Врач:</strong>&nbsp;{{array_last($doctors->where('id', $visit->medic_id)->all())->surname . " " . array_last($doctors->where('id', $visit->medic_id)->all())->name . " " . array_last($doctors->where('id', $visit->medic_id)->all())->patronymic}}.
                                    <br>
                                    <strong>Специальность:</strong>&nbsp;{{array_last($doctors->where('id', $visit->medic_id)->all())->spec}}.
                                    <br>   
                                    <strong>Кабинет:</strong>&nbsp;{{array_last($doctors->where('id', $visit->medic_id)->all())->cabinet}}.
                                    <br>
                                    <form method="post" action="/visits/{{$visit->id}}/update">
                                    {{ csrf_field() }}
                                        <div class="form-row pl-2">
                                            <div class="form-group">
                                                <label for="date">Новая дата посещения</label>
                                                <input type="date" class="form-control" id="date" name="date" value="{{$visit->date}}">
                                            </div>
                                        </div>
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-success">Подтвердить</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
                                        </div>
                                    </form>
                                    <br>
                                    <p class="pt-4 mb-0 pb-0 font-italic text-justify">
                                        В случае отмены, никаких изменений не произойдет.
                                    </p>
                                </div>
                            </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#delete{{array_last($doctors->where('id', $visit->medic_id)->all())->id}}">Отменить запись</button>
                        <!-- Window -->
                        <div class="modal fade" id="delete{{array_last($doctors->where('id', $visit->medic_id)->all())->id}}" tabindex="-1" role="dialog" aria-labelledby="delete{{array_last($doctors->where('id', $visit->medic_id)->all())->id}}Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="delete{{array_last($doctors->where('id', $visit->medic_id)->all())->id}}Title">Отмена записи</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p class="pl-2 mb-3">Вы уверены, что хотите отменить данную запись?</p>
                                    
                                    <div class="float-right">
                                        <a href="/visits/{{$visit->id}}/delete" class="btn btn-danger">Подтвердить</a>
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                                    </div>
                                    <br>
                                    <p class="pt-4 mb-0 pb-0 font-italic text-justify">
                                        В случае отмены, никаких изменений не произойдет.
                                    </p>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endif

@if(count($actual) != 0)
    <div class="container pl-0">
        <h3 class="pl-4 mb-3">Ближайшие записи</h3>
        <div class="row pl-1">
            @foreach($actual as $id => $visit)
            <div class="card mr-2 mb-2" style="width: 17rem;">
                <div class="card-body">
                    <h5 class="card-title">Предстоящая запись #{{$id+1}}</h5>
                    <h6 class="card-subtitle mb-3 text-muted">{{array_last($doctors->where('id', $visit->medic_id)->all())->spec}}</h6>
                    <p class="card-text m-0">{{ array_last($doctors->where('id', $visit->medic_id)->all())->surname . " " . array_last($doctors->where('id', $visit->medic_id)->all())->name . " " . array_last($doctors->where('id', $visit->medic_id)->all())->patronymic }}</p>
                    <p class="card-text m-0">Каб. {{ array_last($doctors->where('id', $visit->medic_id)->all())->cabinet }}</p>
                    <p class="card-text m-0 pb-2">{{ str_replace(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'], date("l, d.m.Y", strtotime($visit->date))) }}</p>
                    <div class="row">
                        <button type="button" class="btn btn-info btn-block mb-2" data-toggle="modal" data-target="#update{{array_last($doctors->where('id', $visit->medic_id)->all())->id}}">Изменить запись</button>
                        <!-- Window -->
                        <div class="modal fade" id="update{{array_last($doctors->where('id', $visit->medic_id)->all())->id}}" tabindex="-1" role="dialog" aria-labelledby="update{{array_last($doctors->where('id', $visit->medic_id)->all())->id}}Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="update{{array_last($doctors->where('id', $visit->medic_id)->all())->id}}Title">Изменение записи</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <strong>Врач:</strong>&nbsp;{{array_last($doctors->where('id', $visit->medic_id)->all())->surname . " " . array_last($doctors->where('id', $visit->medic_id)->all())->name . " " . array_last($doctors->where('id', $visit->medic_id)->all())->patronymic}}.
                                    <br>
                                    <strong>Специальность:</strong>&nbsp;{{array_last($doctors->where('id', $visit->medic_id)->all())->spec}}.
                                    <br>   
                                    <strong>Кабинет:</strong>&nbsp;{{array_last($doctors->where('id', $visit->medic_id)->all())->cabinet}}.
                                    <br>
                                    <form method="post" action="/visits/{{$visit->id}}/update">
                                    {{ csrf_field() }}
                                        <div class="form-row pl-2">
                                            <div class="form-group">
                                                <label for="date">Новая дата посещения</label>
                                                <input type="date" class="form-control" id="date" name="date" value="{{$visit->date}}">
                                            </div>
                                        </div>
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-success">Подтвердить</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
                                        </div>
                                    </form>
                                    <br>
                                    <p class="pt-4 mb-0 pb-0 font-italic text-justify">
                                        В случае отмены, никаких изменений не произойдет.
                                    </p>
                                </div>
                            </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#delete{{array_last($doctors->where('id', $visit->medic_id)->all())->id}}">Отменить запись</button>
                        <!-- Window -->
                        <div class="modal fade" id="delete{{array_last($doctors->where('id', $visit->medic_id)->all())->id}}" tabindex="-1" role="dialog" aria-labelledby="delete{{array_last($doctors->where('id', $visit->medic_id)->all())->id}}Title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="delete{{array_last($doctors->where('id', $visit->medic_id)->all())->id}}Title">Отмена записи</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p class="pl-2 mb-3">Вы уверены, что хотите отменить данную запись?</p>
                                    
                                    <div class="float-right">
                                        <a href="/visits/{{$visit->id}}/delete" class="btn btn-danger">Подтвердить</a>
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                                    </div>
                                    <br>
                                    <p class="pt-4 mb-0 pb-0 font-italic text-justify">
                                        В случае отмены, никаких изменений не произойдет.
                                    </p>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endif

@if(count($archive) != 0)
    <div class="container pl-0">
        <h3 class="pl-4 mb-3">Архив записей</h3>
        <div class="row pl-1">
            @foreach($archive as $id => $visit)
            <div class="card mr-2 mb-2" style="width: 15rem;">
                <div class="card-body">
                    <h5 class="card-title">Архивная запись #{{$id+1}}</h5>
                    <h6 class="card-subtitle mb-3 text-muted">{{array_last($doctors->where('id', $visit->medic_id)->all())->spec}}</h6>
                    <p class="card-text m-0">{{ array_last($doctors->where('id', $visit->medic_id)->all())->surname . " " . array_last($doctors->where('id', $visit->medic_id)->all())->name . " " . array_last($doctors->where('id', $visit->medic_id)->all())->patronymic }}</p>
                    <p class="card-text m-0">Каб. {{ array_last($doctors->where('id', $visit->medic_id)->all())->cabinet }}</p>
                    <p class="card-text m-0">{{ str_replace(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'], date("l, d.m.Y", strtotime($visit->date))) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endif

@endsection