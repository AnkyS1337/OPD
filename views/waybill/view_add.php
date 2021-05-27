<?php


use app\models\Shop;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax; ?>


<h1>Выберете точку, которую хотите добавить</h1>
<div class="shop-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <!-- <input type="text" id="inputf" style="margin-left: 28%; width: 300px;"></input> -->
    <?php Pjax::begin(['id'=>'pjax-container']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'id' => 'Grid1',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' =>  '(Не задано)'],
        'summary' =>"Показаны записи {begin} - {end} из {totalCount} ",
        'emptyText' => 'Записи не найдены',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'id',

            ['attribute'=>'id_entity','value'=>function($model){Yii::$app->session['123gtf']=$model;
                return \app\models\Shop::getEntity_name($model['id_entity']);}
                ,'filter'=> \app\models\Entity::getList_entity()],
                [
                    'attribute' => 'address',
                    'filter' => null,
                    'filterInputOptions' => ['style'=>"display: none;", 'id' => "inputg"]
                ],
            // 'coord',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{create}  ',
                'buttons' => [
                    'create' => function ($url, $model, $key) use($id_waybill) {
                    //Если убрать $url и $key то как то не работает)
                        return Html::a('', ['create_one','id_shop'=>$model['id'],'id_waybill'=>$id_waybill],
                            ['class' => 'glyphicon glyphicon-plus',
                                 ]);
                    },
                ],
            ],
        ],
    ]); ?>



<script>
var timeout;
function updateGrid(){
    
    $("#Grid1").yiiGridView("applyFilter");
    $(document).on('pjax:success', function() {
        if (!$("#inputf").length)
        {
            $('<input>').attr({
            type: 'text',
            id: 'inputf',
            autofocus: true,
            name: 'bar'}).insertBefore($("#inputg"));
        }
        var value = ($('#inputg').val());
        // if ($("#help").length)
        // {
        //     $("#help").remove();
        // }
        // $('<p id="help"> Поле поиска: ' + value + '</p>').insertAfter($("#inputg"));
        $('#inputf').val($('#inputg').val());
        $('#inputf').focus();
        $('#inputf').keyup(function() {
        if(timeout) {
            clearTimeout(timeout);
            timeout = null;
        }
    $('#inputg').val($('#inputf').val());
    timeout = setTimeout(updateGrid,700);
    });
});
}
window.onload = function() {
    updateGrid();
    }
</script>

    <?php Pjax::end(); ?>
    <span class="btn pull-left"><?= Html::a('Назад', ['waybill/view',
            'id' =>$id_waybill],
            ['class'=>'btn btn-danger',]) ?></span>

</div>