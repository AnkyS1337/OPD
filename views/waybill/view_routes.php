<?php
 use yii\helpers\Html;
 use yii\helpers\Json;
 use yii\web\YiiAsset;
 use yii\web\AssetBundle;
?>
<html>
<head>
</head>
<body>
<div class='site-index'>
    <div>
        <h1 style="text-align:left">Погрузочные листы</h1>
    </div>
    <? YiiAsset::register($this); ?>
    <div class="row">
        <?foreach ($waybills_for_push as $key=>$waybill_for_push):?>
            <h2>Дата выполнения:<?=$waybill_for_push['date']?> </h2>
            <h3>Название маршрута:<?=$waybill_for_push['name']?> </h3>
        <h3>Водитель:<?=$waybill_for_push['driver'] ?> </h3>
            <h3>Телефон водителя:<?=$waybill_for_push['phone'] ?> </h3>


            <?= \yii\helpers\Html::a('PDF',['pdf',
                    'id'=>$key,
                    'date'=>$waybill_for_push['date'],'driver'=>$waybill_for_push['driver'],
                    'phone'=>$waybill_for_push['phone'],
                ],
                    ['class'=>'btn btn-default','target'=>'_blank']) ?>
            <br>
        <?endforeach;?>
    </div>
</div>
</body>
</html>


