<?php

use app\models\Shop;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ShopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Точки продаж';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить точку продаж', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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

            ['attribute'=>'id_entity','value'=>function($model){
            return Shop::getEntity_name($model['id_entity']);}
                ,'filter'=> \app\models\Entity::getList_entity()],
            [
                'attribute' => 'name',
                'filter' => null,
                'filterInputOptions' => ['style'=>"display: none;", 'id' => "inputgg"]
            ],
            [
                'attribute' => 'address',
                'filter' => null,
                'filterInputOptions' => ['style'=>"display: none;", 'id' => "inputg"]
            ],
            ['attribute'=>'payment_method','value'=>function($model){
                return Shop::getName_of_payment_method($model['payment_method']);}
                ,'filter'=> Shop::getType_of_payment_method()],

            ['class' => 'yii\grid\ActionColumn',

                'template' => ' {update}&nbsp  {view}&nbsp&nbsp{delete}  ',
                'buttons' => [
                    'view' => function ($url, $model, $key)  {
                        // Yii::$app->session['model1']=$model;
                        return Html::a('', ['shop/view', 'id' => $model['id']],['class' => 'glyphicon glyphicon-eye-open','data-pjax' => 0]);
                    },
                    'update' => function ($url, $model, $key)  {
                        // Yii::$app->session['model1']=$model;
                        return Html::a('', ['shop/update', 'id' => $model['id']],['class' => 'glyphicon glyphicon-pencil', 'data-pjax' => 0]);
                    },
                    'delete' => function ($url, $model, $key) {
                        $iconName = "glyphicon glyphicon-trash";
                        //Текст в title ссылки, что виден при наведении
                        $title = 'Удалить';
                        $id = 'info-'.$key;
                        $options = [
                            'title' => $title,
                            'aria-label' => $title,
                            'data-pjax' => '1',
                            'id' => $id,
                        ];
                        //Для стилизации используем библиотеку иконок
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                        $urlforjs = Url::to(['shop/delete', 'id' => $model['id']], true);
                        //Обработка клика на кнопку
                        $js = <<<JS
                    $("#{$id}").on("click",function(event)
                    {
                                            if(confirm("Вы уверены что хотите удалить?"))
                                            {
                                                $.ajax
                                                ({
                                                    url: "$urlforjs",
                                                    type: "POST",
                                                    success: function() {
                                                        $.pjax.reload({container: '#pjax-container', async:false});
                                                    },
                                                })
                                            }
                    }
                    );
JS;


                        //Регистрируем скрипты
                        $this->registerJs($js, \yii\web\View::POS_READY, $id);

                        return Html::button($icon,$options);
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
                if (!$("#inputfg").length)
                {
                    $('<input>').attr({
                        type: 'text',
                        id: 'inputfg',
                        autofocus: true,
                        name: 'bar'}).insertBefore($("#inputgg"));
                }

                if (!$("#inputf").length)
                {
                    $('<input>').attr({
                        type: 'text',
                        id: 'inputf',
                        autofocus: true,
                        name: 'bar'}).insertBefore($("#inputg"));
                }

                $('#inputfg').val($('#inputgg').val());
                $('#inputf').val($('#inputg').val());


                $('#inputfg').keyup(function() {
                    if(timeout) {

                        $('#inputfg').focus();
                        clearTimeout(timeout);
                        timeout = null;

                    }
                    $('#inputgg').val($('#inputfg').val());




                    timeout = setTimeout(updateGrid,700);
                });

                $('#inputf').keyup(function() {
                    if(timeout) {

                        $('#inputf').focus();
                        clearTimeout(timeout);
                        timeout = null;

                    }
                    $('#inputg').val($('#inputf').val());




                    timeout = setTimeout(updateGrid,700);
                });
            });
        }

    </script>
    <?php Pjax::end(); ?>

    <script>
        // var p;
        function checkVariable(){
            if ( window.$){
                if ($.fn.yiiGridView())
                {
                    try {updateGrid();} catch (err) {
                        setTimeout(updateGrid,500);
                    }
                }
            }
            else
            {
                window.setTimeout("checkVariable()",100);
            }
        }

        // var p =
        checkVariable();
    </script>

</div>
