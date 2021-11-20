<?php
/*
 * Страница просмотра категории, файл modules/admin/views/category/view.php
 */
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Category */

$this->title = 'Просмотр категории: ' . $model->name;
?>

<h1><?= Html::encode($this->title) ?></h1>
<p>
    <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Вы уверены, что хотите удалить категорию?',
            'method' => 'post',
        ],
    ]) ?>
</p>

<?=
DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        [
            'attribute' => 'parent_id',
            'value' =>  $model->getParentName()
        ],
        'content:html',
        'keywords',
        'description',
        'image',
    ],
]);
?>

