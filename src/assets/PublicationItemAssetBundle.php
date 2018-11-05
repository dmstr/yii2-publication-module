<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\assets;

use dosamigos\selectize\SelectizeAsset;
use yii\web\JqueryAsset;
use dosamigos\ckeditor\CKEditorAsset;


/**
 * Class PublicationItemAssetBundle
 * @package dmstr\modules\publication\assets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class PublicationItemAssetBundle extends \dmstr\web\AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = __DIR__ . '/web/item';


    /**
     * @var array
     */
    public $js = [
        'js/publiction-switch-schema.js',
        'js/init.js'
    ];


    /**
     * @var array
     */
    public $depends = [
        SelectizeAsset::class,
        CKEditorAsset::class,
        JqueryAsset::class
    ];
}