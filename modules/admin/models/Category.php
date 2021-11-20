<?php
namespace app\modules\admin\models;

use Yii;
use yii\db\ActiveRecord;
use yii\imagine\Image;

/**
 * Это модель для таблицы БД `category`
 *
 * @property int $id Уникальный идентификатор
 * @property int $parent_id Родительская категория
 * @property string $name Наименование категории
 * @property string $content Описание категории
 * @property string $keywords Мета-тег keywords
 * @property string $description Мета-тег description
 * @property string $image Имя файла изображения
 */
class Category extends ActiveRecord {

    /**
     * Вспомогательный атрибут для загрузки изображения
     */
    public $upload;

    /**
     * Возвращает имя таблицы базы данных
     */
    public static function tableName() {
        return 'category';
    }

    /**
     * Возвращает данные о родительской категории
     */
    public function getParent() {
        return $this->hasOne(Category::class, ['id' => 'parent_id']);
    }

    /**
     * Возвращает наименование родительской категории
     */
    public function getParentName() {
        $parent = $this->parent;
        return $parent ? $parent->name : '';
    }

    /**
     * Правила валидации полей формы при создании и редактировании категории
     */
    public function rules() {
        return [
            [['parent_id'], 'integer'],
            [['name'], 'trim'], [['name'], 'required'],
            [['name', 'content', 'keywords', 'description'], 'string', 'max' => 255],
            // удаляем пробелы и преобразуем пустые строки в null
            [['keywords', 'description', 'content', 'image'], 'filter', 'filter' => 'trim'],
            [['keywords', 'description', 'content', 'image'], 'default', 'value' => null],
            // атрибут image проверяем с помощью валидатора image
            ['image', 'image', 'extensions' => 'png, jpg, gif'],
        ];
    }

    /**
     * Возвращает имена полей формы для создания и редактирования категории
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'parent_id' => 'Родитель',
            'name' => 'Наименование',
            'content' => 'Описание',
            'keywords' => 'Мета-тег keywords',
            'description' => 'Мета-тег description',
            'image' => 'Изображение'
        ];
    }

    /**
     * Возвращает массив всех категорий каталога в виде дерева
     */
    public static function getAllCategories($parent = 0, $level = 0, $exclude = 0) {
        $children = self::find()
            ->where(['parent_id' => $parent])
            ->asArray()
            ->all();
        $result = [];
        foreach ($children as $category) {
            // при выборе родителя категории нельзя допустить
            // чтобы она размещалась внутри самой себя
            if ($category['id'] == $exclude) {
                continue;
            }
            if ($level) {
                $category['name'] = str_repeat('— ', $level) . $category['name'];
            }
            $result[] = $category;
            $result = array_merge(
                $result,
                self::getAllCategories($category['id'], $level+1, $exclude)
            );
        }
        return $result;
    }

    /**
     * Возвращает массив всех категорий каталога для возможности
     * выбора родителя при добавлении или редактировании товара
     * или категории
     */
    public static function getTree($exclude = 0, $root = false) {
        $data = self::getAllCategories(0, 0, $exclude);
        $tree = [];
        // при выборе родителя категории можно выбрать значение
        // «Без родителя» — это будет категория верхнего уровня
        if ($root) {
            $tree[0] = 'Без родителя';
        }
        foreach ($data as $item) {
            $tree[$item['id']] = $item['name'];
        }
        return $tree;
    }

    /**
     * Возвращает массив идентификаторов всех потомков категории $id,
     * т.е. дочерние, дочерние дочерних и так далее
     */
    public static function getAllChildIds($id) {
        $children = [];
        $ids = self::getChildIds($id);
        foreach ($ids as $item) {
            $children[] = $item;
            $c = self::getAllChildIds($item);
            foreach ($c as $v) {
                $children[] = $v;
            }
        }
        return $children;
    }

    /**
     * Возвращает массив идентификаторов дочерних категорий (прямых
     * потомков) категории с уникальным идентификатором $id
     */
    protected static function getChildIds($id) {
        $children = self::find()->where(['parent_id' => $id])->asArray()->all();
        $ids = [];
        foreach ($children as $child) {
            $ids[] = $child['id'];
        }
        return $ids;
    }

    /**
     * Загружает файл изображения категории
     */
    public function uploadImage() {
        if ($this->upload) { // только если был выбран файл для загрузки
            $name = md5(uniqid(rand(), true)) . '.' . $this->upload->extension;
            // сохраняем исходное изображение в директории source
            $source = Yii::getAlias('@webroot/images/categories/source/' . $name);
            if ($this->upload->saveAs($source)) {
                // выполняем resize, чтобы получить маленькое изображение
                $thumb = Yii::getAlias('@webroot/images/categories/thumb/' . $name);
                Image::thumbnail($source, 250, 250)->save($thumb, ['quality' => 90]);
                return $name;
            }
        }
        return false;
    }

    /**
     * Удаляет старое изображение при загрузке нового
     */
    public static function removeImage($name) {
        if (!empty($name)) {
            $source = Yii::getAlias('@webroot/images/categories/source/' . $name);
            if (is_file($source)) {
                unlink($source);
            }
            $thumb = Yii::getAlias('@webroot/images/categories/thumb/' . $name);
            if (is_file($thumb)) {
                unlink($thumb);
            }
        }
    }

    /**
     * Удаляет изображение при удалении категории
     */
    public function afterDelete() {
        parent::afterDelete();
        self::removeImage($this->image);
    }

    /**
     * Проверка перед удалением категории
     */
    public function beforeDelete() {
        $children = self::find()->where(['parent_id' => $this->id])->all();
        $products = Product::find()->where(['category_id' => $this->id])->all();
        if (!empty($children) || !empty($products)) {
            Yii::$app->session->setFlash(
                'warning',
                'Нельзя удалить категорию, которая имеет товары или дочерние категории'
            );
            return false;
        }
        return parent::beforeDelete();
    }
}
