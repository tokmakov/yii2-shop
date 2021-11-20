<?php
/*
 * Страница редактирования существующего бренда, файл modules/admin/views/brand/update.php
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Brand */

$this->title = 'Редактирование бренда: ' . $model->name;
?>

<h1><?= Html::encode($this->title); ?></h1>
<?=
$this->render(
    '_form', [
    'model' => $model,
]);
?>
