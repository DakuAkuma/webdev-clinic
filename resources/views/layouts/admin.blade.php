<!DOCTYPE html>
<html lang="ru-ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Панель администратора</title>
	<!-- CSS и JavaScript -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/9a5c681441.js" crossorigin="anonymous"></script>
</head>
<body>
	<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-1 bg-success border-bottom shadow">
		<h5 class="my-0 mr-md-auto font-weight-normal text-light">Поликлиника №155</h5>
		<nav class="my-2 my-md-0 mr-md-3">
			<a class="p-2 text-light" href="/">Главная</a>
			<a class="p-2 text-light" href="/profile">Личный кабинет</a>
		</nav>
	</div>
	<div class="container-fluid">
		<div class="row">
			<nav class="col-sm-2 d-none d-sm-block sidebar">
				<div class="sidebar-sticky">
					<ul class="nav flex-column">
						<li class="nav-item">
							<a class="nav-link mb-2" href="/admin">Инфопанель</a>
						</li>
						<li class="nav-item">
							<a class="nav-link mb-2" href="/admin/create">Создать пользователя</a>
						</li>
						<li class="nav-item">
							<a class="nav-link mb-2" href="/admin/update">Изменить данные пользователя</a>
						</li>
					</ul>
				</div>
			</nav>
			<main role="main" class="col-sm-9 ml-sm-auto col-lg-10 px-4">
				@yield('content')
			</main>
		</div>
	</div>
</body>
</html>