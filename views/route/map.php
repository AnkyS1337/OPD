<??>
<div id="map" style="width:450px;height:300px"></div>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=1b193296-6d10-4cac-944a-2221b7a6efd5" type="text/javascript"></script>
<script src="https://yandex.st/jquery/2.2.3/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
ymaps.ready(function () {
    var myMap = new ymaps.Map('map', {
        center: [48.536118,135.052693],
        zoom: 9,
        controls: []
    });
    var id1=[] ;
    <?php foreach ($map as $key=>$one): ?>
        id1['<?=$key?>']='<?=$one?>';
    <?endforeach;?>
    // Создание экземпляра маршрута.
    var multiRoute = new ymaps.multiRouter.MultiRoute({   
        // Точки маршрута.
        // Обязательное поле. 
        referencePoints:
        id1
        ,    params: {
            avoidTrafficJams: true
        }
    }, {
      // Автоматически устанавливать границы карты так,
      // чтобы маршрут был виден целиком.
      boundsAutoApply: true
});

    // Добавление маршрута на карту.
    myMap.geoObjects.add(multiRoute);
});
</script>
