<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\widgets;

use dmstr\modules\publication\models\crud\PublicationCategory;
use dmstr\modules\publication\models\crud\PublicationItem;
use yii\data\Pagination;
use yii\helpers\VarDumper;
use yii\widgets\LinkPager;


/**
 * Class Publication
 * @package dmstr\modules\publication\widgets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property int categoryId
 * @property PublicationItem item
 * @property bool pagination
 * @property string wrapperCssClass
 * @property string paginationNextLabel
 * @property string paginationPrevLabel
 */
class Publication extends BasePublication
{
    /**
     * can be:
     * - null (no category constraint)
     * - 'all' (explicitly no category constraint)
     * - positiv integer (select items IN category ID)
     * - negativ integer (select items NOT IN category ID)
     * - array of positiv|negativ integers (same IN/NOT IN rules as for simple integer)
     * @var mixed
     */
    public $categoryId;

    public $item;
    public $pagination = false;
    public $paginationNextLabel = '&raquo;';
    public $paginationPrevLabel = '&laquo;';
    public $pageSize = 60;

    private $wrapperCssClass = 'publication-widget publication-item-index';


    /**
     * @return PublicationItem|string
     * @throws \Exception
     */
    public function run()
    {

        if ($this->item) {
            return "<div class='{$this->wrapperCssClass}'>" . $this->renderHtmlByPublicationItem($this->item) . '</div>';
        }

        $publicationItemsQuery = PublicationItem::find()->published()->limit($this->limit);

        $paginationHtml = null;

        if ($this->pagination) {
            $count      = $publicationItemsQuery->count();
            $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->pageSize]);
            $publicationItemsQuery->offset($pagination->offset);
            $publicationItemsQuery->limit($pagination->limit);
            $paginationHtml = '<div class="publication-pagination">' . LinkPager::widget(
                    [
                        'pagination'    => $pagination,
                        'nextPageLabel' => $this->paginationNextLabel,
                        'prevPageLabel' => $this->paginationPrevLabel,
                        // 'firstPageLabel' => $this->paginationPrevLabel,
                        // 'lastPageLabel' => $this->paginationNextLabel,
                    ]
                ) . '</div>';

        }

        // if we get one or a list of category_ids build constraint
        // to exclude IDs they can be dafined as negative int
        if (!empty($this->categoryId) && $this->categoryId !== 'all') {
            $inIds = [];
            $notInIds = [];
            if (is_array($this->categoryId)) {
                foreach($this->categoryId as $id) {
                    if (is_numeric($id)) {
                        (int)$id < 0 ? $notInIds[] = -(int)$id : $inIds[] = (int)$id;
                    }
                }
            } else {
                (int)$this->categoryId < 0 ? $notInIds[] = -(int)$this->categoryId : $inIds[] = (int)$this->categoryId;
            }

            if (!empty($inIds)) {
                $publicationItemsQuery->andWhere(['category_id' => $inIds]);
            }

            if (!empty($notInIds)) {
                $publicationItemsQuery->andWhere(['not', ['category_id' => $notInIds]]);
            }


        }

        $html = $this->renderItemsHtml($publicationItemsQuery->all());

        return "<div class='{$this->wrapperCssClass}'>" . $html . '</div>' . $paginationHtml;
    }

    protected function renderItemsHtml($publicationItems = [])
    {
        $html = '';
        /** @var PublicationItem[] $publicationItems */
        foreach ($publicationItems as $publicationItem) {
            $html .= $this->renderHtmlByPublicationItem($publicationItem);
        }
        return $html;
    }

}