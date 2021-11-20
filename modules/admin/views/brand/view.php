<?php
/*
 * Страница просмотра данных бренда, файл modules/admin/views/brand/view.php
 */
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Brand */

$this->title = 'Просмотр бренда ' . $model->name;
?>

<h1><?= Html::encode($this->title) ?></h1>
<p>
    <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Вы уверены, что хотите удалить бренд?',
            'method' => 'post',
        ],
    ]) ?>
</p>

<?=
DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        'content',
        'keywords',
        'description',
        'image',
    ],
]);
?>

