<?php
/*
 * Страница просмотра страницы, файл modules/admin/views/page/view.php
 */
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Page */

$this->title = 'Просмотр страницы: ' . $model->name;
?>

<h1><?= Html::encode($this->title); ?></h1>

<p>
    <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Вы уверены, что хотите удалить эту страницу?',
            'method' => 'post',
        ],
    ]) ?>
</p>

<?=
DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        'slug',
        [
            'attribute' => 'parent_id',
            'value' =>  $model->getParentName()
        ],
        [
            'attribute' => 'content',
            'value' =>  empty($model->content) ? 'Не задано' : $model->content,
            'format' => 'html'
        ],
        [
            'attribute' => 'keywords',
            'value' =>  empty($model->keywords) ? 'Не задано' : $model->keywords
        ],
        [
            'attribute' => 'description',
            'value' =>  empty($model->description) ? 'Не задано' : $model->description
        ],
    ],
]);
?>

