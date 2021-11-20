<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

    <?php
    $items = [
        0 => 'Новый',
        1 => 'Обработан',
        2 => 'Оплачен',
        3 => 'Доставлен',
        4 => 'Завершен',
    ];
    echo $form->field($model, 'status')->dropDownList($items);
    echo $form->field($model, 'name')->textInput(['maxlength' => true]);
    echo $form->field($model, 'email')->textInput(['maxlength' => true]);
    echo $form->field($model, 'phone')->textInput(['maxlength' => true]);
    echo $form->field($model, 'address')->textarea(['rows' => 2, 'maxlength' => true]);
    echo $form->field($model, 'comment')->textarea(['rows' => 2, 'maxlength' => true]);
    echo $form->field($model, 'amount')->textInput(['disabled' => true]);
    echo $form->field($model, 'created')->textInput(['disabled' => true]);
    echo $form->field($model, 'updated')->textInput(['disabled' => true]);
    ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>
