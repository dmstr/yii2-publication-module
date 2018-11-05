<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\assets;


use yii\jui\JuiAsset;

/**
 * Class PublicationAttachAssetBundle
 * @package dmstr\modules\publication\assets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class PublicationAttachAssetBundle extends \dmstr\web\AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = __DIR__ . '/web/attach';


    /**
     * @var array
     */
    public $js = [
        'js/main.js'
    ];

    public $css = [
      'css/tags-sortable.less'
    ];


    /**
     * @var array
     */
    public $depends = [
        JuiAsset::class
    ];
}