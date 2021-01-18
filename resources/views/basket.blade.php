<link rel="stylesheet" href="css/basket.css">
<script id="ISDEKscript" type="text/javascript" src="https://widget.cdek.ru/widget/widjet.js" charset="utf-8"></script>

@extends('layouts.app')
@section('container')

{{-- Illuminate\Support\Collection {#301 ▼
    #items: array:1 [▼
      0 => {#303 ▼
        +"id": 1
        +"userToken": "rJIiJuOwAMTF7sQ9A88sxi0i7vnNcZ4jT2fK38vx"
        +"productId": "2"
        +"stand": "Без подставки"
        +"orderSumm": 1990
        +"quantity": 1
        +"created_at": "2021-01-15 05:54:57"
        +"updated_at": null
        +"productParams": array:10 [▼
          "id" => 1
          "quantity" => 0
          "title" => "Колокол"
          "description" => "Этот колокол я взял для теста. Тестовый колокол лучше нигде не применять, так как он не настоящий и сделан только ради того, что бы подстроить алгоритмы и верст ▶"
          "price" => 5990
          "weight" => 0.3
          "filter" => "{"themes": ["Тестовый", "Красивый"], "mission": ["Подарок руководители", "Крестины"]}"
          "images" => "["/storage/productImages/1/4ixcf5wF7hBvPQBzrUpxoNNPpXAvQu9diHAJ8k7o.jpeg", "/storage/productImages/1/dww99pb1FmDPLgfwZbeKsz0JhdDpvxBlgp1ytzwl.jpeg", "/storage/p ▶"
          "created_at" => "2020-12-17 08:41:44"
          "updated_at" => "2020-12-23 05:01:25"
        ]
      }
    ]
  } --}}

<div class="container">

  @if ($collection->count() > 0)
  {{-- Корзина --}}
  <h1>Корзина</h1>
  <div class="cart-block">
    @foreach ($collection as $item)
    <div class="basket-card" price={{ $item->productParams['price'] }} quantity={{ $item->quantity }}>
      <button class="remove" onclick="removeGood({{ $item->id }})">x</button>
      <div class="title-image" style="background-image: url({{ json_decode($item->productParams['images'])[0] }});">
      </div>
      <div class="data-group">
        <h3>{{ $item->productParams['title'] }}</h3>
        <p>{{ $item->productParams['description'] }}</p>
        <p>Подставка: {{ $item->stand }}</p>
        <p>Вес: {{ $item->productParams['weight'] }} кг.</p>
        <br>
        {{-- <p>Количество: <span>{{ $item->quantity }}</span> шт.</p> --}}


      </div>
      <div class="param-group">
        <div class="big-number"><span>{{ $item->quantity }}</span> шт</div>
        <p>
          <button onclick="changeQuantity({{ $item->id, -1 }})">-</button>
          <button onclick="changeQuantity({{ $item->id, 1 }})">+</button>
        </p>
        <div class="big-number">{{ number_format($item->productParams['price'], 0, '', ' ') }} ₽</div>
      </div>
    </div>
    {{-- @dump($item) --}}
    @endforeach
  </div>
  <form action="" method="POST">

    <h2>
      Выберите вариант доставки
    </h2>
    <div class="delivery-block">

      <input type="radio" name="delivery" id="no-delivery" value="no-delivery" onchange="deliveryChecked()">
      <label for="no-delivery">
        <h5>Фирменный магазин</h5>
        <p>г. Воронеж, ул. Фридриха Энгельса 71</p>
      </label>

      <input type="radio" name="delivery" id="cdek" value="cdek" onchange="deliveryChecked()">
      <label for="cdek">
        <h5>Доставка CDEK</h5>
        <p>В ближайший пункт выдачи или курьером домой</p>
      </label>

      
    </div>

    <div class="price-block">
      <h2>Выберете вариант оплаты</h2>
      <h3>1100</h3>
    </div>

    <input type="hidden" name="deliveryPrice" value=0>
    <input type="hidden" name="basketPrice" value=0>

  </form>
  <div id="forpvz" class="hide" style="width:100%; height:600px;"></div>
  @else
  {{-- Корзина пустая --}}
  <h1>Корзина пустая</h1>
  <a href="/#shop">+ Добавить колокол</a>
  @endif


</div>
@endsection

<script type="text/javascript">
  var ourWidjet = new ISDEKWidjet ({
      defaultCity: 'Нижний Новгород', //какой город отображается по умолчанию
      cityFrom: 'Воронеж', // из какого города будет идти доставка
      country: 'Россия', // можно выбрать страну, для которой отображать список ПВЗ
      link: 'forpvz', // id эл=емента страницы, в который будет вписан виджет
      path: '/cdek/widget/scripts/', //директория с библиотеками
      servicepath: '/cdek/service.php', //ссылка на файл service.php на вашем сайте
      hidedress: true,
      hidecash: true,
      // hidedelt: true,
      detailAddress: true,
      // popup: true,
      goods: [{
           length: 20,
           width: 20,
           height: 15,
           weight: 2
       }],

  });

  function priceRecalc () {
    let basketPrice = 0
    document.querySelectorAll('.basket-card').forEach(el=>{
      basketPrice += el.getAttribute('price')*el.getAttribute('quantity')
    })
    (basketPrice)

  }
  
  function deliveryChecked() {
    let elem = document.getElementById('forpvz')
    if (event.target.value=='cdek') elem.className=''; else elem.className='hide';
    priceRecalc()
  }

  // document.addEventListener('DOMContentLoaded', function (){
  //   document.querySelectorAll('input[name="delivery"]').forEach(el=>{
  //     el.onchange = () => {
  //       let elem = document.getElementById('forpvz')
  //       if (el.value=='cdek') {
  //         if (elem.classList.contains ('hide')) elem.classList.remove ('hide'); 
  //       } else elem.classList.add ('hide');
  //     }  
  //   })
  // })
  
</script>