<?php
/*
 * Страница списка всех категорий, файл modules/admin/views/category/index.php
 */
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Категории каталога';
?>

<h1><?= Html::encode($this->title) ?></h1>
<p>
    <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success']) ?>
</p>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Наименование</th>
            <th>Мета-тег keywords</th>
            <th>Мета-тег description</th>
            <th><span class="glyphicon glyphicon-list"></span></th>
            <th><span class="glyphicon glyphicon-eye-open"></span></th>
            <th><span class="glyphicon glyphicon-pencil"></span></th>
            <th><span class="glyphicon glyphicon-trash"></span></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($categories as $category): ?>
        <tr>
            <td><?= $category['name']; ?></td>
            <td><?= $category['keywords']; ?></td>
            <td><?= $category['description']; ?></td>
            <td>
                <?php
                echo Html::a(
                    '<span class="glyphicon glyphicon-list"></span>',
                    ['/admin/category/products', 'id' => $category['id']]
                );
                ?>
            </td>
            <td>
                <?php
                echo Html::a(
                    '<span class="glyphicon glyphicon-eye-open"></span>',
                    ['/admin/category/view', 'id' => $category['id']]
                );
                ?>
            </td>
            <td>
                <?php
                echo Html::a(
                    '<span class="glyphicon glyphicon-pencil"></span>',
                    ['/admin/category/update', 'id' => $category['id']]
                );
                ?>
            </td>
            <td>
                <?php
                echo Html::a(
                    '<span class="glyphicon glyphicon-trash"></span>',
                    ['/admin/category/delete', 'id' => $category['id']],
                    [
                        'data-confirm'=> 'Вы уверены, что хотите удалить эту категорию?',
                        'data-method'=> 'post'
                    ]
                );
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

