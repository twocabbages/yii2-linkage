<?php
use kartik\helpers\Html;
?>
<div class="levelSelect-default-index" xmlns="http://www.w3.org/1999/html">
    <div class="row">
        <div class="col-lg-12">
            <?php
            \yii\bootstrap\ActiveForm::begin([
//                  'action' => Yii::$app->urlManager->createUrl(["/linkage/default/index"]),
            ]);
            echo \cabbage\linkage\MultiLevelSelect::widget([
                'name' => 'User[region_id]',
                'dataProvider' => 'cabbage\linkage\models\Region',
                'defaultData' => $defaultData,
            ]);
            echo Html::submitButton();
            \yii\bootstrap\ActiveForm::end();
            ?>
        </div>
    </div>
</div>
