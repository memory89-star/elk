<?php

use frontend\modules\elk\models\ReestrPrint;



?>


<div class="container-report--body" style="margin-bottom: 3.67em;">
    <div class="container-report--title" style="text-align: center; margin-top: 1.67em;">
        <span class="title-wrapper--bold-size" style="font-weight: bold; font-size: .83em;">
            Реестр "Летучего контроля" за период с <?= Yii::$app->formatter->asDate($model->date_begin, 'php:d.m.Y') ?> по <?= Yii::$app->formatter->asDate($model->date_end, 'php:d.m.Y') ?>
        </span>
    </div>
    <?php $model = new ReestrPrint;?>
    <table class="table table-bordered text-left">
        <tr>
            <td colspan="11"  style="border: 1px solid black" align="center" > Коррекции </td>
            <td colspan="4"  style="border: 1px solid black" align="center" > Корректирующие действия </td>
        </tr>
        <tr>
            <td style="border: 1px solid black;" align="center" valign="top">Рег. №</td>
            <td style="border: 1px solid black;" align="center" valign="top">Контролируемое подразделение</td>
            <td style="border: 1px solid black;" align="center" valign="top">Контролирующее подразделение</td>
            <td style="border: 1px solid black;" align="center" valign="top">Дата выявления</td>
            <td style="border: 1px solid black;" align="center" valign="top">Несоответствие/наблюдение</td>
            <td style="border: 1px solid black;" align="center" valign="top">Код объекта ЛК</td>
            <td style="border: 1px solid black;" align="center" valign="top">Не выполнены требования</td>
            <td style="border: 1px solid black;" align="center" valign="top">Значимость</td>
            <td style="border: 1px solid black;" align="center" valign="top">Мероприятия по устранению несоответсвия/наблюдения</td>
            <td style="border: 1px solid black;" align="center" valign="top">Сроки, Отвественный/Контролирующий</td>
            <td style="border: 1px solid black;" align="center" valign="top">Статус выполнения</td>
            <td style="border: 1px solid black;" align="center" valign="top">Причина несоответствия</td>
            <td style="border: 1px solid black;" align="center" valign="top">Мероприятия по устранению причин</td>
            <td style="border: 1px solid black;" align="center" valign="top">Сроки, Отвественный/Контролирующий</td>
            <td style="border: 1px solid black;" align="center" valign="top">Статус выполнения</td>
        </tr>
        <tr>
            <td style="border: 1px solid black;" align="center">1</td>
            <td style="border: 1px solid black;" align="center">2</td>
            <td style="border: 1px solid black;" align="center">3</td>
            <td style="border: 1px solid black;" align="center">4</td>
            <td style="border: 1px solid black;" align="center">5</td>
            <td style="border: 1px solid black;" align="center">6</td>
            <td style="border: 1px solid black;" align="center">7</td>
            <td style="border: 1px solid black;" align="center">8</td>
            <td style="border: 1px solid black;" align="center">9</td>
            <td style="border: 1px solid black;" align="center">10</td>
            <td style="border: 1px solid black;" align="center">11</td>
            <td style="border: 1px solid black;" align="center">12</td>
            <td style="border: 1px solid black;" align="center">13</td>
            <td style="border: 1px solid black;" align="center">14</td>
            <td style="border: 1px solid black;" align="center">15</td>
        </tr>
        <?php $kk = 1;
        $num_action = 0;?>
        <?php foreach ($data as $pdata): ?>
            <?php
                $model_pdata = $model->getEventsKolID($pdata->id);
