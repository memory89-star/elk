<?php

use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var \Exception $exception */

$this->title = $name;
?>
<section class="content">

    <div class="error-page">
        <h2 class="headline text-danger"><i class="fas fa-exclamation-triangle"></i></h2>

        <div class="error-content">
            <h3><?= $name ?></h3>

            <p>
                <?= nl2br(Html::encode($message)) ?>
            </p>

            <p>
                <?= Yii::t('app', 'The above error occurred while the Web server was processing your request') ?>.
                <?= Yii::t('app', 'Please contact us if you think this is a server error') ?>. <?= Yii::t('app', 'Thank you') ?>.
                <?= Yii::t('app', 'Meanwhile, you may') ?> <a href='<?= Yii::$app->homeUrl ?>'><?= Yii::t('app', 'Return to dashboard') ?></a><?/*= Yii::t('app', ' or try using the search form')*/ ?>.
            </p>

        </div>
    </div>

</section>
