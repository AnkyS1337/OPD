<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Shop */
/* @var $form yii\widgets\ActiveForm */
?>
<style>

#messageHeader {
    height: 20px;
}

#footer {
    width: 376px;
    background-color: #f2f2ef;
    padding: 12px;
}

#message {
    height: 76px;
}

#map {
    height: 160px;
    width: 376px;
    margin: 0px 12px 18px 12px;
    position: relative;
}

#header {
    height: 28px;
    width: 376px;
    margin: 12px 10px 12px 12px;
}

#button {
    display: inline-block;
    font-size: 11px;
    color: rgb(68,68,68);
    text-decoration: none;
    user-select: none;
    padding: .2em 0.6em;
    outline: none;
    border: 1px solid rgba(0,0,0,.1);
    border-radius: 2px;
    background: rgb(245,245,245) linear-gradient(#f4f4f4, #f1f1f1);
    transition: all .218s ease 0s;
    height: 28px;
    width: 74px;
}

#button:hover {
    color: rgb(24,24,24);
    border: 1px solid rgb(198,198,198);
    background: #f7f7f7 linear-gradient(#f7f7f7, #f1f1f1);
    box-shadow: 0 1px 2px rgba(0,0,0,.1);
}

#button:active {
    color: rgb(51,51,51);
    border: 1px solid rgb(204,204,204);
    background: rgb(238,238,238) linear-gradient(rgb(238,238,238), rgb(224,224,224));
    box-shadow: 0 1px 2px rgba(0,0,0,.1) inset;
}

.input {
    height: 18px;
    margin-right: 10px;
    width: 277px;
    padding: 4px;
    border: 1px solid #999;
    border-radius: 3px;
    box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0);
    transition: .17s linear;
}

.input:focus {
    outline: none;
    border: 1px solid #fdd734;
    box-shadow: 0 0 1px 1px #fdd734;
}

.input_error, .input_error:focus {
    outline: none;
    border: 1px solid #f33;
    box-shadow: 0 0 1px 1px #f33;
}
#notice
{
    color: red;
}
</style>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=1b193296-6d10-4cac-944a-2221b7a6efd5" type="text/javascript"></script>
<script src="https://yandex.st/jquery/2.2.3/jquery.min.js" type="text/javascript"></script>
<script>
ymaps.ready(init);

function init() {
	
	if ($('#shop-address').val()!= null) geocode();
	
// Подключаем поисковые подсказки к полю ввода.
var suggestView = new ymaps.SuggestView('shop-address', 
{
    boundedBy: 
            [
            [48.728611, 134.560810],
            [47.260741, 134.884552]]
    }),
map,
placemark;

// При клике по кнопке запускаем верификацию введёных данных.
$('#button').on('click', function (e) {
geocode();
});

function geocode() {
// Забираем запрос из поля ввода.
var request = $('#shop-address').val();
// Геокодируем введённые данные.
ymaps.geocode(request).then(function (res) {
    var obj = res.geoObjects.get(0),
        error, hint;
       showMessage(obj.geometry._coordinates.toString());

    if (obj) {
        // Об оценке точности ответа геокодера можно прочитать тут: https://tech.yandex.ru/maps/doc/geocoder/desc/reference/precision-docpage/
        switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
            case 'exact':
                break;
            case 'number':
            case 'near':
            case 'range':
                error = 'Неточный адрес, требуется уточнение';
                hint = 'Уточните номер дома';
                break;
            case 'street':
                error = 'Неполный адрес, требуется уточнение';
                hint = 'Уточните номер дома';
                break;
            case 'other':
            default:
                error = 'Неточный адрес, требуется уточнение';
                hint = 'Уточните адрес';
        }
    } else {
        error = 'Адрес не найден';
        hint = 'Уточните адрес';
    }

    // Если геокодер возвращает пустой массив или неточный результат, то показываем ошибку.
    if (error) {
        showError(error);
    } else {
        showResult(obj);
    }
}, function (e) {
    console.log(e)
})

}
function showResult(obj) {
// Удаляем сообщение об ошибке, если найденный адрес совпадает с поисковым запросом.
$('#shop-address').removeClass('input_error');
$('#notice').css('display', 'none');

var mapContainer = $('#map'),
    bounds = obj.properties.get('boundedBy'),
// Рассчитываем видимую область для текущего положения пользователя.
    mapState = ymaps.util.bounds.getCenterAndZoom(
        bounds,
        [mapContainer.width(), mapContainer.height()]
    ),
// Сохраняем полный адрес для сообщения под картой.
    address = [obj.getCountry(), obj.getAddressLine()].join(','),
// Сохраняем укороченный адрес для подписи метки.
    shortAddress = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');
// Убираем контролы с карты.
mapState.controls = [];
// Создаём карту.
createMap(mapState, shortAddress);
// Выводим сообщение под картой.
}

function showError(message) {
$('#notice').text(message);
$('#shop-address').addClass('input_error');
$('#notice').css('display', 'block');
// Удаляем карту.
if (map) {
    map.destroy();
    map = null;
}
}
function createMap(state, caption) {
// Если карта еще не была создана, то создадим ее и добавим метку с адресом.
if (!map) {
    map = new ymaps.Map('map', state);
    placemark = new ymaps.Placemark(
        map.getCenter(), {
            iconCaption: caption,
            balloonContent: caption
        }, {
            preset: 'islands#redDotIconWithCaption'
        });
    map.geoObjects.add(placemark);
    // Если карта есть, то выставляем новый центр карты и меняем данные и позицию метки в соответствии с найденным адресом.
} else {
    map.setCenter(state.center, state.zoom);
    placemark.geometry.setCoordinates(state.center);
    placemark.properties.set({iconCaption: caption, balloonContent: caption});

}
}

function showMessage(message) {
$('#shop-coord').val(message);

}
}</script>


<div class="shop-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_entity')->dropDownList(\app\models\Entity::getList_entity()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    

    <div class="form-group field-shop-address required">
    <label class="control-label" for="shop-address">Адрес</label>
    <input type="text" id="shop-address" class="form-control" value="<?=$model->address?>" name="Shop[address]" aria-required="true">

    <div class="help-block"></div>
    </div>
<p id="notice">Адрес не найден</p>

<div id="map"></div>
<input type="button" id="button" value="Проверить"></input>
<div class="form-group field-shop-coord">
<input type="text" id="shop-coord" class="form-control" name="Shop[coord]" style="display: none;">

<div class="help-block"></div>
</div>
    <?= $form->field($model, 'payment_method')->dropDownList(\app\models\Shop::getType_of_payment_method())?>
    <div class="form-group">
        <span class="btn pull-left"><?= Html::a( 'Назад', ('/shop/index'),
                ['class'=>'btn btn-danger']) ?></span>
        <span class="btn pull-right"><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </span>
    </div>

    <?php ActiveForm::end(); ?>

</div>
