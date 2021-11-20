<?php
/*
 * Форма для добавления и редактирования бренда, файл modules/admin/views/brand/_form.php
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Brand */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>
    <fieldset>
        <legend>Загрузить изображение</legend>
        <?= $form->field($model, 'image')->fileInput(); ?>
        <?php
        if (!empty($model->image)) {
            $img = Yii::getAlias('@webroot') . '/images/brands/source/' . $model->image;
            if (is_file($img)) {
                $url = Yii::getAlias('@web') . '/images/brands/source/' . $model->image;
                echo 'Уже загружено ', Html::a('изображение', $url, ['target' => '_blank']);
            }
        }
        ?>
    </fieldset>
    <?= $form->field($model, 'content')->textarea(['rows' => 2, 'maxlength' => true]); ?>
    <?= $form->field($model, 'keywords')->textarea(['rows' => 2, 'maxlength' => true]); ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 2, 'maxlength' => true]); ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>
