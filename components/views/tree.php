<?php
/*
 * Файл components/views/menu.php
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>

<ul id="accordion">
<?php foreach ($tree as $item1): ?>
    <li><a href="<?= Url::to(['catalog/category', 'id' => $item1['id']]); ?>">
        <?= Html::encode($item1['name']); ?>
        <?php if (isset($item1['childs'])): ?>
        </a>
        <span class="badge pull-right"><i class="fa fa-plus"></i></span>
            <ul>
            <?php foreach ($item1['childs'] as $item2): ?>
                <li><a href="<?= Url::to(['catalog/category', 'id' => $item2['id']]); ?>">
                    <?= Html::encode($item2['name']); ?>
                    <?php if (isset($item2['childs'])): ?>
                    </a>
                    <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                        <ul>
                        <?php foreach ($item2['childs'] as $item3): ?>
                            <li><a href="<?= Url::to(['catalog/category', 'id' => $item3['id']]); ?>">
                                <?= Html::encode($item3['name']); ?>
                                <?php if (isset($item3['childs'])): ?>
                                    </a>
                                    <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                                    <ul>
                                    <?php foreach ($item3['childs'] as $item4): ?>
                                        <li><a href="<?= Url::to(['catalog/category', 'id' => $item4['id']]); ?>">
                                            <?= Html::encode($item4['name']); ?>
                                            <?php if (isset($item4['childs'])): ?>
                                                </a>
                                                <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                                                <ul>
                                                <?php foreach ($item4['childs'] as $item5): ?>
                                                    <li><a href="<?= Url::to(['catalog/category', 'id' => $item5['id']]); ?>">
                                                        <?= Html::encode($item5['name']); ?></a></li>
                                                <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                </a>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    </a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            </a>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>
