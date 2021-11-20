<?php
/*
 * Форма для добавления и редактирования страницы, файл modules/admin/views/page/_form.php
 */
use app\modules\admin\models\Page;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Page */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]); ?>
    <?php
    // при редактировании существующей страницы нельзя допустить,
    // чтобы в качестве родителя была выбрана эта же страница
    $exclude = 0;
    if (!empty($model->id)) {
        $exclude = $model->id;
    }
    echo $form->field($model, 'parent_id')->dropDownList(Page::getRootPages($exclude));
    ?>
    <?=
    /*
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
    */
    $form->field($model, 'content')->widget(
        CKEditor::class,
        [
            'editorOptions' => ElFinder::ckeditorOptions(
                'elfinder',
                [
                    // разработанны стандартные настройки basic, standard, full
                    'preset' => 'basic',
                    'inline' => false, // по умолчанию false
                ]
            ),
        ]
    );
    ?>
    <?= $form->field($model, 'keywords')->textarea(['rows' => 2, 'maxlength' => true]); ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 2, 'maxlength' => true]); ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>

