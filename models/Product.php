<?php
namespace app\models;

use Yii;
use yii\data\Pagination;
use yii\db\ActiveRecord;
use yii\db\Query;
use Stem\LinguaStemRu;


class Product extends ActiveRecord {

    /**
     * Возвращает имя таблицы БД
     */
    public static function tableName() {
        return 'product';
    }

    /**
     * Позволяет получить родительскую категорию для товара
     */
    public function getCategory() {
        // связь таблицы БД `product` с таблицей `category`
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Возвращает информацию о товаре с иденификатором $id
     */
    public function getProduct($id) {
        return self::find()->where(['id' => $id])->asArray()->one();
    }

    /**
     * Результаты поиска по каталогу товаров
     */
    public function getSearchResult($search, $page) {
        $search = $this->cleanSearchString($search);
        if (empty($search)) {
            return [null, null];
        }

        // пробуем извлечь данные из кеша
        $key = 'search-'.md5($search).'-page-'.$page;
        $data = Yii::$app->cache->get($key);

        if ($data === false) { // данных нет в кеше, получаем их заново
            // разбиваем поисковый запрос на отдельные слова
            $temp = explode(' ', $search);
            $words = [];
            $stemmer = new LinguaStemRu();
            foreach ($temp as $item) {
                if (iconv_strlen($item) > 3) {
                    // получаем корень слова
                    $words[] = $stemmer->stem_word($item);
                } else {
                    $words[] = $item;
                }
            }
            $relevance = "IF (`product`.`name` LIKE '%" . $words[0] . "%', 3, 0)";
            $relevance .= " + IF (`product`.`keywords` LIKE '%" . $words[0] . "%', 2, 0)";
            $relevance .= " + IF (`category`.`name` LIKE '%" . $words[0] . "%', 1, 0)";
            $relevance .= " + IF (`brand`.`name` LIKE '%" . $words[0] . "%', 1, 0)";
            for ($i = 1; $i < count($words); $i++) {
                $relevance .= " + IF (`product`.`name` LIKE '%" . $words[$i] . "%', 3, 0)";
                $relevance .= " + IF (`product`.`keywords` LIKE '%" . $words[$i] . "%', 2, 0)";
                $relevance .= " + IF (`category`.`name` LIKE '%" . $words[$i] . "%', 1, 0)";
                $relevance .= " + IF (`brand`.`name` LIKE '%" . $words[$i] . "%', 1, 0)";
            }
            $query = (new Query())
                ->select([
                    'id' => 'product.id',
                    'name' => 'product.name',
                    'price' => 'product.price',
                    'image' => 'product.image',
                    'hit' => 'product.hit',
                    'new' => 'product.new',
                    'sale' => 'product.sale',
                    'relevance' => $relevance
                ])
                ->from('product')
                ->join('INNER JOIN', 'category', 'category.id = product.category_id')
                ->join('INNER JOIN', 'brand', 'brand.id = product.brand_id')
                ->where(['like', 'product.name', $words[0]])
                ->orWhere(['like', 'product.keywords', $words[0]])
                ->orWhere(['like', 'category.name', $words[0]])
                ->orWhere(['like', 'brand.name', $words[0]]);
            for ($i = 1; $i < count($words); $i++) {
                $query = $query->orWhere(['like', 'product.name', $words[$i]]);
                $query = $query->orWhere(['like', 'product.keywords', $words[$i]]);
                $query = $query->orWhere(['like', 'category.name', $words[$i]]);
                $query = $query->orWhere(['like', 'brand.name', $words[$i]]);
            }
            $query = $query->orderBy(['relevance' => SORT_DESC]);

            // постраничная навигация
            $pages = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => Yii::$app->params['pageSize'],
                'forcePageParam' => false,
                'pageSizeParam' => false
            ]);
            $products = $query
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
            // сохраняем полученные данные в кеше
            $data = [$products, $pages];
            Yii::$app->cache->set($key, $data);
        }

        return $data;
    }

    /**
     * Вспомогательная функция, очищает строку поискового запроса с сайта
     * от всякого мусора
     */
    protected function cleanSearchString($search) {
        $search = iconv_substr($search, 0, 64);
        // удаляем все, кроме букв и цифр
        $search = preg_replace('#[^0-9a-zA-ZА-Яа-яёЁ]#u', ' ', $search);
        // сжимаем двойные пробелы
        $search = preg_replace('#\s+#u', ' ', $search);
        $search = trim($search);
        return $search;
    }

}