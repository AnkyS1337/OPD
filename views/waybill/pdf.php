
<html>
<head>
    <style type="text/css">
        .pdf{
            width: 100%;
            border-collapse: collapse;
        }
        .pdf tr td{
            padding:5px;
            border:1px solid #333;
        }
        .pdf tr th{
            padding:5px;
            border:1px solid #333;
        }
    </style>
</head>
<body>
<div class='site-index'>
    <div>
        <h3 style="text-align:left"> Дата выполнения мартшрута: <?=$date ?>;  Водитель: <?=$driver ?> <?=$phone ?> </h3>
    </div>
    <div>
        <div >
            <table class="pdf">
                <thead>
                <tr>
                    <td>№</td>
                    <td>Магазин&ensp;&ensp;&ensp;&ensp;&ensp;</td>
                    <td>Мол&ensp;0.9</td>
                    <td>&ensp;</td>
                    <td>Мол&ensp;0,5</td>
                    <td>&ensp;</td>
                    <td>Тво</td>
                    <td>&ensp;</td>
                    <td>Смет</td>
                    <td>&ensp;</td>
                    <td>Вар</td>
                    <td>&ensp;</td>
                    <td>Ряж</td>
                    <td>&ensp;</td>
                    <td>Кеф</td>
                    <td>&ensp;</td>
                    <td>Тво кг.</td>
                    <td>&ensp;</td>
                    <td>Сме кг.</td>
                    <td>&ensp;</td>

                </tr>
                </thead>
                <tbody>

                <?php if($waybills_buy_shop_push != null): ?>
                    <?foreach ($waybills_buy_shop_push as $key=>$waybill_buy_shop_push):?>
                        <tr>
                            <td><?=$key?></td>
                            <td><?= \app\models\States_for_waybill::getShop_name_for_pdf($waybill_buy_shop_push['shop'],1)?></td>
                        <td><?php if ($waybill_buy_shop_push['молоко 0,9'] !=null){echo $waybill_buy_shop_push['молоко 0,9']; }?></td>
                        <td><?php if ($waybill_buy_shop_push['молоко 0,9_в'] !=null){echo $waybill_buy_shop_push['молоко 0,9_в']; }?></td>

                        <td><?php if ($waybill_buy_shop_push['молоко 0,5'] !=null){echo $waybill_buy_shop_push['молоко 0,5']; }?></td>
                        <td><?php if ($waybill_buy_shop_push['молоко 0,5_в'] !=null){echo $waybill_buy_shop_push['молоко 0,5_в']; }?></td>

                        <td><?php if ($waybill_buy_shop_push['Творог'] !=null){echo $waybill_buy_shop_push['Творог']; }?></td>
                        <td><?php if ($waybill_buy_shop_push['Творог_в'] !=null){echo $waybill_buy_shop_push['Творог_в']; }?></td>

                        <td><?php if ($waybill_buy_shop_push['Сметана'] !=null){echo $waybill_buy_shop_push['Сметана']; }?></td>
                        <td><?php if ($waybill_buy_shop_push['Сметана_в'] !=null){echo $waybill_buy_shop_push['Сметана_в']; }?></td>

                        <td><?php if ($waybill_buy_shop_push['варенец'] !=null){echo $waybill_buy_shop_push['варенец']; }?></td>
                        <td><?php if ($waybill_buy_shop_push['варенец_в'] !=null){echo $waybill_buy_shop_push['варенец_в']; }?></td>

                        <td><?php if ($waybill_buy_shop_push['ряженка'] !=null){echo $waybill_buy_shop_push['ряженка']; }?></td>
                        <td><?php if ($waybill_buy_shop_push['ряженка_в'] !=null){echo $waybill_buy_shop_push['ряженка_в']; }?></td>

                        <td><?php if ($waybill_buy_shop_push['кефир'] !=null){echo $waybill_buy_shop_push['кефир']; }?></td>
                        <td><?php if ($waybill_buy_shop_push['кефир_в'] !=null){echo $waybill_buy_shop_push['кефир_в']; }?></td>

                        <td><?php if ($waybill_buy_shop_push['Творог кг.'] !=null){echo $waybill_buy_shop_push['Творог кг.']; }?></td>
                        <td><?php if ($waybill_buy_shop_push['Творог кг._в'] !=null){echo $waybill_buy_shop_push['Творог кг._в']; }?></td>

                        <td><?php if ($waybill_buy_shop_push['Сметана кг.'] !=null){echo $waybill_buy_shop_push['Сметана кг.']; }?></td>
                        <td><?php if ($waybill_buy_shop_push['Сметана кг._в'] !=null){echo $waybill_buy_shop_push['Сметана кг._в']; }?></td>
                    <?endforeach;?>
                <?php else:?>
                    <tr>
                        <td>Записи не найдены</td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td></td>
                    <td>Итого</td>
                    <td><?php if ($waybill_all_for_push['молоко 0,9'] !=null){echo $waybill_all_for_push['молоко 0,9']; }?></td>
                    <td><?php if ($waybill_all_for_push['молоко 0,9_в'] !=null){echo $waybill_all_for_push['молоко 0,9_в']; }?></td>

                    <td><?php if ($waybill_all_for_push['молоко 0,5'] !=null){echo $waybill_all_for_push['молоко 0,5']; }?></td>
                    <td><?php if ($waybill_all_for_push['молоко 0,5_в'] !=null){echo $waybill_all_for_push['молоко 0,5_в']; }?></td>

                    <td><?php if ($waybill_all_for_push['Творог'] !=null){echo $waybill_all_for_push['Творог']; }?></td>
                    <td><?php if ($waybill_all_for_push['Творог_в'] !=null){echo $waybill_all_for_push['Творог_в']; }?></td>

                    <td><?php if ($waybill_all_for_push['Сметана'] !=null){echo $waybill_all_for_push['Сметана']; }?></td>
                    <td><?php if ($waybill_all_for_push['Сметана_в'] !=null){echo $waybill_all_for_push['Сметана_в']; }?></td>

                    <td><?php if ($waybill_all_for_push['варенец'] !=null){echo $waybill_all_for_push['варенец']; }?></td>
                    <td><?php if ($waybill_all_for_push['варенец_в'] !=null){echo $waybill_all_for_push['варенец_в']; }?></td>

                    <td><?php if ($waybill_all_for_push['ряженка'] !=null){echo $waybill_all_for_push['ряженка']; }?></td>
                    <td><?php if ($waybill_all_for_push['ряженка_в'] !=null){echo $waybill_all_for_push['ряженка_в']; }?></td>

                    <td><?php if ($waybill_all_for_push['кефир'] !=null){echo $waybill_all_for_push['кефир']; }?></td>
                    <td><?php if ($waybill_all_for_push['кефир_в'] !=null){echo $waybill_all_for_push['кефир_в']; }?></td>

                    <td><?php if ($waybill_all_for_push['Творог кг.'] !=null){echo $waybill_all_for_push['Творог кг.']; }?></td>
                    <td><?php if ($waybill_all_for_push['Творог кг._в'] !=null){echo $waybill_all_for_push['Творог кг._в']; }?></td>

                    <td><?php if ($waybill_all_for_push['Сметана кг.'] !=null){echo $waybill_all_for_push['Сметана кг.']; }?></td>
                    <td><?php if ($waybill_all_for_push['Сметана кг._в'] !=null){echo $waybill_all_for_push['Сметана кг._в']; }?></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <? $orders=\app\models\Order_of_products::find()->all();?>
                    <? foreach ($orders as $order):?>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <?endforeach;?>
                </tr>
                </tbody>
            </table>

                    <h3>
                        Отпустил&nbsp;&nbsp;&nbsp;__________________
                        &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
                        &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
                        &ensp;&ensp;
                        Получил&nbsp;&nbsp;&nbsp;__________________
                    </h3>
            </div>
        </div>
        </div>
    </div>
</div>
</body>
</html>
