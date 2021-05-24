<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Route */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Routes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="route-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
        ],
    ]) ?>

    <span class="btn pull-left"> <?= Html::a( 'На главную', ('/route/index'),
            ['class'=>'btn btn-danger',]) ?>
    </span>
    <span class="btn pull-right">  <?= Html::a('Изменить',
            ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </span>
    <br>
    <br>
    <br>
    <br>
    <div id="map" style="width:450px;height:300px"></div>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=1b193296-6d10-4cac-944a-2221b7a6efd5" type="text/javascript"></script>
    <script src="https://yandex.st/jquery/2.2.3/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        ymaps.ready(function () {
            var id1=[] ;

            <?if($map !=null):?>
            <?php foreach ($map as $key=>$one): ?>
            id1['<?=$key?>']='<?=$one?>';
            <?endforeach;?>
            <?php endif; ?>
            var myMap = new ymaps.Map('map', {
                center: id1[2],
                zoom: 9,
                controls: []
            });

            // Создание экземпляра маршрута.
            var multiRoute = new ymaps.multiRouter.MultiRoute({
                // Точки маршрута.
                // Обязательное поле.
                referencePoints:
                id1
                ,    params: {
                    avoidTrafficJams: true,
                    reverseGeocoding: true
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
</div>
<br>
<br>
<br>
<span class="btn pull-left">
<?= Html::a(('Добавить точку маршрута'),
    ['create_one','id_route'=>$model->id],
    ['class'=>'btn btn-success']) ?>
</span>
<br>
<br>
<br>
<div class="order-of--route-index">

    <?php Pjax::begin(['id'=>'pjax-container']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' =>  '(Не задано)'],
        'summary' =>"Показаны записи {begin} - {end} из {totalCount} ",
        'emptyText' => 'Записи не найдены',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            ['attribute'=>'id_shop','value'=>function($model)
            {
                return $model->shop_name;
            },'filter'=>\app\models\Shop::getList_shops()],
            'NPP',

            ['class' => 'yii\grid\ActionColumn',

                'template' => ' {down}&nbsp{up} {delete}  ',

                'buttons' => [
                    'down' => function ($url, $model, $key)  {
                        $iconName = "glyphicon glyphicon-arrow-down";
                        //Текст в title ссылки, что виден при наведении
                        $title =  'down';
                        $id = 'info-3'.$key;
                        $options = [
                            'title' => $title,
                            'aria-label' => $title,
                            'data-pjax' => '1',
                            'id' => $id,
                        ];
                        //Для стилизации используем библиотеку иконок
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                        $urlforjs = Url::to(['route/change_position_down', 'id' => $model->id], true);

                        //Обработка клика на кнопку
                        $js = <<<JS
                    $("#{$id}").on("click",function(event)
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
                    );
JS;


                        //Регистрируем скрипты
                        $this->registerJs($js, \yii\web\View::POS_READY, $id);

                        return Html::button($icon,$options);
                    },

                    'up' => function ($url, $model, $key)  {
                        $iconName = "glyphicon glyphicon-arrow-up";
                        //Текст в title ссылки, что виден при наведении
                        $title =  'up';
                        $id = 'info-2'.$key;
                        $options = [
                            'title' => $title,
                            'aria-label' => $title,
                            'data-pjax' => '1',
                            'id' => $id,
                        ];
                        //Для стилизации используем библиотеку иконок
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                        $urlforjs = Url::to(['route/change_position_up', 'id' => $model->id], true);

                        //Обработка клика на кнопку
                        $js = <<<JS
                    $("#{$id}").on("click",function(event)
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
                    );
JS;


                        //Регистрируем скрипты
                        $this->registerJs($js, \yii\web\View::POS_READY, $id);

                        return Html::button($icon,$options);
                    },

                    'delete' => function ($url, $model, $key) {
                        $iconName = "glyphicon glyphicon-trash";
                        //Текст в title ссылки, что виден при наведении
                        $title =  'Delete';
                        $id = 'info-'.$key;
                        $options = [
                            'title' => $title,
                            'aria-label' => $title,
                            'data-pjax' => '2',
                            'id' => $id,
                        ];
                        //Для стилизации используем библиотеку иконок
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                        $urlforjs = Url::to(['route/delete_one', 'id' => $model->id], true);

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

    <?php Pjax::end(); ?>

</div>
