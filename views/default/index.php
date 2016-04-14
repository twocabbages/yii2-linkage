<?php
use kartik\helpers\Html;

/**
 * @var yii\web\View $this
 */
?>
<div class="levelSelect-default-index" xmlns="http://www.w3.org/1999/html">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <?php
                \yii\bootstrap\ActiveForm::begin([
                ]);
                echo \cabbage\linkage\MultiLevelSelect::widget([
                    'name' => 'User[region_id]',
                    'defaultData' => $defaultData,
                ]);
                ?>
            </div>

            <?= Html::submitButton('submit', ['class'=>"btn btn-default"]); ?>
            <?php
            \yii\bootstrap\ActiveForm::end();
            ?>
        </div>
    </div>
</div>
