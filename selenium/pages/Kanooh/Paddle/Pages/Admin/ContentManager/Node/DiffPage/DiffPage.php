<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\DiffPage\DiffPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\DiffPage;

use Kanooh\Paddle\Pages\Element\Links\ContentAdminMenuLinks;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * Diff view between 2 revisions.
 *
 * @property ContentAdminMenuLinks $adminMenuLinks
 *   The admin menu links.
 * @property DiffPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property DiffsNavigationLinks $navigationLinks
 *   The links to navigate through diff pages.
 */
class DiffPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'node/%/moderation/diff/view/%/%';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'adminMenuLinks':
                return new ContentAdminMenuLinks($this->webdriver);
            case 'contextualToolbar':
                return new DiffPageContextualToolbar($this->webdriver);
            case 'navigationLinks':
                // Using the webdriver for the Links classes is deprecated,
                // but the diff page can have no navigation, so we don't have
                // an element to pass.
                return new DiffsNavigationLinks($this->webdriver);
        }
        return parent::__get($property);
    }

    /**
     * Checks if the exact added text without HTML tags is present on the page.
     *
     * @param string $text
     *   The text to check for.
     *
     * @return bool
     *   True if the exact text has been found, false otherwise.
     */
    public function checkExactTextAddedPresent($text)
    {
        $xpath = '//table[contains(@class, "diff")]//tr//td[contains(@class, "diff-addedline")]/div[contains(., "' . $text . '")]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        return (bool) count($elements);
    }

    /**
     * Checks if the exact deleted text without HTML tags is present on the page.
     *
     * @param string $text
     *   The text to check for.
     *
     * @return bool
     *   True if the exact text has been found, false otherwise.
     */
    public function checkExactTextDeletedPresent($text)
    {
        $xpath = '//table[contains(@class, "diff")]//tr//td[contains(@class, "diff-deletedline")]/div[contains(., "' . $text . '")]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        return (bool) count($elements);
    }

    /**
     * Checks if the requested image is present on the page.
     *
     * @param string $image
     *   An identifier to search for in the src url.
     *
     * @return bool
     *   True if the exact text has been found, false otherwise.
     */
    public function checkImagePresent($image)
    {
        $xpath = '//table[contains(@class, "diff")]//tr//td[contains(@class, "diff-addedline")]/div/img[contains(@src, "' . $image . '")]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        return (bool) count($elements);
    }

    /**
     * Gets the revert link for a revision.
     *
     * @param int $nid
     *   The nid to do the revert for.
     * @param int $vid
     *   The vid to get the link for.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The revert link.
     */
    public function getRevertLink($nid, $vid)
    {
        $url = url('node/' . $nid . '/revisions/' . $vid . '/revert');
        $xpath = '//table[contains(@class, "diff")]//thead//a[contains(@href, "' . $url . '")]';
        $element = $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
        return $element;
    }
}
