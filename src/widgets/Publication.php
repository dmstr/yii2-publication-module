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
 * @property PublicationItem item
 * @property bool pagination
 * @property string wrapperCssClass
 * @property string paginationNextLabel
 * @property string paginationPrevLabel
 */
class Publication extends BasePublication
{
    public $item;
    public $pagination = false;
    public $paginationNextLabel = '&raquo;';
    public $paginationPrevLabel = '&laquo;';
    public $pageSize = 60;

    private $wrapperCssClass = 'publication-widget publication-item-index';


    /**
     * @throws Exception
     * @return PublicationItem|string
     */
    public function run()
    {

        if ($this->item) {
            return Html::tag('div', $this->renderHtmlByPublicationItem($this->item), ['class' => $this->wrapperCssClass]);
        }

        $publicationItemsQuery = $this->itemsQuery;

        $paginationHtml = null;

        if ($this->pagination) {
            $count = $publicationItemsQuery->count();
            $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->pageSize]);
            $publicationItemsQuery->offset($pagination->offset);
            $publicationItemsQuery->limit($pagination->limit);
            $paginationHtml = Html::beginTag('div', ['class' => 'publication-pagination']) . LinkPager::widget(
                    [
                        'pagination' => $pagination,
                        'nextPageLabel' => $this->paginationNextLabel,
                        'prevPageLabel' => $this->paginationPrevLabel,
                        // 'firstPageLabel' => $this->paginationPrevLabel,
                        // 'lastPageLabel' => $this->paginationNextLabel,
                    ]
                ) . Html::endTag('div');
        }

        return Html::tag('div', $this->renderItemsHtml($publicationItemsQuery->all()), ['class' => $this->wrapperCssClass]) . $paginationHtml;
    }

    protected function renderItemsHtml($publicationItems = [])
    {
        $html = '';
        /** @var PublicationItem[] $publicationItems */
        foreach ($publicationItems as $publicationItem) {
            try {
                $html .= $this->renderHtmlByPublicationItem($publicationItem);
            } catch (Exception $e) {
                Yii::error($e->getMessage(), __METHOD__);
            }
        }
        return $html;
    }

}