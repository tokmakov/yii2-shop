<?php
namespace app\modules\admin\models;

use Yii;
use yii\db\ActiveRecord;
use yii\imagine\Image;

/**
 * Это модель для таблицы БД `brand`
 *
 * @property int $id Уникальный идентификатор
 * @property string $name Наименование бренда
 * @property string $content Описание бренда
 * @property string $keywords Мета-тег keywords
 * @property string $description Мета-тег description
 * @property string $image Имя файла изображения
 */
class Brand extends ActiveRecord {

    /**
     * Вспомогательный атрибут для загрузки изображения
     */
    public $upload;

    /**
     * Возвращает имя таблицы базы данных
     */
    public static function tableName() {
        return 'brand';
    }

    /**
     * Правила валидации полей формы при создании и редактировании бренда
     */
    public function rules() {
        return [
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
     * Возвращает имена полей формы для создания и редактирования бренда
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'content' => 'Описание',
            'keywords' => 'Мета-тег keywords',
            'description' => 'Мета-тег description',
            'image' => 'Изображение',
        ];
    }

    /**
     * Загружает файл изображения бренда
     */
    public function uploadImage() {
        if ($this->upload) { // только если был выбран файл для загрузки
            $name = md5(uniqid(rand(), true)) . '.' . $this->upload->extension;
            // сохраняем исходное изображение в директории source
            $source = Yii::getAlias('@webroot/images/brands/source/' . $name);
            if ($this->upload->saveAs($source)) {
                // выполняем resize, чтобы получить маленькое изображение
                $thumb = Yii::getAlias('@webroot/images/brands/thumb/' . $name);
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
            $source = Yii::getAlias('@webroot/images/brands/source/' . $name);
            if (is_file($source)) {
                unlink($source);
            }
            $thumb = Yii::getAlias('@webroot/images/brands/thumb/' . $name);
            if (is_file($thumb)) {
                unlink($thumb);
            }
        }
    }

    /**
     * Удаляет изображение при удалении бренда
     */
    public function afterDelete() {
        parent::afterDelete();
        self::removeImage($this->image);
    }

    /**
     * Проверка перед удалением бренда
     */
    public function beforeDelete() {
        $products = Product::find()->where(['brand_id' => $this->id])->all();
        if (!empty($products)) {
            Yii::$app->session->setFlash(
                'warning',
                'Нельзя удалить бренд, у которого есть товары'
            );
            return false;
        }
        return parent::beforeDelete();
    }
}
