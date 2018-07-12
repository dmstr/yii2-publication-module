<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\controllers\actions;

use pheme\settings\models\Setting;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


/**
 * Class SettingAction
 * @package project\modules\crud\controllers
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property string key
 * @property string section
 * @property Setting|null _setting
 */
class SettingAction extends Action
{

    const SECTION_PUBLICATION = 'publication';

    public $key;
    public $section = self::SECTION_PUBLICATION;

    private $_setting;

    /**
     * @return bool
     * @throws InvalidConfigException
     */
    public function beforeRun()
    {

        // find setting
        $this->_setting = \Yii::$app->settings->get($this->key, $this->section);

        if ($this->_setting === null) {
            throw new NotFoundHttpException("Setting with key '{$this->key}' and section '{$this->section}' does not exist");
        }

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
        $scalar = json_decode($this->_setting->scalar);
        if ($scalar !== null) {
            $enums = $scalar;
        }

        return [
            'type' => 'string',
            'enum' => $enums
        ];
    }
}