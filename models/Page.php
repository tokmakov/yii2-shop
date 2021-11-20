<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Page extends ActiveRecord {

    /**
     * Метод возвращает имя таблицы БД
     */
    public static function tableName() {
        return 'page';
    }

    /**
     * Метод возвращает все страницы в виде дерева
     */
    public static function getTree() {
        // пробуем извлечь данные из кеша
        $data = Yii::$app->cache->get('page-menu');
        if ($data === false) {
            // данных нет в кеше, получаем их заново
            $pages = Page::find()
                ->select(['id', 'name', 'slug', 'parent_id'])
                ->indexBy('id')
                ->asArray()
                ->all();
            $data = self::makeTree($pages);
            // сохраняем полученные данные в кеше
            Yii::$app->cache->set('page-menu', $data, 60);
        }
        return $data;
    }

    /**
     * Принимает на вход линейный массив элеменов, связанных отношениями
     * parent-child, и возвращает массив в виде дерева
     */
    protected static function makeTree($data = []) {
        if (count($data) == 0) {
            return [];
        }
        $tree = [];
        foreach ($data as $id => &$node) {
            if ($node['parent_id'] == 0) {
                $tree[$id] = &$node;
            } else {
                $data[$node['parent_id']]['childs'][$id] = &$node;
            }
        }
        return $tree;
    }

}