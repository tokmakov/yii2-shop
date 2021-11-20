<?php
namespace app\modules\admin\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Это модель для таблицы БД `page`
 *
 * @property int $id Уникальный идентификатор
 * @property int $parent_id Родительская страница
 * @property string $name Заголовок страницы
 * @property string $slug Для создания ссылки
 * @property string $content Содержимое страницы
 * @property string $keywords Мета-тег keywords
 * @property string $description Мета-тег description
 */
class Page extends ActiveRecord {

    /**
     * Возвращает имя таблицы базы данных
     */
    public static function tableName() {
        return 'page';
    }

    /**
     * Возвращает данные о родительской странице
     */
    public function getParent() {
        return $this->hasOne(Page::class, ['id' => 'parent_id']);
    }

    /**
     * Возвращает наименование родительской страницы
     */
    public function getParentName() {
        $parent = $this->parent;
        return $parent ? $parent->name : '';
    }

    /**
     * Возвращает массив страниц верхнего уровня для
     * возможности выбора родителя
     */
    public static function getRootPages($exclude = 0) {
        $parents = [0 => 'Без родителя'];
        $root = Page::find()->where(['parent_id' => 0])->all();
        foreach ($root as $item) {
            if ($exclude == $item['id']) {
                continue;
            }
            $parents[$item['id']] = $item['name'];
        }
        return $parents;
    }

    /**
     * Правила валидации полей формы при создании и редактировании страницы
     */
    public function rules() {
        return [
            [['parent_id', 'name', 'slug'], 'required'],
            ['parent_id', 'integer'],
            // не должно быть двух страниц с одинаковым slug
            ['slug', 'unique'],
            // slug может содержать только латиницу, цифры, дефис и подчеркивание
            ['slug', 'match', 'pattern' => '/^[a-z][-_a-z0-9]*$/i'],
            ['content', 'string'],
            [['name', 'slug'], 'string', 'max' => 100],
            [['keywords', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * Возвращает имена полей формы для создания и редактирования страницы
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'parent_id' => 'Родитель',
            'name' => 'Заголовок',
            'slug' => 'Для создания ссылки',
            'content' => 'Содержимое',
            'keywords' => 'Мета-тег keywords',
            'description' => 'Мета-тег description',
        ];
    }

    /**
     * Возвращает массив всех страниц в виде дерева
     */
    public static function getTree($parent = 0) {
        $children = self::find()
            ->where(['parent_id' => $parent])
            ->asArray()
            ->all();
        $result = [];
        foreach ($children as $page) {
            if ($parent) {
                $page['name'] = '— ' . $page['name'];
            }
            $result[] = $page;
            $result = array_merge(
                $result,
                self::getTree($page['id'])
            );
        }
        return $result;
    }

    /**
     * Проверка перед удалением страницы
     */
    public function beforeDelete() {
        $children = self::find()->where(['parent_id' => $this->id])->all();
        if (!empty($children)) {
            Yii::$app->session->setFlash(
                'warning',
                'Нельзя удалить страницу, которая имеет дочерние стрницы'
            );
            return false;
        }
        return parent::beforeDelete();
    }
}
