<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title'); ?></title>
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/header.css">
    
    <script src="js/general.js"></script>
</head>

<body>
    <?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('container'); ?>
</body>

</html><?php /**PATH /Users/pavelkatunin/Documents/bells.ikatunin.ru/resources/views/layouts/app.blade.php ENDPATH**/ ?>