//                $model_pdata = $model->getDiscrKol($pdata->id);
                $action_events = \yii\helpers\ArrayHelper::toArray($model_pdata);

                    $row_span = count($model_pdata);
                    if ($row_span == 0) {
                        $action_events[0]['events'] = 'action test';
//                        $action_events[0]['events'] = 'events test';
                    }

                    $num_action = 0;
            ?>

            <?php foreach ($action_events as $action):
            $num_action ++;?>

                <?php if ($num_action == 1): ?>
                 <tr>

                            <td style="border: 1px solid black;" align="justify"; valign="top"; rowspan="<?= $row_span ?>">
                                <span class="title-wrapper"><?= $kk++ ?></span>
                            </td>
                            <td style="border: 1px solid black;" align="justify"; valign="top"; rowspan="<?= $row_span ?>">
                                <span class="title-wrapper"><?= $model->getDepartmentKontr($pdata->id_department_kontrolled) ?></span>
                            </td>
                            <td style="border: 1px solid black;" align="justify" valign="top" rowspan="<?= $row_span ?>">
                                <span class="title-wrapper"><?= $model->getDepartmentKontr($pdata->id_department_kontrolling) ?></span>
                            </td>
                            <td style="border: 1px solid black;" align="justify" valign="top" rowspan="<?= $row_span ?>">
                                <span class="title-wrapper"><?= Yii::$app->formatter->asDate($pdata->date_detection) ?></span>
                            </td>
                            <td style="border: 1px solid black;" align="justify" valign="top" rowspan="<?= $row_span ?>">
                                <span class="title-wrapper"><?= $pdata->incongruity ?></span>
                            </td>
                            <td style="border: 1px solid black;" align="justify" valign="top" rowspan="<?= $row_span ?>">
                                <span class="title-wrapper"><?= $model->getKod($pdata->id_objects) ?></span>
                            </td>
                            <td style="border: 1px solid black;" align="justify" valign="top" rowspan="<?= $row_span ?>">
                                <span class="title-wrapper"><?= $pdata->requirements_not_met ?></span>
                            </td>
                            <td style="border: 1px solid black;" align="justify" valign="top" rowspan="<?= $row_span ?>">
                            <span class="title-wrapper"><?= $model->getZn($pdata->id_significance) ?></span>
                            </td>
                            <td style="border: 1px solid black;" align="justify" valign="top" rowspan="<?= $row_span ?>">
                                <span class="title-wrapper"><?= $pdata->events_elimination ?></span>
                            </td>
                            <td style="border: 1px solid black;" align="justify" valign="top" rowspan="<?= $row_span ?>">
                                <span class="title-wrapper">     <?= "план: " . $model->getSrokPlan($pdata->id) ?></span>
                                <span class="title-wrapper"><br> <?= "факт: " . $model->getSrokFakt($pdata->id) ?></span>
                                <span class="title-wrapper"><br> <?= "отв.: " . $model->getFioByCardId($pdata->id_otvetst) ?></span>
                                <span class="title-wrapper"><br> <?= "котр.: " . $model->getFioByCardId($pdata->id_kontrol)  ?></span>
                            </td>
                            <td style="border: 1px solid black;" align="justify" valign="top" rowspan="<?= $row_span ?>">
                                <span class="title-wrapper"><?= $model->getStepStatus($pdata->id) ?></span>
                            </td>

                            <?php if ($row_span !== 0): ?>

                                <td style="border: 1px solid black;" align="justify"; valign="top">
                                    <span class="title-wrapper"><?= $action['discrepancy'] ?> </span>
                                </td>

                                <td style="border: 1px solid black;" align="justify"; valign="top">
                                    <span class="title-wrapper"><?= $action['events'] ?> </span>
                                </td>

                                <td style="border: 1px solid black;" align="justify";  valign="top">
                                     <span class="title-wrapper"> <?= "план: " . Yii::$app->formatter->asDate($action['date_plan']) . ' ' ?></span>
                                     <span class="title-wrapper"><br> <?= "факт: " . Yii::$app->formatter->asDate($action['date_fact']) . ' '  ?></span>
                                     <span class="title-wrapper"><br> <?= 'отв. ' . $model->getFioByCardId($action['id_otvetst']) . ' '  ?></span>
                                     <span class="title-wrapper"><br> <?= 'контр. ' . $model->getFioByCardId($action['id_kontrol']) ?></span>
                                </td>
                                <td style="border: 1px solid black;" align="justify"; valign="top">
                                    <span class="title-wrapper"><?= $model->getStepStatusEvents($action['id_events']) ?> </span>
                                </td>

                            <?php else: ?>
                                <td style="border: 1px solid black;" align="justify" valign="top">
                                    <span class="title-wrapper"></span>
                                </td>
                                <td style="border: 1px solid black;" align="justify" valign="top">
                                    <span class="title-wrapper"></span>
                                </td>
                                <td style="border: 1px solid black;" align="justify" valign="top">
                                    <span class="title-wrapper"></span>
                                </td>
                                <td style="border: 1px solid black;" align="justify" valign="top">
                                    <span class="title-wrapper"></span>
                                </td>
                            <?php endif; ?>

                </tr>

            <?php else: ?>
                <tr>

                    <td style="border: 1px solid black;" align="justify"; valign="top">
                        <span class="title-wrapper"><?= $action['discrepancy'] ?> </span>
                    </td>

                    <td style="border: 1px solid black;" align="justify"; valign="top">
                        <span class="title-wrapper"><?= $action['events'] ?> </span>
                    </td>

                    <td style="border: 1px solid black;" align="justify";  valign="top">
                        <span class="title-wrapper"> <?= "план: " . Yii::$app->formatter->asDate($action['date_plan']) . ' ' ?></span>
                        <span class="title-wrapper"><br> <?= "факт: " . Yii::$app->formatter->asDate($action['date_fact']) . ' '  ?></span>
                        <span class="title-wrapper"><br> <?= 'отв. ' . $model->getFioByCardId($action['id_otvetst']) . ' '  ?></span>
                        <span class="title-wrapper"><br> <?= 'контр. ' . $model->getFioByCardId($action['id_kontrol']) ?></span>
                    </td>

                    <td style="border: 1px solid black;" align="justify"; valign="top">
                        <span class="title-wrapper"><?= $model->getStepStatusEvents($action['id_events']) ?> </span>
                    </td>
                </tr>
            <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>

    </table>
</div>

