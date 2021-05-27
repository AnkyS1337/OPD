<?

use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\form\ActiveForm as ActiveForm2;

?>
<h1>Свод по маршрутам</h1>

<? $form= ActiveForm::begin()?>
<div>
    <?= $form->field($model, 'date_range',
        [
//            'addon'=>
//                [
//                    'prepend'=>
//                        [
//                            'content'=>'<i class="fas fa-calendar-alt"></i>',
//                        ],
//                ],
//            'options'=>
//                [
//                    'class'=>'drp-container form-group'
//                ],


        ])->widget(DateRangePicker::classname(), [
        //'useWithAddon'=>true,
        'convertFormat'=>true,
        'name'=>'search',
        'pluginOptions'=>[
            'locale'=>[
                'separator'=>' до ',
                'format' => 'Y-m-d',
            ],
            'opens'=>'right'
        ]
    ])->label('Выбрать диапазон поиска'); ?>
    <span >  <?= Html::submitButton('Показать данные', ['class' => 'btn btn-primary mr-1']) ?></span>
    </span>

</div>

<? ActiveForm::end()?>


<span class="btn pull-right"><?= Html::a("Excel", ['excel_full'],
        ['class'=>'btn btn-info'])?></span>
<div>
    <div >
        <? ?>
            <h2>В количественном эквиваленте</h2>
            <table class="table table-bordered"">
            <thead>

            <tr>
                <td>№</td>
                <td>Магазин</td>
                <td >Мол&ensp;0.9&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Мол&ensp;0,5&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Твор&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Смет&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td> &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Вар&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Ряж&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Кеф&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Твор кг.&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td> &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Сме кг.&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td> &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Итого реализованно</td>
                <td>Итого возвратов</td>
                <td>Отн. откл %</td>


            </tr>
            </thead>
            <tbody>
            <?php if($new_routes != null): ?>
                <? $counter=0;?>
                <?foreach ($new_routes['waybill'] as $key_waybill=>$route):?>
                <? $counter+=1;?>
                    <tr>
                    <? $order_new=$order_push?>
                    <td><?=$counter?></td>
                    <td><?= Html::a($route['name'],['waybill/report','id'=>$key_waybill,'name'=>$route['name']])?></td>

                <? if($route['products']!=null):?>
                    <? foreach ($route['products'] as $key=>$product):?>
                        <?
                        $order_new[$key]['count'] =$product['count'];
                        $order_new[$key]['returns'] =$product['returns'];
                        ?>
                    <?endforeach;?>
                    <? foreach ($order_new as $key=>$order):?>
                        <td><h5><? if($order['count']!=0){ echo$order['count']; }else{ echo "-";}?></h5></td>
                        <td><h5><? if($order['returns']!=0){ echo$order['returns']; }else{ echo "-";}?></h5></td>
                    <?endforeach;?>
                        <? else:?>
                        <? foreach ($order_push as $order):?>
                            <td><h5><?  echo "-";?></h5></td>
                            <td><h5><? echo "-";?></h5></td>
                        <? endforeach;?>
                        <? endif;?>
                    <td><h5><?= $route['count_all_waybill']?></h5></td>
                    <td><h5><?= $route ['returns_all_waybill']?></h5></td>
                    <td><h5><?= $route ['percent']?> %</h5></td>


                <?endforeach;?>
                <tr>
                    <td></td>
                    <td>Итого</td>
                    <? $order_new_last=$order_push?>
                    <? if($new_routes['all_products']!=null):?>
                    <? foreach ($new_routes['all_products'] as $key=>$product):?>
                        <?
                        $order_new_last[$key]['count_all'] =$product['count_all'];
                        $order_new_last[$key]['returns_all'] =$product['returns_all'];
                        ?>
                    <?endforeach;?>
                <? foreach ($order_new_last as $key=>$order):?>
                    <td><h5><? if($order['count_all']!=0){ echo$order['count_all']; }else{ echo "-";}?></h5></td>
                    <td><h5><? if($order['returns_all']!=0){ echo$order['returns_all']; }else{ echo "-";}?></h5></td>
                    <?endforeach;?>
                    <td><h5><?=$new_routes['count_final']?></h5></td>
                    <td><h5><?=$new_routes['returns_final']?></h5></td>
                    <td><h5><?=$new_routes['percent_final']?>%</h5></td>
                </tr>
                <? endif;?>
            <?php else:?>
                <tr>
                    <td>Записи не найдены</td>
                </tr>
            <?php endif; ?>
            </tbody>
            </table>




        <h2>В денежном эквиваленте</h2>
        <table class="table table-bordered"">
        <thead>
        <tr>
            <td>№</td>
            <td>Магазин</td>
            <td>Мол&ensp;0.9&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td>Мол&ensp;0,5&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td>Твор&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td> &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td>Смет&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td>Вар&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td>Ряж&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td> &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td>Кеф&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td>Твор кг.&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td>Сме кг.&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
            <td>Итого реализованно</td>
            <td>Итого возвратов</td>
            <td>Отн. откл%</td>


        </tr>
        </thead>
        <tbody>
        <?php if($new_routes != null): ?>
            <? $counter=0;?>
            <?foreach ($new_routes['waybill'] as $key_waybill=>$route):?>
                <? $counter+=1;?>
                <tr>
                <? $order_new=$order_push?>
                <td><?=$counter?></td>
                <td><?= Html::a($route['name'],['waybill/report','id'=>$key_waybill,'name'=>$route['name']])?></td>
                    <? if($route['products']!=null):?>
                <? foreach ($route['products'] as $key=>$product):?>
                    <?
                    $order_new[$key]['count_money'] =$product['count_money'];
                    $order_new[$key]['returns_money'] =$product['returns_money'];
                    ?>
                <?endforeach;?>
                <? foreach ($order_new as $key=>$order):?>
                    <td><h5><? if($order['count_money']!=0){ echo$order['count_money']; }else{ echo "-";}?></h5></td>
                    <td><h5><? if($order['returns_money']!=0){ echo$order['returns_money']; }else{ echo "-";}?></h5></td>
                <?endforeach;?>
                <? else:?>
                    <? foreach ($order_push as $order):?>
                        <td><h5><?  echo "-";?></h5></td>
                        <td><h5><? echo "-";?></h5></td>
                    <? endforeach;?>
                <? endif;?>
                <td><h5><?= $route['count_money_all_waybill']?></h5></td>
                <td><h5><?= $route ['returns_money_all_waybill']?></h5></td>
                <td><h5><?= $route ['percent_money']?> %</h5></td>


            <?endforeach;?>
            <tr>
                <td></td>
                <td>Итого</td>
                <? $order_new_last=$order_push?>
            <? if($new_routes['all_products']!=null):?>
                <? foreach ($new_routes['all_products'] as $key=>$product):?>
                    <?
                    $order_new_last[$key]['count_money_all'] =$product['count_money_all'];
                    $order_new_last[$key]['returns_money_all'] =$product['returns_money_all'];
                    ?>
                <?endforeach;?>
                <? foreach ($order_new_last as $key=>$order):?>
                    <td><h5><? if($order['count_money_all']!=0){ echo$order['count_money_all']; }else{ echo "-";}?></h5></td>
                    <td><h5><? if($order['returns_money_all']!=0){ echo$order['returns_money_all']; }else{ echo "-";}?></h5></td>
                <?endforeach;?>
                <td><h5><?=$new_routes['count_money_final']?></h5></td>
                <td><h5><?=$new_routes['returns_money_final']?></h5></td>
                <td><h5><?=$new_routes['percent_money_final']?>%</h5></td>
            </tr>
        <? endif;?>
        <?php else:?>
            <tr>
                <td>Записи не найдены</td>
            </tr>
        <?php endif; ?>
        </tbody>
        </table>

    </div>
</div>

