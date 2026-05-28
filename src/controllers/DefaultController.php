<?php

namespace dmstr\modules\publication\controllers;

use dmstr\modules\publication\components\PublicationFeedBuilder;
use dmstr\modules\publication\components\PublicationHelper;
use dmstr\modules\publication\models\crud\PublicationItem;
use dmstr\modules\publication\models\crud\PublicationItemXTag;
use Yii;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * @property \dmstr\modules\publication\Module $module
*/
class DefaultController extends Controller
{
    /**
     *
     * @throws BadRequestHttpException
     * @return string
     */
    public function actionIndex()
    {
        $categoryId = Yii::$app->request->get('categoryId', PublicationHelper::ALL);
        $tagId = Yii::$app->request->get('tagId', null);
        $limit = Yii::$app->request->get('limit');

        if ($categoryId !== null && !$this->checkParam($categoryId)) {
            throw new BadRequestHttpException(Yii::t('publication', 'Invalid config for param category id'));
        }

        if ($tagId !== null && !$this->checkParam($tagId)) {
            throw new BadRequestHttpException(Yii::t('publication', 'Invalid config for param tag id'));
        }

        if ($limit !== null && (!is_numeric($limit) || $limit < 1)) {
            throw new BadRequestHttpException(Yii::t('publication', 'Invalid config for param limit'));
        }

        return $this->render('index', ['categoryId' => $categoryId,'tagId' => $tagId, 'limit' => $limit]);
    }

    /**
     *
     * @throws BadRequestHttpException
     * @return string
     */
    public function actionTag()
    {
        $tagId = Yii::$app->request->get('tagId', PublicationHelper::ALL);
        $categoryId = Yii::$app->request->get('categoryId', null);
        $limit = Yii::$app->request->get('limit');


        if ($tagId !== null && !$this->checkParam($tagId)) {
            throw new BadRequestHttpException(Yii::t('publication', 'Invalid config for param tag id'));
        }

        if ($categoryId !== null && !$this->checkParam($categoryId)) {
            throw new BadRequestHttpException(Yii::t('publication', 'Invalid config for param category id'));
        }

        if ($limit !== null && (!is_numeric($limit) || $limit < 1)) {
            throw new BadRequestHttpException(422, Yii::t('publication', 'Invalid config for param limit'));
        }

        return $this->render('tag', ['tagId' => $tagId, 'limit' => $limit, 'categoryId' => $categoryId]);
    }

    private function checkParam($param)
    {
        if (!\is_array($param)) {
            return is_numeric($param) || $param === PublicationHelper::ALL;
        }
        foreach ($param as $item) {
            if (!is_numeric($item)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $itemId
     *
     * @throws HttpException
     * @return string
     */
    public function actionDetail($itemId)
    {
        $query = PublicationItem::find()->andWhere([PublicationItem::tableName() . '.id' => $itemId]);

        // Check if user is allowed to access the item by defined role from module
        if (empty($this->module->previewItemRole) || !Yii::$app->getUser()->can($this->module->previewItemRole)) {
            $query->published();
        }
        $item = $query->one();

        if ($item === null) {
            throw new NotFoundHttpException(\Yii::t('publication', 'Publication item not found'));
        }

        $item->setScenario('crud');

        return $this->render('detail', ['item' => $item]);

    }

    /**
     * Renders an RSS 2.0 feed of published PublicationItems.
     *
     * Honors the same release-date/status/end-date/language constraints as the
     * widgets (via PublicationItemQuery::published()). Accepts the same filter
     * params as actionIndex (categoryId, tagId, limit).
     *
     * @throws BadRequestHttpException
     */
    public function actionRss(): string
    {
        $categoryId = Yii::$app->request->get('categoryId', PublicationHelper::ALL);
        $tagId = Yii::$app->request->get('tagId');
        $limit = Yii::$app->request->get('limit', $this->module->rssDefaultLimit);

        if (!$this->checkParam($categoryId)) {
            throw new BadRequestHttpException(Yii::t('publication', 'Invalid config for param category id'));
        }
        if ($tagId !== null && !$this->checkParam($tagId)) {
            throw new BadRequestHttpException(Yii::t('publication', 'Invalid config for param tag id'));
        }
        if (!is_numeric($limit) || $limit < 1) {
            throw new BadRequestHttpException(Yii::t('publication', 'Invalid config for param limit'));
        }

        $items = $this->buildRssQuery($categoryId, $tagId, (int)$limit)->all();

        $builder = new PublicationFeedBuilder($this->module);
        $feed = $builder->buildFeed($items, Url::current([], true));

        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->set('Content-Type', 'application/rss+xml; charset=utf-8');

        return $builder->renderRss($feed);
    }

    private function buildRssQuery($categoryId, $tagId, int $limit): \dmstr\modules\publication\models\crud\query\PublicationItemQuery
    {
        $query = PublicationItem::find()->published();

        if ($categoryId !== PublicationHelper::ALL && !empty($categoryId)) {
            $query->andWhere([PublicationItem::tableName() . '.category_id' => (array)$categoryId]);
        }

        if (!empty($tagId) && $tagId !== PublicationHelper::ALL) {
            $query->innerJoin(
                ['x' => PublicationItemXTag::tableName()],
                PublicationItem::tableName() . '.id = x.item_id'
            );
            $query->andWhere(['x.tag_id' => (array)$tagId]);
            $query->groupBy(PublicationItem::tableName() . '.id');
        }

        return $query->limit($limit);
    }
}
