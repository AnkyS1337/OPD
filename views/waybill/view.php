<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use app\models\Product;

/* @var $this yii\web\View */
/* @var $model app\models\Waybill */

//$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Погрузочные листы', 'url' => ['index']];

\yii\web\YiiAsset::register($this);
?>
<div class="waybill-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
<!--        --><?//= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
<!--        --><?//= Html::a('Delete', ['delete', 'id' => $model->id], [
//            'class' => 'btn btn-danger',
//            'data' => [
//                'confirm' => 'Are you sure you want to delete this item?',
//                'method' => 'post',
//            ],
//        ]) ?>
<!--    </p>-->

    <?= DetailView::widget([
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' =>  '(Не задано)',
            'dateFormat' => 'dd-MM-yyyy',],
        'model' => $model,
        'attributes' => [

            ['attribute'=>'id_driver','value'=>function($model){return $model->drivers_name->name;}],
            ['attribute'=>'id_route','value'=>function($model){return $model->route_name->name;}],
            'date:date',
        ],
    ]) ?>
        <span class="btn pull-left"> <?= Html::a( 'На главную', ('/waybill/index'),
                ['class'=>'btn btn-danger',]) ?>
    </span>
        <span class="btn pull-right">
        <?= Html::a(('Добавить магазин'),
         ['view_add','id_waybill'=>$model->id],
            ['class'=>'btn btn-success']) ?>
</span>
</div>
<br>


<br>
<br>
<br>
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
                    $name = array_pop($model);
                    return \app\models\States_for_waybill::getShop_name_for_pdf($name['id_shop'],0);
                },'filter'=>\app\models\States_for_waybill::getList_shops($model)],
                // ['attribute'=>'id_product','value'=>function($model)
                // {
                //     return $model->product_name->name;
                // },'filter'=>\app\models\Product::getList_product()],
                // 'price_for_one',

            //    'id15',
