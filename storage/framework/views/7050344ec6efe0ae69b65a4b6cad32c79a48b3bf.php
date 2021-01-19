<!-- Наш шаблон -->


<!-- Название страницы, её title -->
<?php $__env->startSection('title'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Контент страницы -->
    <h1 class="text-center">Добро пожаловать на наш сайт</h1>
    <p>
        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Magni officiis tenetur nisi quam autem obcaecati, similique, ab consequuntur eligendi molestias cupiditate rem maiores amet nesciunt voluptas sed dolores. Eligendi, porro!
    </p>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>