<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Юридические лица';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить юридическое лицо', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    

    <?php Pjax::begin(['id'=>'pjax-container']); ?>

    <!-- <?php  //echo $this->render('_search', ['model' => $searchModel]); ?> -->
    <?= GridView::widget([
        'id' => 'Grid1',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' =>  '(Не задано)'],
        'summary' =>"Показаны записи {begin} - {end} из {totalCount} ",
        'emptyText' => 'Записи не найдены',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'filter' => null,
                'filterInputOptions' => ['style'=>"display: none;", 'id' => "inputg"]
            ],
            // ['attribute'=>'name','filter'=>function(){
            //     return[];
            // }],

            ['class' => 'yii\grid\ActionColumn',

                'template' => '   {view}&nbsp&nbsp{delete}  ',
                'buttons' => [
                    'view' => function ($url, $model, $key)  {
                        // Yii::$app->session['model1']=$model;
                        return Html::a('', ['entity/view', 'id' => $model['id']],['class' => 'glyphicon glyphicon-eye-open','data-pjax' => 0]);
                    },
//                    'update' => function ($url, $model, $key)  {
//                        // Yii::$app->session['model1']=$model;
//                        return Html::a('', ['entity/update', 'id' => $model['id']],['class' => 'glyphicon glyphicon-pencil', 'data-pjax' => 0]);
//                    },
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
                        $urlforjs = Url::to(['entity/delete', 'id' => $model['id']], true);
                        $urlforcheck = Url::to(['entity/candelete', 'id' => $model['id']], true);
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
                                            alert('Нельзя удалить т.к у этого юридического лица заведены магазины');
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
</script>


    <?php Pjax::end(); ?>
<script>
// var p;
function checkVariable(){
            console.log(12223);
            if ( window.$){
                console.log(1);
                if ($.fn.yiiGridView())
                {
                    console.log(2);
                    try {updateGrid();} catch (err) {
                        console.log(123);
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
