<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\WaybillSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Погрузочные листы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waybill-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать погрузочный лист', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(['id'=>'pjax-container']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' =>  '(Не задано)',
        'dateFormat' => 'dd-MM-yyyy',],
        'summary' =>"Показаны записи {begin} - {end} из {totalCount} ",
        'emptyText' => 'Записи не найдены',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'id_driver','value'=>function($model){return $model->drivers_name->name;},'filter'=>\app\models\Drivers::getList_drivers()],
            ['attribute'=>'id_route','value'=>function($model){return $model->route_name->name;},'filter'=>\app\models\Route::getList_routes()],

            'date:date',
            //'deleted',

            ['class' => 'yii\grid\ActionColumn',

                'template' => ' {view}&nbsp&nbsp{delete}  ',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        $iconName = "glyphicon glyphicon-trash";
                        //Текст в title ссылки, что виден при наведении
                        $title =  'Delete';
                        $id = 'info-'.$key;
                        $options = [
                            'title' => $title,
                            'aria-label' => $title,
                            'data-pjax' => '1',
                            'id' => $id,
                        ];
                        //Для стилизации используем библиотеку иконок
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                        $urlforjs = Url::to(['waybill/delete', 'id' => $model->id], true);
                        $urlforcheck = Url::to(['waybill/candelete', 'id' => $model->id], true);
//                        if (array_key_exists('viewer', Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())))
//                        {
//                            $viewer=true;
//                        }

                        //Обработка клика на кнопку
                        $js = <<<JS
                    $("#{$id}").on("click",function(event)
                    {
                        var can = false;
                        $.ajax
                        (
                            
                            {
                            url: "$urlforcheck", 
                            type: 'POST',
                            success: function(result)
                                {
                                    can = Boolean(result);
                                    if (can)
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
                                    else
                                    {   
//                                        if("$viewer"== true)
//                                            {
//                                                       alert('У вас не хватает прав');
//                                            }else{
                                                       alert('Нельзя удалить т.к есть записи о ремонте');
                                            // }
                                    }
                                } 
                            }
                        )
                        
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
