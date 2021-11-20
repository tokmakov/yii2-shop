<?php
namespace app\models;

use yii\data\Pagination;
use yii\db\ActiveRecord;
use Yii;

class Brand extends ActiveRecord {

    /**
     * Метод возвращает имя таблицы БД
     */
    public static function tableName() {
        return 'brand';
    }

    /**
     * Метод возвращает массив товаров бренда
     */
    public function getProducts() {
        // связь таблицы БД `brand` с таблицей `product`
        return $this->hasMany(Product::class, ['brand_id' => 'id']);
    }

    /**
     * Возвращает информацию о бренде с идентификатором $id
     */
    public function getBrand($id) {
        return self::find()->where(['id' => $id])->asArray()->one();
    }

    /**
     * Возвращает массив популярных брендов и
     * количество товаров для каждого бренда
     */
    public function getPopularBrands() {
        // получаем бренды с наибольшим кол-вом товаров
        $brands = self::find()
            ->select([
                'id' => 'brand.id',
                'name' => 'brand.name',
                'content' => 'brand.content',
                'image' => 'brand.image',
                'count' => 'COUNT(*)'
            ])
            ->innerJoin(
                'product',
                'product.brand_id = brand.id'
            )
            ->groupBy([
                'brand.id', 'brand.name', 'brand.content', 'brand.image'
            ])
            ->orderBy(['count' => SORT_DESC])
            ->limit(3)
            ->asArray()
            // для дальнейшей сортировки
            ->indexBy('name')
            ->all();
        // теперь нужно отсортировать бренды по названию
        ksort($brands);
        return $brands;
    }

    /**
     * Возвращает массив всех брендов каталога и
     * количество товаров для каждого бренда
     */
    public function getAllBrands() {
        return self::find()
            ->select([
                'id' => 'brand.id',
                'name' => 'brand.name',
                'content' => 'brand.content',
                'image' => 'brand.image',
                'count' => 'COUNT(*)'
            ])
            ->innerJoin(
                'product',
                'product.brand_id = brand.id'
            )
            ->groupBy([
                'brand.id', 'brand.name', 'brand.content', 'brand.image'
            ])
            ->orderBy(['name' => SORT_ASC])
            ->asArray()
            ->all();
    }

    /**
     * Возвращает массив всех товаров бренда с идентификатором $id
     */
    public function getBrandProducts($id) {
        // для постаничной навигации получаем только часть товаров
        $query = Product::find()->where(['brand_id' => $id]);
        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => Yii::$app->params['pageSize'],
            'forcePageParam' => false,
            'pageSizeParam' => false
        ]);
        $products = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
        return [$products, $pages];
    }
}