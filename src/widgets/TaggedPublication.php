<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\widgets;

use Exception;
use yii\helpers\Html;
use Yii;

/**
 * Class TaggedPublication
 * @package dmstr\modules\publication\widgets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property int|array tagId
 */
class TaggedPublication extends BasePublication
{

    public $pagination = true;
    public $wrapperCssClass = 'publication-widget publication-item-tagged';

}