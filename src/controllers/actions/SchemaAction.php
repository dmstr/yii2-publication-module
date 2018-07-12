<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\actions;

use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\Response;


/**
 * Class SchemaAction
 * @package project\modules\crud\controllers
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property string|ActiveRecord modelClass
 *
 * @property string primaryKey
 * @property string labelAttribute
 *
 * @property ActiveRecord[]|array _models
 */
class SchemaAction extends Action
{

    /**
     * ActiveRecord model name with namespace
     */
    public $modelClass;

    /**
     * Primary key of model, will be saved in json
     */
    public $primaryKey = 'id';

    /**
     * Label for enum options
     */
    public $labelAttribute = 'name';

    /**
     * List of models
     */
    private $_models;

    /**
     * @return bool
     * @throws InvalidConfigException
     */
    public function beforeRun()
    {
        // check if class exists and throw an invalid config exception if not
        if (!class_exists($this->modelClass)) {
            throw new InvalidConfigException("Model '{$this->modelClass}' does not exist");
        }

        // find all models
        $this->_models = $this->modelClass::find()->all();

        // set response format to json for correct ajax response
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeRun();
    }

    /**
     * @return array
     */
    public function run()
    {
        $enums = [];
        $enumTitles = [];
        foreach ($this->_models as $model) {
            $enums[] = $model->{$this->primaryKey}; // set key
            $enumTitles[] = $model->{$this->labelAttribute}; // set label
        }

        return [
            'type' => 'string',
            'enum' => $enums,
            'options' => [
                'enum_titles' => $enumTitles
            ]
        ];
    }
}