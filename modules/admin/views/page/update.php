<?php
/*
 * Страница редактирования страницы, файл modules/admin/views/page/update.php
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Page */

$this->title = 'Редактирование страницы: ' . $model->name;
?>

<h1><?= Html::encode($this->title); ?></h1>
<?=
$this->render(
    '_form',
    ['model' => $model]
);
?>
