<?php

use yii\helpers\Html;

?>
<span class="btn pull-left"><?= Html::a('Назад', ['waybill/report_full',],
        ['class'=>'btn btn-danger',]) ?></span>
<br>
<br>
<h1>Свод по маршруту '<?=$name?>'.</h1>
<div>
    <div >
        <?foreach ($new_waybills as $waybill):?>
        <h1>Дата маршрута: <?= $waybill['date']?></h1>
        <h2>В количественном эквиваленте</h2>
        <table class="table table-bordered"">
            <thead>
            <tr>
                <td>№</td>
                <td>Магазин&ensp;&ensp;&ensp;</td>
                <td>Мол&ensp;0.9&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Мол&ensp;0,5&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Твор&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Смет&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Вар&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Ряж&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Кеф&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Твор кг.&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;</td>
                <td>Сме кг.&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;</td>
                <td>Итого реализованно</td>
                <td>Итого возвратов</td>
                <td>Отн откл %</td>


            </tr>
            </thead>
            <tbody>
            <?php if($waybill['shops'] != null): ?>
                <?foreach ($waybill['shops'] as $shop):?>
                    <tr>
                    <? $order_new=$order_push?>
                    <td><?=$shop['NPP']?></td>
                    <td><?= $shop['name']?></td>
                    <? foreach ($shop['products'] as $key=>$product):?>
                    <?
                        $order_new[$key]['count'] =$product['count'];
                        $order_new[$key]['returns'] =$product['returns'];
                        ?>
                    <?endforeach;?>
                    <? foreach ($order_new as $key=>$order):?>
                        <td><h5><? if($order['count']!=0){ echo$order['count']; }else{ echo "-";}?></td>
                        <td><h5><? if($order['returns']!=0){ echo$order['returns']; }else{ echo "-";}?></td>
                    <?
                        $total[$key]['count']+=$order['count'];
                        $total[$key]['returns']+=$order['returns'];
                        ?>
                    <?endforeach;?>
                    <td><h5><?= $shop['count_all']?></h5></td>
                    <td><h5><?= $shop ['returns_all']?></h5></td>
                    <td><h5><?= $shop ['percent']?> %</h5></td>

                    <? $total_all['count_all']+=$shop['count_all']?>
                    <? $total_all['returns_all']+=$shop['returns_all']?>
                <?endforeach;?>
                <tr>
                    <td></td>
                    <td>Итого</td>
                    <? foreach ($total as $total_one):?>
                        <td><h5><?=$total_one['count'] ?></h5></td>
                        <td><h5><?=$total_one['returns'] ?></h5></td>
                    <?endforeach;?>
                    <td><h5><?= $total_all['count_all']?></h5></td>
                    <td><h5><?= $total_all['returns_all']?></h5></td>
                    <td><h5><? if($total_all['count_all']!=0 && $total_all['returns_all']!=0){echo (round(($total_all['returns_all']/$total_all['count_all']),3)*100);} ?>%</h5></td>
                </tr>
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
                <td>Магазин&ensp;&ensp;&ensp;</td>
                <td>Мол&ensp;0.9&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Мол&ensp;0,5&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Твор&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Смет&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Вар&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Ряж&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Кеф&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                <td>Твор кг.&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;</td>
                <td>Сме кг.&ensp;&ensp;&ensp;&ensp;</td>
                <td>&ensp;&ensp;&ensp;</td>
                <td>Итого реализованно</td>
                <td>Итого возвратов</td>
                <td>Отн. откл %</td>


            </tr>
            </thead>
            <tbody>
            <?php if($waybill['shops'] != null): ?>
                <?foreach ($waybill['shops'] as $shop):?>
                    <tr>
                    <? $order_new=$order_push?>
                    <td><?=$shop['NPP']?></td>
                    <td><?= $shop['name']?></td>
                    <? foreach ($shop['products'] as $key=>$product):?>
                        <?
                        $order_new[$key]['count_money'] =$product['count_money'];
                        $order_new[$key]['returns_money'] =$product['returns_money'];
                        ?>
                    <?endforeach;?>
                    <? foreach ($order_new as $key=>$order):?>
                        <td><h5><? if($order['count_money']!=0){ echo$order['count_money']; }else{ echo "-";}?></h5></td>
                        <td><h5><? if($order['returns_money']!=0){ echo$order['returns_money']; }else{ echo "-";}?></h5></td>
                        <?
                        $total[$key]['count_money']+=$order['count_money'];
                        $total[$key]['returns_money']+=$order['returns_money'];
                        ?>
                    <?endforeach;?>
                    <td><h5><?= $shop['count_all_money']?></h5></td>
                    <td><h5><?= $shop ['returns_all_money']?></h5></td>
                    <td><h5><?= $shop ['percent_money']?> %</h5></td>
                    <? $total_all['count_all_money']+=$shop['count_all_money']?>
                    <? $total_all['returns_all_money']+=$shop['returns_all_money']?>
                <?endforeach;?>
                <tr>
                    <td></td>
                    <td>Итого</td>
                    <? foreach ($total as $total_one):?>
                        <td><h5><?=$total_one['count_money'] ?></h5></td>
                        <td><h5><?=$total_one['returns_money'] ?></h5></td>
                    <?endforeach;?>
                    <td><h5><?= $total_all['count_all_money']?></h5></td>
                    <td><h5><?= $total_all['returns_all_money']?></h5></td>
                    <td><h5><? if($total_all['count_all_money']!=0 && $total_all['returns_all_money']!=0){echo (round(($total_all['returns_all_money']/$total_all['count_all_money']),3)*100);} ?>%</h5></td>
                </tr>
            <?php else:?>
                <tr>
                    <td>Записи не найдены</td>
                </tr>
            <?php endif; ?>
            </tbody>
            </table>
<?endforeach;?>
    </div>
</div>
