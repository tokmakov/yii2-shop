<?php
/*
 * Страница списка всех брендов, файл modules/admin/views/brand/index.php
 */
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Бренды';
?>

<h1><?= Html::encode($this->title) ?></h1>
<p>
    <?= Html::a('Добавить бренд', ['create'], ['class' => 'btn btn-success']) ?>
</p>

<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        [
            'attribute' => 'keywords',
            'value' => function($data) {
                return empty($data->keywords) ? 'Не задано' : $data->keywords;
            }
        ],
        [
            'attribute' => 'description',
            'value' => function($data) {
                return empty($data->description) ? 'Не задано' : $data->description;
            }
        ],
        ['class' => 'yii\grid\ActionColumn'],
    ],
]);
?>

