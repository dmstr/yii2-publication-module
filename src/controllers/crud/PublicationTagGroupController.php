<?php


namespace dmstr\modules\publication\controllers\crud;

use dmstr\modules\publication\assets\PublicationAttachAssetBundle;
use dmstr\modules\publication\models\crud\PublicationItem;
use dmstr\modules\publication\models\crud\PublicationItemXTag;
use dmstr\modules\publication\models\crud\PublicationTag;
use dmstr\modules\publication\models\crud\PublicationTagGroup;
use dmstr\modules\publication\models\crud\PublicationTagGroupXTag;
use dmstr\modules\publication\models\crud\search\PublicationTagGroup as PublicationTagGroupSearch;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * This is the class for controller "PublicationTagController".
 */
class PublicationTagGroupController extends BaseController
{

    public $model = PublicationTagGroup::class;
    public $searchModel = PublicationTagGroupSearch::class;

    public function actionAttach($id)
    {
        /** @var PublicationTagGroup $model */
        $model = $this->model::findOne($id);

        if ($model->ref_lang !== Yii::$app->language) {
            throw new HttpException(500, Yii::t('publication', 'You are not allowed to do that.'));
        }

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('publication', 'Publication tag group does not exist'));
        }

        $attachedTags = $model->tags;

        $otherTagsQuery = PublicationTag::find()->where(['NOT IN', 'id', ArrayHelper::map($attachedTags, 'id', 'id')]);
        $qLangs = [Yii::$app->language];
        if (!empty(\Yii::$app->params['fallbackLanguages'][\Yii::$app->language])) {
            $qLangs[] = \Yii::$app->params['fallbackLanguages'][\Yii::$app->language];
        }
        $otherTagsQuery->andWhere(['ref_lang' => $qLangs]);

        $otherTags = $otherTagsQuery->all();

        PublicationAttachAssetBundle::register($this->view);

        return $this->render($this->action->id, [
            'model' => $model,
            'attachedTags' => $attachedTags,
            'otherTags' => $otherTags
        ]);
    }

    public function actionAttachTags()
    {
        if (\Yii::$app->request->isAjax) {
            $tagGroupId = \Yii::$app->request->post('itemId');

            if (!is_numeric($tagGroupId)) {
                return false;
            }

            /** @var Transaction $transaction */
            $transaction = \Yii::$app->db->beginTransaction();
            PublicationTagGroupXTag::deleteAll(['tag_group_id' => $tagGroupId]);

            foreach ((array)\Yii::$app->request->post('tagIds') as $tagId) {
                $tagGroupXTag = new PublicationTagGroupXTag(['tag_group_id' => $tagGroupId, 'tag_id' => $tagId]);

                if (!$tagGroupXTag->save()) {
                    $transaction->rollBack();
                    return false;
                }
            }
            $transaction->commit();
            return true;
        }
        return $this->redirect('index');
    }
}
