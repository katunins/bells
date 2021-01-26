<link rel="stylesheet" href="css/welcome.css">


<?php $__env->startSection('title', 'Русский колокол - литейная мастерская в Воронеже'); ?>

<?php $__env->startSection('script'); ?>
<script src="js/welcome.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('container'); ?>
<div class="container">

    <div class="block">
        <div class="flex-vert-center col-6">
            
            <div class="utp-block col-5">
                
                <h1>Настольный колокол из бронзы от производителя</h1>
                
                <p>Воронежская литейная мастерская реализует оригинальные колокола с вашим именем, молитвой или ликом
                    святого! Доставка по РФ</p>
                <button class="todo-button"
                    onclick="document.getElementById('shop').scrollIntoView({block: 'start', behavior: 'smooth'})">
                    Выбрать
                    колокол
            </div>
            </button>
        </div>
        <div class="col-6 flex-vert-center">
            <div class="col-5 block-to-center">
                <div><img src="images/big-bell.png" alt=""></div>
                
            </div>
        </div>
    </div>

    <div class="block brown-back flex-vert-center">
        <div class="best-bells-block col-6 flex-horiz-center">
            <div class="col-1 flex-horiz-center">
                <button id="best-bells-left" class="transparent-button" onclick="shiftTopProducts(1)">
                    <img style="transform: rotate(180deg);" src="images/arrow-right.svg" alt="">
                </button>
            </div>

            <div class="flex-vert-center">
                <div id="best-cards-block" class="col-4">
                    <div class="best-cards-container" style="left: 0">
                        <?php $__currentLoopData = $topProduct; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="best-card">
                            <a href="<?php echo e($item->link); ?>">
                                <div class="back-bell" style="background-image: url(<?php echo e($item->image); ?>)">
                                    <div class="name-price">
                                        <div class="name"><?php echo e($item->name); ?></div>
                                        <div class="price"><?php echo e($item->pricetext); ?>

                                            <img src="images/thin-arrow.svg" alt="">
                                        </div>
                                    </div>
                                    <div class="weight"><?php echo e($item->weight); ?>кг</div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="col-4">

                    <div class="col-3 block-to-center count-line">
                        <hr>
                        <div class="line-elem" style="left: 0">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-1 flex-horiz-center">
                <button id="best-bells-right" class="transparent-button" onclick="shiftTopProducts(-1)">
                    <img src="images/arrow-right.svg" alt="">
                </button>
            </div>
        </div>
        <div class="video-block col-9 block-to-center">

            <div class="text-center color-white">
                <h1>Как правильно выбрать колокол в подарок?</h1>
                <h3 class="line-height-0">Обзор настольных колоколов от Андрея Подорожного</h3>
            </div>
            <img src="images/video-cover.jpg" alt="">
            <div class="arrow-down">
                <img src="images/arrow-down.svg" alt="">
            </div>
            <div class="todo-button block-to-center">Выбрать колокол</div>
        </div>
    </div>

    <div class="block">

    </div>

    <div class="block brown-back" id="main-slider">
        <div class="slide-line" style="left: 0">
            <div class="slide" style="background-image: url(images/slider/slide-1.jpg)">
                <div class="slider-text col-6 color-white">
                    Подарок на юбилей 50 лет: Колокол - “Николай Чудотворец”
                    Подставка в комплекте. Вес - 1кг.
                </div>
            </div>
            <div class="slide" style="background-image: url(images/slider/slide-1.jpg)">
                <div class="slider-text col-6 color-white">
                    Подарок на юбилей 50 лет: Колокол - “Николай Чудотворец”
                    Подставка в комплекте. Вес - 1кг.
                </div>
            </div>
        </div>
        <div class="slider-buttons">
            <button id="slider-left" class="transparent-button">
                <img style="transform: rotate(180deg);" src="images/arrow-right.svg" alt=""
                    onclick="slideToShift ('main-slider', 1)">
            </button>
            <button id="slider-right" class="transparent-button" onclick="slideToShift ('main-slider', -1)">
                <img src="images/arrow-right.svg" alt="">
            </button>
        </div>
    </div>

    <?php echo $__env->make('layouts.shop', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


</div>
<?php $__env->stopSection(); ?>

<script>
    // Функция смещения слайдера
    // главный div с фиксированной шириной
    // внутри лента из элементов
    // (parentElemID, , direction, )
    function slideToShift (parentElemID, direction, step=1) {
        let parentElem = document.getElementById(parentElemID)

        let lineElem = parentElem.firstElementChild
        let stepWidth = parentElem.clientWidth / step //если на экране 2 слайда и нужно сместить на 1 слайд
        let leftStyle = lineElem.style.left ? parseFloat(lineElem.style.left) : 0

        let slidersCount = lineElem.children.length
        // let slideWidth = lineElem.children[0].clientWidth

        let minLeft = -((slidersCount - step) * stepWidth)
        let maxLeft = 0

        let newLeft = leftStyle + direction * stepWidth

        if (newLeft <= maxLeft && newLeft >= minLeft) {
            lineElem.style.left = newLeft
            return true
        }

    }

    // Обработчик нажатия на кнопки вправо - влево в блоке популярной продукции
    function shiftTopProducts(direction) {
    if (slideToShift ('best-cards-block', direction, 2)) {
        // если был шифт, то сдвинем нижнюю полоску 
        let lineElem = document.querySelector('.line-elem')
        let lineElemLeft = lineElem.style.left ? parseFloat(lineElem.style.left) : 0
        let maxLenghtLineElem =document.querySelector('.count-line').clientWidth
        lineElem.style.left = lineElemLeft - direction * lineElem.clientWidth   
    }

    }   

    document.addEventListener ('DOMContentLoaded', function (){

        let cardsCount = document.querySelectorAll('.best-card').length
        let maxLenghtLineElem =document.querySelector('.count-line').clientWidth
        document.querySelector ('.line-elem').style.width = parseFloat(maxLenghtLineElem)/(cardsCount-1)
    })
</script>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/pavelkatunin/Documents/bells.ikatunin.ru/resources/views/welcome.blade.php ENDPATH**/ ?>