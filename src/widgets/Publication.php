<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\widgets;

use dmstr\modules\publication\models\crud\PublicationItem;
use Exception;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use Yii;


/**
 * Class Publication
 * @package dmstr\modules\publication\widgets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 */
class Publication extends BasePublication
{

    public $wrapperCssClass = 'publication-widget publication-item-index';

}