//                'name_shop',
//                'address',
                //'type_of_payment',
                [
                    'attribute' => 'count',
                    'filter' => false,
                    'format' => 'raw',
                    'value' => function($model, $index) use ($form) {
                        $new=$model->id.'c';
                        // $index = ($model['id'] * 2);
                        $html =  "<div style='height: px; /*your fixed height*/
                        -webkit-column-count: 7;
                           -moz-column-count: 7;
                                column-count: 7; /*3 in those rules is just placeholder -- can be anything*'>";
                        $countstring = 'count';
                        $idstring = 'id';
                        foreach ($model as $one)
                        {
                            switch ($one['id_product'])
                            {
                                case 4:
                                    $new_model[1]=$one;
                                    break;
                                case 5:
                                    $new_model[2]=$one;
                                    break;
                                case 2:
                                    $new_model[3]=$one;
                                    break;
                                case 3:
                                    $new_model[4]=$one;
                                    break;
                                case 6:
                                    $new_model[5]=$one;
                                    break;
                                case 7:
                                    $new_model[6]=$one;
                                    break;
                                case 8:
                                    $new_model[7]=$one;
                                    break;
                                case 9:
                                    $new_model[8]=$one;
                                    break;
                                case 10:
                                    $new_model[9]=$one;
                                    break;
                                default: break;
                            }
                        }
                        ksort($new_model);

                        foreach($new_model as $shop)
                        {
                            $countstr = '';
                            $idstr = 'count-2'. $shop['id'] . 'c';
                            $countstr .= '"';
                            $countstr .= 'count-2'.$shop[$idstring];
                            $countstr .= '"';
                            $tabindex = $shop['NPP'] * $shop['NPP'] * 2;
                            $htmlin = '<ul style="padding-left: 0px;">';
                            // if ($index)
                            // {
                            // \Yii::$app->session['NPP0' . $index] = $index;
                            // \Yii::$app->session['tabindex' . $index] = $tabindex;
                            
                            // }

                            $productname = Product::getProduct_name($shop['id_product']);
                            $htmlin .= "<li style='display: inline-block;'> $productname  </li>";
                            $shop[$countstring]=strtr((string)$shop[$countstring],".",",");;
                            $htmlin .= "<li style='display: inline-block;'> <input  class='forma' type='text' tabindex='$tabindex' id='$idstr' size='1' value='$shop[$countstring]' onchange='document.getElementById($countstr).click();'  </li>";          
                            $htmlin .= '</ul>';
                            $html .= $htmlin;
                            // \Yii::$app->session['saas'] = $html;
                        }
                        $html .= '</div>';
                        return $html;

                    }
                ],
                [
                    'attribute' => 'returns',
                    'filter' => false,
                    'format' => 'raw',
                    'value' => function($model, $index) use ($form) {
                        foreach ($model as $one)
                        {
                            switch ($one['id_product'])
                            {
                                case 4:
                                    $new_model[1]=$one;
                                    break;
                                case 5:
                                    $new_model[2]=$one;
                                    break;
                                case 2:
                                    $new_model[3]=$one;
                                    break;
                                case 3:
                                    $new_model[4]=$one;
                                    break;
                                case 6:
                                    $new_model[5]=$one;
                                    break;
                                case 7:
                                    $new_model[6]=$one;
                                    break;
                                case 8:
                                    $new_model[7]=$one;
                                    break;
                                case 9:
                                    $new_model[8]=$one;
                                    break;
                                case 10:
                                    $new_model[9]=$one;
                                    break;
                            }
                        }
                        ksort($new_model);
                        // $new=$model->id.'c';
                        // $tabindex = ($model->id * 2);
                        $html =  "<div style='height: px; /*your fixed height*/
                        -webkit-column-count: 7;
                           -moz-column-count: 7;
                                column-count: 7; /*3 in those rules is just placeholder -- can be anything*'>";
                        $returnsstring = 'returns';
                        $idstring = 'id';
                        foreach($new_model as $shop)
                        {
                            $returnsstr = '';
                            $idstr = 'returns-2'. $shop['id'] . 'c';
                            $returnsstr .= '"';
                            $returnsstr .= 'returns-2'.$shop[$idstring];
                            $returnsstr .= '"';
                            $tabindex = ($shop['NPP']) * $shop['NPP'] * 2 + 1;
                            // if ($index)
                            // {
                            // \Yii::$app->session['NPP_r' . $index] = $index;
                            // \Yii::$app->session['tabindex_r' . $index] = $tabindex;
                            
                            // }
                            $htmlin = '<ul style="padding-left: 0px;">';
                            // \Yii::$app->session['sho2p'] = $shop;
                            $productname = Product::getProduct_name($shop['id_product']);
                            $shop[$returnsstring]=strtr((string)$shop[$returnsstring],".",",");
                            $htmlin .= "<li style='display: inline-block;'> $productname  </li>";
                            $htmlin .= "<li style='display: inline-block;'> <input class='forma' type='text' tabindex='$tabindex' id='$idstr' size='1' value='$shop[$returnsstring]' onchange='document.getElementById($returnsstr).click();'  </li>";          
                            $htmlin .= '</ul>';
                            $html .= $htmlin;
                            // \Yii::$app->session['saas'] = $html;
                        }
                        $html .= '</div>';
                        return $html;

                    }
                ],
                ['class' => 'yii\grid\ActionColumn',
                    'template' => ' ',
                    //'template' => '{count}{returns}&nbsp&nbsp{for_production}  ',

                    'buttons' => [
                        'count'=>function($url,$model,$key)
                        {
                            $iconName = "glyphicon glyphicon-refresh";
                            //Текст в title ссылки, что виден при наведении
                            $return;
                            foreach ($model as $shop)
                            {
                                $title = 'count';
                                $id = 'count-2'.$shop['id'];
                                $options = [
                                    'title' => $title,
                                    'aria-label' => $title,
                                    'data-pjax' => '1',
                                    'id' => $id,
                                    'style'=>'display:none',
                                ];
                                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                                $urlforjs = Url::to(['waybill/update_count'], true);
                                $shopidentifier = $shop['id'];
                                $js = <<<JS
                                $("#{$id}").on("click",function(event,model)
                                {
                                   var id = 'count-2' + '$shopidentifier'+'c';
                                   var request = $("#" + id).val();
                                                            $.ajax
                                                            ({
                                                            
                                                                url: "$urlforjs",
                                                                type: "POST",
                                                                data:{"id":'$shopidentifier',"count":request},
                                                                success: function() {
                                                                    // $.pjax.reload({container: '#pjax-container', async:false});
                                                                },
                                                            })
                                                     
                                }
                                );
JS;
            
            
                                        //Регистрируем скрипты
                                       
                            
                                $this->registerJs($js, \yii\web\View::POS_READY, $id);
                                $return .= Html::button($icon,$options);
                            }
                            return $return;
                        },

                        /////////////////
                        /// /
                        ///
                        ///
                        ///
                        'returns'=>function($url,$model,$key)
                        {
                            $iconName = "glyphicon glyphicon-refresh";
                            //Текст в title ссылки, что виден при наведении
                            $return;
                            foreach ($model as $shop)
                            {
                                $title = 'returns';
                                $id = 'returns-2'.$shop['id'];
                                $options = [
                                    'title' => $title,
                                    'aria-label' => $title,
                                    'data-pjax' => '1',
                                    'id' => $id,
                                    'style'=>'display:none',
                                ];
                                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                                $urlforjs = Url::to(['waybill/update_returns'], true);
                                $shopidentifier = $shop['id'];
                                $js = <<<JS
                                $("#{$id}").on("click",function(event,model)
                                {
                                   var id = 'returns-2' + '$shopidentifier'+'c';
                                   var request = $("#" + id).val();
                                                            $.ajax
                                                            ({
                                                            
                                                                url: "$urlforjs",
                                                                type: "POST",
                                                                data:{"id":'$shopidentifier',"returns":request},
                                                                success: function() {
                                                                    // $.pjax.reload({container: '#pjax-container', async:false});
                                                                },
                                                            })
                                                     
                                }
                                );
JS;
            
            
                                        //Регистрируем скрипты
                                       
                            
                                $this->registerJs($js, \yii\web\View::POS_READY, $id);
                                $return .= Html::button($icon,$options);
                            }
                            return $return;
                        },
                        //
                        ///
                        ///
                        ///
                        ///

//                        'update' => function ($url, $model, $key)  { //Если убрать $url и $key то как то не работает)
//
//                            return Html::a('', ['waybill/update_one', 'id' => $model->id],['class' => 'glyphicon glyphicon-pencil']);
//
//                        },
//                        'view' => function ($url, $model, $key)  {
//                            Yii::$app->session['model1']=$model;
//                            $model_one=array_pop($model);
//                            return Html::a('', ['waybill/view_one', 'id' => $model_one['id_shop'],'id_waybill'=> $model_one['id_waybill']],['class' => 'glyphicon glyphicon-eye-open']);
//
//                        },

                        'for_production' => function ($url, $model, $key)  {
                            Yii::$app->session['model1']=$model;
                            $model_one=array_pop($model);
                            return Html::a('', ['waybill/for_production', 'id' => $model_one['id_shop'],'id_waybill'=> $model_one['id_waybill']],['class' => 'glyphicon glyphicon-bookmark']);

                        },

                        'delete' => function ($url, $model, $key) {
                            $iconName = "glyphicon glyphicon-trash";
                            //Текст в title ссылки, что виден при наведении
                            $title = 'Delete';
                            $id = 'info-'.$key;
                            $options = [
                                'title' => $title,
                                'aria-label' => $title,
                                'data-pjax' => '1',
                                'id' => $id,
                            ];
                            //Для стилизации используем библиотеку иконок
                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                            $urlforjs = Url::to(['waybill/delete_one', 'id' => $model->id], true);
                            $urlforcheck = Url::to(['waybill/candelete_one', 'id' => $model->id], true);
//                            if (array_key_exists('viewer', Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId())))
//                            {
//                                $viewer=true;
//                            }
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

                                                    alert('У вас не хватает прав');
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
        <script>
        function checkVariable(){
            if ( window.$){
                $('.forma').on('keyup', function(event){
                if(event.keyCode==13) {
                    var currid = event.target.attributes.id.value;
                    var inputs = $('*[tabindex]').not('[tabindex=-1], [disabled], :hidden');
                    // console.log(inputs);
                    console.log($(this));
                    console.log($(this).attr ( "id" ));
                    inputs.each(function (i, val)
                    {
                        console.log($(this).attr("id") + ' this');
                        console.log(currid + ' this_id_new');
                        console.log($(val).attr("id") + ' val');
                        if (currid == $(val).attr("id"))
                        {

                            inputs[i + 1].focus();
                            return false;
                            // .next().focus();
                        }
                        
                    });
                }
                });
            }
            else{
                window.setTimeout("checkVariable();",100);
            }
            }
        checkVariable();
        </script>
</div>
