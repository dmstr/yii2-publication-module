<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;


/**
 * Class PublicationItemAssetBundle
 * @package dmstr\modules\publication\assets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class PublicationItemAssetBundle extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = __DIR__.'/web';


    /**
     * @var array
     */
    public $js = [
        'js/publiction-switch-schema.js'
    ];


    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class
    ];
}