
<div class="header">
    <div class="logo">
        <a href="/"><img src="images/logo-new.svg" alt=""></a>
    </div>
    <div class="menu-block col-5">
        <?php 
            $activeRoute = Route::current()->uri;
            $routes = [
                '/'=>'Главная', 
                '/bells'=>'Колокола',
                '/about'=>'Литейная',
                '/contacts'=>'Контакты',
            ];
        ?>

        <?php $__currentLoopData = $routes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $href=>$name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a <?php if($href==$activeRoute): ?> class="active" <?php endif; ?> href="<?php echo e($href); ?>"><?php echo e($name); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
    <div class="phone-block col-2">
        <img src="images/phone.svg" alt="">
        <a href="">+7 900 800-22-05</a>
    </div>
    <a class="basket-block col-1" href="/basket">
        <img src="images/basket.svg" alt="">
        <span id="header-basket-summ"><?php echo e(number_format(App\Http\Controllers\CartController::getBasketSumm(), 0, '', ' ')); ?></span>р
        <button id="basket-arrow">
            <img src="images/basket-arrow.svg" alt="">
        </button>
    </a>
</div><?php /**PATH /Users/pavelkatunin/Documents/bells.ikatunin.ru/resources/views/layouts/header.blade.php ENDPATH**/ ?>