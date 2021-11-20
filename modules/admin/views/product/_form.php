<?php
/*
 * Форма для добавления и редактирования товара, файл modules/admin/views/product/_form.php
 */

use mihaildev\ckeditor\CKEditor;
use app\modules\admin\models\Brand;
use app\modules\admin\models\Category;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>
    <?php
    $category = Yii::$app->request->get('category') ?: 0;
    $param = ['options' => [$category => ['selected' => true]]];
    echo $form->field($model, 'category_id')->dropDownList(Category::getTree(), $param);
    ?>
    <?=
    $form->field($model, 'brand_id')->dropDownList(
        ArrayHelper::map(Brand::find()->all(), 'id', 'name')
    );
    ?>
    <?= $form->field($model, 'price')->textInput(['maxlength' => true]); ?>
    <fieldset>
        <legend>Загрузить изображение</legend>
        <?= $form->field($model, 'image')->fileInput(); ?>
        <?php
        if (!empty($model->image)) {
            $img = Yii::getAlias('@webroot') . '/images/products/source/' .  $model->image;
            if (is_file($img)) {
                $url = Yii::getAlias('@web') . '/images/products/source/' .  $model->image;
                echo 'Уже загружено ', Html::a('изображение', $url, ['target' => '_blank']);
                echo $form->field($model,'remove')->checkbox();
            }
        }
        ?>
    </fieldset>
    <?=
    $form->field($model, 'content')->widget(
        CKEditor::class,
        [
            'editorOptions' => [
                // разработанны стандартные настройки basic, standard, full
                'preset' => 'basic',
                'inline' => false, // по умолчанию false
            ],
        ]
    );
    ?>
    <?= $form->field($model, 'keywords')->textarea(['rows' => 2, 'maxlength' => true]); ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 2, 'maxlength' => true]); ?>
    <?= $form->field($model, 'hit')->checkbox(); ?>
    <?= $form->field($model, 'new')->checkbox(); ?>
    <?= $form->field($model, 'sale')->checkbox(); ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>

