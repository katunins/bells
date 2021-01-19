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
    <div class="basket-card" price={{ $item->orderSumm }} quantity={{ $item->quantity }} data-id={{ $item->id }}>
      <button class="remove" onclick="changeQuantity({{ $item->id }}, 0)">x</button>
      <div class="title-image" style="background-image: url({{ json_decode($item->productParams['images'])[0] }});">
      </div>
      <div class="data-group">
        <h3>{{ $item->productParams['title'] }}</h3>
        <p>{{ $item->productParams['description'] }}</p>
        <p>Подставка: {{ $item->stand }}</p>
        <p>Вес: {{ $item->productParams['weight'] }} кг.</p>
        <br>


      </div>
      <div class="param-group">
        <div class="big-number"><span class="big-quantity">{{ $item->quantity }}</span> шт</div>
        <p>
          <button onclick="changeQuantity({{ $item->id}}, -1)">-</button>
          <button onclick="changeQuantity({{ $item->id}}, 1)">+</button>
        </p>
        <div class="big-number">{{ number_format($item->orderSumm, 0, '', ' ') }} ₽</div>
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

    {{-- <div class="price-block">
        <h2>Выберете вариант оплаты</h2>
        
      </div> --}}

    <input type="hidden" name="deliveryPrice" value=0>
    <input type="hidden" name="basketPrice" value=0>

  </form>
  <div id="forpvz" class="hide"></div>
  <div id="deliveryInfo"></div>

  <div class="tel-block">
    <h2>Введите номер телефона</h2>
    <input type="tel" name="tel" placeholder="+7" id="tel">
  </div>


  <div class="toOrder">

    <button id="toOrderButton" class="hide">Заказать</button>
    <div id="orderSumm"></div>
  </div>

  @else
  {{-- Корзина пустая --}}
  <h1>Корзина пустая</h1>
  <a href="/#shop">+ Добавить колокол</a>
  @endif


</div>
@endsection

<script type="text/javascript">
  function changeQuantity(id, direction) {
    ajax ('changeCartProduct',{
        id: id,
        direction: direction
      }, function(result){
      if (result !==false) {
        let basketCard = document.querySelector('.basket-card[data-id="'+id+'"]')
        if (result==0) {
          basketCard.parentNode.removeChild(basketCard)
          if (document.querySelectorAll('.basket-card').length == 0) location = location
        } else {
          basketCard.setAttribute('quantity', result)
          basketCard.querySelector('.big-quantity').innerHTML = result
        }
        priceRecalc()
      }
    })
  }

  function setCursorPosition (pos, elem) {
  elem.focus ();
  if (elem.setSelectionRange) elem.setSelectionRange (pos, pos);
  else if (elem.createTextRange) {
    var range = elem.createTextRange ();
    range.collapse (true);
    range.moveEnd ('character', pos);
    range.moveStart ('character', pos);
    range.select ();
  }
}
  function mask (event) {
  var matrix = '+7 (___) ___-____',
    i = 0,
    def = matrix.replace (/\D/g, ''),
    val = this.value.replace (/\D/g, '');
  if (def.length >= val.length) val = def;
  this.value = matrix.replace (/./g, function (a) {
    return /[_\d]/.test (a) && i < val.length
      ? val.charAt (i++)
      : i >= val.length ? '' : a;
  });
  if (event.type == 'blur') {
    if (this.value.length == 2) this.value = '';
  } else setCursorPosition (this.value.length, this);
}
  
  function cdekSelected (wat) {
    document.getElementById('deliveryInfo').innerHTML = 'Доставка: ' + (wat.id=='courier'?'курьером':'пункт выдачи заказов '+wat.PVZ.Address)+ ', '+'<b>'+wat.price+' руб.</b> '+'<button onclick="document.getElementById(`forpvz`).className=``">изменить</button>'
    document.getElementById('forpvz').className='hide'
    document.querySelector('input[name="deliveryPrice"]').value=wat.price
    priceRecalc()
  }

  function turnOnCdek() {
      if (typeof ourWidjet === 'undefined') {
        window.ourWidjet = new ISDEKWidjet ({
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
            onChooseProfile: function(wat) {
              cdekSelected(wat)
            },
            
        });
        ourWidjet.binders.add(function(wat){
          cdekSelected(wat)
        }, 'onChoose');
      }
    }
  
  function priceRecalc () {
    let basketPrice = 0
    document.querySelectorAll('.basket-card').forEach(el=>{
      basketPrice += el.getAttribute('price')*el.getAttribute('quantity')
    })
    document.querySelector('input[name="basketPrice"]').value=basketPrice
    let orderSumm = Number(basketPrice)+Number(document.querySelector('input[name="deliveryPrice"]').value)
    document.getElementById('orderSumm').innerHTML = orderSumm.toLocaleString()+' ₽'
    document.getElementById('toOrderButton').className=''
  }
  
  function deliveryChecked() {
    let elem = document.getElementById('forpvz')
    if (event.target.value=='cdek') {
        elem.className=''
        turnOnCdek()
      } else {
        elem.className='hide'
        document.getElementById('deliveryInfo').innerHTML=''
        document.querySelector('input[name="deliveryPrice"]').value=0
        priceRecalc()
        }

    priceRecalc()
  }

    document.addEventListener ('DOMContentLoaded', function () {
    const elems = document.getElementById ('tel');
    elems.addEventListener ('input', mask);
    elems.addEventListener ('focus', mask);
    elems.addEventListener ('blur', mask);
    })
  
</script>