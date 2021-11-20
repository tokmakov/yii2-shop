<?php
/*
 * Файл view-шаблона modules/admin/views/order/update.php
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Order */

$this->title = 'Редактирование заказа № ' . $model->id;
?>
<h1><?= Html::encode($this->title) ?></h1>

<?=
$this->render('_form', [
    'model' => $model,
]);
?>
