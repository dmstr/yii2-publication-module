<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\controllers\crud\actions;


use dmstr\modules\publication\components\PublicationHelper;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\web\HttpException;

/**
 * Class Create
 * @package dmstr\modules\publication\controllers\crud\actions
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property ActiveRecord model
 * @property string redirectAction
 */
class Create extends Action
{
    public $model;
    public $redirectAction = 'view';

    /**
     * @return mixed
     * @throws HttpException
     */
    public function run()
    {
        /** @var ActiveRecord|PublicationItem $model */
        $model = new $this->model;

        $model->ref_lang = Yii::$app->language;

        if (PublicationHelper::checkBaseModelAccess($model)) {
            try {
                if ($model->load(\Yii::$app->request->post()) && $model->save()) {
                    return $this->controller->redirect([$this->redirectAction, 'id' => $model->id]);
                }
                if (!\Yii::$app->request->isPost) {
                    $model->load(\Yii::$app->request->get());
                }
            } catch (\Exception $e) {
                $msg = isset($e->errorInfo[2]) ?? $e->getMessage();
                $model->addError('_exception', $msg);
            }
            return $this->controller->render($this->id, ['model' => $model]);
        }
        throw new HttpException(403, \Yii::t('publication', 'Access denied'));
    }
}