<?php
namespace app\controllers;

use app\models\Page;
use Yii;
use yii\web\Controller;

class AppController extends Controller {

    /**
     * Для передачи меню страниц сайта в layout-шаблон
     */
    public $pageMenu;

    /**
     * Метод устанавливает мета-теги для страницы сайта
     * @param string $title
     * @param string $keywords
     * @param string $description
     */
    protected function setMetaTags($title = '', $keywords = '', $description = '') {
        $this->view->title = $title ?: Yii::$app->params['defaultTitle'];
        $this->view->registerMetaTag([
            'name' => 'keywords',
            'content' => $keywords ?: Yii::$app->params['defaultKeywords']
        ]);
        $this->view->registerMetaTag([
            'name' => 'description',
            'content' => $description ?: Yii::$app->params['defaultDescription']
        ]);
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action) {
        $this->pageMenu = Page::getTree();
        return parent::beforeAction($action);
    }
}