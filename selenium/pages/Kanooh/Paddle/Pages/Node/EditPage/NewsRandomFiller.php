<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\NewsRandomFiller.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\TestDataProvider\AlphanumericTestDataProvider;

/**
 * Fills in the news node edit form.
 */
class NewsRandomFiller
{

    /**
     * The alphanumeric test data provider.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * The title.
     *
     * @var string
     */
    public $title;

    /**
     * The body text.
     *
     * @var string
     */
    public $body;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
    }

    /**
     * Randomize the fields of the form.
     *
     * @return $this
     *   Returns the filler.
     */
    public function randomize()
    {
        $this->title = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->body = $this->alphanumericTestDataProvider->getValidValue(626);

        return $this;
    }

    /**
     * Fills the form fields for a specific page.
     *
     * @param NewsPage $page
     *   The page for which to form the fields.
     */
    public function fill(NewsPage $page)
    {
        $page->newsForm->title->fill($this->title);
        $page->body->setBodyText($this->body);
    }
}
