<?php
namespace app\controllers;

use app\models\Category;
use app\models\Brand;
use app\models\Product;
use yii\web\HttpException;
use Yii;

class CatalogController extends AppController {
    /**
     * Главная страница каталога товаров
     */
    public function actionIndex() {
        // получаем корневые категории
        $roots = Yii::$app->cache->get('root-categories');
        if ($roots === false) {
            $roots = Category::find()->where(['parent_id' => 0])->asArray()->all();
            Yii::$app->cache->set('root-categories', $roots);
        }
        // получаем популярные бренды
        $brands = Yii::$app->cache->get('popular-brands');
        if ($brands === false) {
            $brands = (new Brand())->getPopularBrands();
            Yii::$app->cache->set('popular-brands', $brands);
        }
        // устанавливаем мета-теги для страницы
        $this->setMetaTags(
            'Каталог | ' . Yii::$app->params['shopName'],
            Yii::$app->params['defaultKeywords'],
            Yii::$app->params['defaultDescription']
        );
        return $this->render('index', compact('roots', 'brands'));
    }

    /**
     * Категория каталога товаров
     */
    public function actionCategory($id, $page = 1) {
        $id = (int)$id;
        $page = (int)$page;
        // пробуем извлечь данные из кеша
        $data = Yii::$app->cache->get('category-'.$id.'-page-'.$page);
        if ($data === null) {
            // данные есть в кеше, но такой категории не существует
            throw new HttpException(
                404,
                'Запрошенная страница не найдена'
            );
        }
        if ($data === false) {
            // данных нет в кеше, получаем их заново
            $temp = new Category();
            // данные о категории
            $category = $temp->getCategory($id);
            if (!empty($category)) { // такая категория существует
                // товары категории
                list($products, $pages) = $temp->getCategoryProducts($id);
                // сохраняем полученные данные в кеше
                $data = [$products, $pages, $category];
                Yii::$app->cache->set('category-' . $id . '-page-' . $page, $data);
            } else { // такая категория не существует
                Yii::$app->cache->set('category-' . $id . '-page-' . $page, null);
                throw new HttpException(
                    404,
                    'Запрошенная страница не найдена'
                );
            }
        }
        list($products, $pages, $category) = $data;
        // устанавливаем мета-теги для страницы
        $this->setMetaTags(
            $category['name'] . ' | ' . Yii::$app->params['shopName'],
            $category['keywords'],
            $category['description']
        );
        return $this->render(
            'category',
            compact('category', 'products', 'pages')
        );
    }

    /**
     * Список всех брендов каталога товаров
     */
    public function actionBrands() {
        // пробуем извлечь данные из кеша
        $brands = Yii::$app->cache->get('all-brands');
        if ($brands === false) {
            // данных нет в кеше, получаем их заново
            $brands = (new Brand())->getAllBrands();
            // сохраняем полученные данные в кеше
            Yii::$app->cache->set('all-brands', $brands);
        }
        return $this->render(
            'brands',
            compact('brands')
        );
    }

    /**
     * Список товаров бренда с идентификатором $id
     */
    public function actionBrand($id, $page = 1) {
        $id = (int)$id;
        $page = (int)$page;
        // пробуем извлечь данные из кеша
        $data = Yii::$app->cache->get('brand-'.$id.'-page-'.$page);
        if ($data === null) {
            // данные есть в кеше, но такого бренда не существует
            throw new HttpException(
                404,
                'Запрошенная страница не найдена'
            );
        }
        if ($data === false) {
            // данных нет в кеше, получаем их заново
            $temp = new Brand();
            // данные о бренде
            $brand = $temp->getBrand($id);
            if (!empty($brand)) { // такой бренд существует
                // товары бренда
                list($products, $pages) = $temp->getBrandProducts($id);
                // сохраняем полученные данные в кеше
                $data = [$products, $pages, $brand];
                Yii::$app->cache->set('brand-'.$id.'-page-'.$page, $data);
            } else { // такой бренд не существует
                Yii::$app->cache->set('brand-'.$id.'-page-'.$page, null);
                throw new HttpException(
                    404,
                    'Запрошенная страница не найдена'
                );
            }
        }
        list($products, $pages, $brand) = $data;
        // устанавливаем мета-теги
        $this->setMetaTags(
            $brand['name'] . ' | ' . Yii::$app->params['shopName'],
            $brand['keywords'],
            $brand['description']
        );
        return $this->render(
            'brand',
            compact('brand', 'products', 'pages')
        );
    }

    /**
     * Страница товара с идентификатором $id
     */
    public function actionProduct($id) {
        $id = (int)$id;
        // пробуем извлечь данные из кеша
        $data = Yii::$app->cache->get('product-'.$id);
        if ($data === null) {
            // данные есть в кеше, но такого товара не существует
            throw new HttpException(
                404,
                'Запрошенная страница не найдена'
            );
        }
        if ($data === false) {
            // данных нет в кеше, получаем их заново
            $product = (new Product())->getProduct($id);
            if (!empty($product)) { // такой товар существует
                $brand = (new Brand())->getBrand($product['brand_id']);
                $data = [$product, $brand];
                // сохраняем полученные данные в кеше
                Yii::$app->cache->set('product-' . $id, $data);
            } else { // такого товара не существует
                Yii::$app->cache->set('product-' . $id, null);
                throw new HttpException(
                    404,
                    'Запрошенная страница не найдена'
                );
            }
        }
        list($product, $brand) = $data;
        // устанавливаем мета-теги
        $this->setMetaTags(
            $product['name'] . ' | ' . Yii::$app->params['shopName'],
            $product['keywords'],
            $product['description']
        );
        // получаем товары, похожие на текущий
        $similar = Yii::$app->cache->get('similar-'.$product['id']);
        if ($similar === false) {
            // товары из той же категории того же бренда
            $similar = Product::find()
                ->where([
                    'category_id' => $product['category_id'],
                    'brand_id' => $product['brand_id']
                ])
                ->andWhere(['NOT IN', 'id', $product['id']])
                ->limit(3)
                ->asArray()
                ->all();
            Yii::$app->cache->set('similar-'.$product['id'], $similar);
        }
        return $this->render(
            'product',
            compact('product', 'brand', 'similar')
        );
    }

    /**
     * Результаты поиска по каталогу товаров
     */
    public function actionSearch($query = '', $page = 1) {
        /*
         * Чтобы получить ЧПУ, выполняем редирект на catalog/search/query/одежда
         * после отправки поискового запроса из формы методом POST. Если строка
         * поискового запроса пустая, выполняем редирект на catalog/search.
         */
        if (Yii::$app->request->isPost) {
            $query = Yii::$app->request->post('query');
            if (is_null($query)) {
                return $this->redirect(['catalog/search']);
            }
            $query = trim($query);
            if (empty($query)) {
                return $this->redirect(['catalog/search']);
            }
            $query = urlencode(Yii::$app->request->post('query'));
            return $this->redirect(['catalog/search/query/'.$query]);
        }

        $page = (int)$page;

        // получаем результаты поиска с постраничной навигацией
        list($products, $pages) = (new Product())->getSearchResult($query, $page);

        // устанавливаем мета-теги для страницы
        $this->setMetaTags('Поиск по каталогу');

        return $this->render(
            'search',
            compact('products', 'pages')
        );
    }

}