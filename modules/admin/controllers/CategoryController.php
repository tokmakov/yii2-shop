<?php
namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\Category;
use app\modules\admin\models\Product;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * Класс CategoryController реализует CRUD для категорий
 */
class CategoryController extends AdminController {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Список всех категорий каталога товаров
     */
    public function actionIndex() {
        return $this->render(
            'index',
            ['categories' => Category::getAllCategories()]
        );
    }

    /**
     * Просмотр данных существующей категории
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Список всех товаров категории
     */
    public function actionProducts($id) {
        // получаем массив идентификаторов всех потомков категории,
        // чтобы запросом выбрать товары и в дочерних категориях
        $ids = Category::getAllChildIds($id);
        $ids[] = $id;
        $products = new ActiveDataProvider([
            'query' => Product::find()->where(['in', 'category_id', $ids])
        ]);
        return $this->render(
            'products',
            [
                'category' => $this->findModel($id),
                'products' => $products,
            ]
        );
    }

    /**
     * Создание новой категории
     */
    public function actionCreate() {
        $model = new Category();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // загружаем изображение и выполняем resize исходного изображения
            $model->upload = UploadedFile::getInstance($model, 'image');
            if ($name = $model->uploadImage()) { // если изображение было загружено
                // сохраняем в БД имя файла изображения
                $model->image = $name;
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Обновление существующей категории
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        // старое изображение, которое надо удалить, если загружено новое
        $old = $model->image;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // загружаем изображение и выполняем resize исходного изображения
            $model->upload = UploadedFile::getInstance($model, 'image');
            if ($new = $model->uploadImage()) { // если изображение было загружено
                // удаляем старое изображение
                if (!empty($old)) {
                    $model::removeImage($old);
                }
                // сохраняем в БД новое имя
                $model->image = $new;
            } else { // оставляем старое изображение
                $model->image = $old;
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление существующей категории
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Поиск категории по идентификатору
     */
    protected function findModel($id) {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
