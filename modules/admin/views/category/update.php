<?php
/*
 * Страница редактирования категории, файл modules/admin/views/category/update.php
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Category */

$this->title = 'Редактирование категории: ' . $model->name;
?>

<h1><?= Html::encode($this->title) ?></h1>
<?=
$this->render(
     '_form', [
    'model' => $model,
]);
?>

