<?php
namespace app\modules\admin\models;

use Yii;
use yii\db\ActiveRecord;
use yii\imagine\Image;

/**
 * Это модель для таблицы БД `product`
 *
 * @property int $id Уникальный идентификатор
 * @property int $category_id Родительская категория
 * @property int $brand_id Идентификатор бренда
 * @property string $name Наименование товара
 * @property string $content Описание товара
 * @property string $price Цена товара
 * @property string $keywords Мета-тег keywords
 * @property string $description Мета-тег description
 * @property string $image Имя файла изображения
 * @property int $hit Лидер продаж?
 * @property int $new Новый товар?
 * @property int $sale Распродажа?
 */
class Product extends ActiveRecord {

    /**
     * Вспомогательный атрибут для загрузки изображения товара
     */
    public $upload;

    /**
     * Вспомогательный атрибут для удаления изображения товара
     */
    public $remove;

    /**
     * Возвращает имя таблицы базы данных
     */
    public static function tableName() {
        return 'product';
    }

    /**
     * Возвращает данные о родительской категории
     */
    public function getCategory() {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Возвращает наименование родительской категории
     */
    public function getCategoryName() {
        $parent = $this->category;
        return $parent ? $parent->name : '';
    }

    /**
     * Возвращает данные о бренде товара
     */
    public function getBrand() {
        return $this->hasOne(Brand::class, ['id' => 'brand_id']);
    }

    /**
     * Возвращает наименование бренда товара
     */
    public function getBrandName() {
        $brand = $this->brand;
        return $brand ? $brand->name : '';
    }

    /**
     * Правила валидации полей формы при создании и редактировании товара
     */
    public function rules() {
        return [
            [['category_id', 'brand_id', 'name', 'price'], 'required'],
            [['category_id', 'brand_id', 'hit', 'new', 'sale'], 'integer'],
            ['content', 'string'],
            ['price', 'number', 'min' => 1],
            [['name', 'keywords', 'description'], 'string', 'max' => 255],
            // удаляем пробелы и преобразуем пустые строки в null
            [['keywords', 'description', 'content', 'image'], 'filter', 'filter' => 'trim'],
            [['keywords', 'description', 'content', 'image'], 'default', 'value' => null],
            // атрибут image проверяем с помощью валидатора image
            ['image', 'image', 'extensions' => 'png, jpg, gif'],
            // вспомогательный атрибут remove помечаем как безопасный
            ['remove', 'safe']
        ];
    }

    /**
     * Возвращает имена полей формы для создания и редактирования товара
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'brand_id' => 'Бренд',
            'name' => 'Наименование',
            'content' => 'Описание',
            'price' => 'Цена',
            'keywords' => 'Мета-тег keywords',
            'description' => 'Мета-тег description',
            'image' => 'Изображение',
            'hit' => 'Лидер продаж',
            'new' => 'Новинка',
            'sale' => 'Распродажа',
            'remove' => 'Удалить изображение'
        ];
    }

    /**
     * Загружает файл изображения товара
     */
    public function uploadImage() {
        if ($this->upload) { // только если был выбран файл для загрузки
            $name = md5(uniqid(rand(), true)) . '.' . $this->upload->extension;
            // сохраняем исходное изображение в директории source
            $source = Yii::getAlias('@webroot/images/products/source/' . $name);
            if ($this->upload->saveAs($source)) {
                // выполняем resize, чтобы получить еще три размера
                $large = Yii::getAlias('@webroot/images/products/large/' . $name);
                Image::thumbnail($source, 1000, 1000)->save($large, ['quality' => 100]);
                $medium = Yii::getAlias('@webroot/images/products/medium/' . $name);
                Image::thumbnail($source, 500, 500)->save($medium, ['quality' => 95]);
                $small = Yii::getAlias('@webroot/images/products/small/' . $name);
                Image::thumbnail($source, 250, 250)->save($small, ['quality' => 90]);
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
            $source = Yii::getAlias('@webroot/images/products/source/' . $name);
            if (is_file($source)) {
                unlink($source);
            }
            $large = Yii::getAlias('@webroot/images/products/large/' . $name);
            if (is_file($large)) {
                unlink($large);
            }
            $medium = Yii::getAlias('@webroot/images/products/medium/' . $name);
            if (is_file($medium)) {
                unlink($medium);
            }
            $small = Yii::getAlias('@webroot/images/products/small/' . $name);
            if (is_file($small)) {
                unlink($small);
            }
        }
    }

    /**
     * Удаляет изображение при удалении товара
     */
    public function afterDelete() {
        parent::afterDelete();
        self::removeImage($this->image);
    }
}
