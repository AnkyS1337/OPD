<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Route */

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

    <span class="btn pull-left"> <?= Html::a( 'На главную', ('/entity/index'),
            ['class'=>'btn btn-danger',]) ?>
    </span>
    <span class="btn pull-right">  <?= Html::a('Изменить',
            ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </span>
</div>
<br>
<br>
<br>
<span class="btn pull-left">
<?= Html::a(('Добавить товар и его цену для данного юридического лица'),
    ['create_one','id_entity'=>$model->id],
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

            ['attribute'=>'id_product','value'=>function($model)
            {
                return $model->product_name->name;
            },'filter'=>\app\models\Product::getList_product()],

            [
                'attribute' => 'price',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model) use ($form) {
                    $price=strtr((string)$model->price,".",",");
                    return "<input type='text' id='$model->id' size='20' value='$price' onchange='document.getElementById(\"info-2\"+$model->id).click();'";

                    }
            ],

            ['class' => 'yii\grid\ActionColumn',

                'template' => '{save}{view}&nbsp {delete}  ',
                'buttons' => [
                        'save'=>function($url,$model,$key)
                        {
                            $iconName = "glyphicon glyphicon-refresh";
                            //Текст в title ссылки, что виден при наведении
                            $title = 'Удалить';
                            $id = 'info-2'.$model->id;
                            $options = [
                                'title' => $title,
                                'aria-label' => $title,
                                'data-pjax' => '1',
                                'id' => $id,
                                'style'=>'display:none',
                            ];
                            //Для стилизации используем библиотеку иконок
                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                            $urlforjs = Url::to(['entity/update_price'], true);
                            //Обработка клика на кнопку
                            $js = <<<JS
                    $("#{$id}").on("click",function(event,model)
                    {
                      var request = $('#$model->id').val()
                                                $.ajax
                                                ({
                                                
                                                    url: "$urlforjs",
                                                    type: "POST",
                                                    data:{"id":'$model->id',"price":request},
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

                    'update' => function ($url, $model, $key)  { //Если убрать $url и $key то как то не работает)
                        return Html::a('', ['entity/update_one', 'id' => $model->id],['class' => 'glyphicon glyphicon-pencil']);
                    },
                    'view' => function ($url, $model, $key)  {

                        return Html::a('', ['entity/view_one', 'id' => $model->id],['class' => 'glyphicon glyphicon-eye-open']);

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
                        $urlforjs = Url::to(['entity/delete_one', 'id' => $model->id], true);
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
