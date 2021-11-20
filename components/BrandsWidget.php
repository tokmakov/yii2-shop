<?php
namespace app\components;

use app\models\Brand;
use yii\base\Widget;
use Yii;

/**
 * Виджет для вывода списка брендов каталога
 */
class BrandsWidget extends Widget {

    public function run() {
        // пробуем извлечь данные из кеша
        $html = Yii::$app->cache->get('widget-brands');
        if ($html === false) {
            // данных нет в кеше, получаем их заново
            $brands = (new Brand())->getPopularBrands();
            $html = $this->render('brands', ['brands' => $brands]);
            // сохраняем полученные данные в кеше
            Yii::$app->cache->set('widget-brands', $html);
        }
        return $html;
    }

}