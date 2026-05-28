<?php
// file generated with AI assistance: Claude Code - 2026-05-27 12:00:00 UTC

namespace dmstr\modules\publication\components;

use dmstr\modules\publication\models\crud\PublicationItem;
use dmstr\modules\publication\Module;
use FeedIo\Feed;
use FeedIo\FeedIo;
use Yii;
use yii\helpers\Url;

/**
 * Builds an RSS feed (FeedIo\Feed) from PublicationItem instances and renders
 * it to an XML string. Channel metadata is read from the publication Module.
 */
class PublicationFeedBuilder
{
    private Module $module;

    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * @param PublicationItem[] $items
     * @param string $feedUrl absolute URL of the feed itself (used as self-link)
     */
    public function buildFeed(array $items, string $feedUrl): Feed
    {
        $feed = new Feed();
        $feed->setTitle($this->module->rssChannelTitle ?? Yii::$app->name);
        $feed->setDescription((string)($this->module->rssChannelDescription ?? ''));
        $feed->setLanguage($this->module->rssChannelLanguage ?? Yii::$app->language);
        $feed->setLink(Url::base(true));
        $feed->setPublicId($feedUrl);
        $feed->setLastModified(new \DateTime());

        foreach ($items as $item) {
            $feed->add($this->buildItem($feed, $item));
        }

        return $feed;
    }

    public function renderRss(Feed $feed): string
    {
        return (new FeedIo())->toRss($feed);
    }

    private function buildItem(Feed $feed, PublicationItem $item): \FeedIo\Feed\Item
    {
        $detailUrl = Url::to(['/publication/default/detail', 'itemId' => $item->id], true);

        $feedItem = $feed->newItem();
        $feedItem->setTitle((string)$item->title);
        $feedItem->setLink($detailUrl);
        $feedItem->setPublicId($detailUrl);
        $feedItem->setLastModified($this->resolveLastModified($item));

        $content = $this->resolveContent($item);
        if ($content !== '') {
            $feedItem->setContent($content);
            $feedItem->setSummary($content);
        }

        return $feedItem;
    }

    private function resolveContent(PublicationItem $item): string
    {
        if (!is_callable($this->module->rssItemContentCallback)) {
            return '';
        }
        return (string)call_user_func($this->module->rssItemContentCallback, $item);
    }

    private function resolveLastModified(PublicationItem $item): \DateTime
    {
        // release_date is a "Y-m-d H:i:s" string; updated_at is a unix timestamp.
        // Prefer the later of the two so edits after release are reflected.
        $candidates = [];
        if (!empty($item->release_date)) {
            try {
                $candidates[] = new \DateTime($item->release_date);
            } catch (\Exception $e) {
                // ignore unparsable date
            }
        }
        if (!empty($item->updated_at)) {
            $candidates[] = (new \DateTime())->setTimestamp((int)$item->updated_at);
        }
        if (empty($candidates)) {
            return new \DateTime();
        }
        usort($candidates, fn($a, $b) => $b <=> $a);
        return $candidates[0];
    }
}
