<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
            [
                'attribute' => 'id',

            ],
            'name',

            ['class' => 'yii\grid\ActionColumn',

                'template' => '{delete}  ',
                'buttons' => [
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
                        $urlforjs = Url::to(['product/delete', 'id' => $model->id], true);
                        $urlforcheck = Url::to(['product/candelete', 'id' => $model->id], true);
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
                                            alert('Нельзя удалить т.к товар находится в одном из справочников');
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
