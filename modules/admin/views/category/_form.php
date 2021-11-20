<?php
/*
 * Форма для добавления и редактирования категории, файл modules/admin/views/category/_form.php
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>
    <?php
    // при редактировании существующей категории нельзя допустить, чтобы
    // в качестве родителя была выбрана эта же категория или ее потомок
    $exclude = 0;
    if (!empty($model->id)) {
        $exclude = $model->id;
    }
    $parents = $model::getTree($exclude, true);
    echo $form->field($model, 'parent_id')->dropDownList($parents);
    ?>
    <fieldset>
        <legend>Загрузить изображение</legend>
        <?= $form->field($model, 'image')->fileInput(); ?>
        <?php
        if (!empty($model->image)) {
            $img = Yii::getAlias('@webroot') . '/images/categories/source/' . $model->image;
            if (is_file($img)) {
                $url = Yii::getAlias('@web') . '/images/categories/source/' . $model->image;
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

