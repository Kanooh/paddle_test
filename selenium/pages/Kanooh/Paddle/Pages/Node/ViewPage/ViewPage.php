<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\ViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage;

use Kanooh\Paddle\Pages\Element\Breadcrumb\Breadcrumb;
use Kanooh\Paddle\Pages\FrontEndPaddlePage;
use Kanooh\Paddle\Pages\PaddlePageWrongPathException;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for front-end node view.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $body
 *   The body field element.
 * @property Breadcrumb $breadcrumb
 *   The breadcrumb.
 * @property CommentForm|false $commentForm
 *   The comment form of the node.
 * @property bool addCommentLink
 *   Whether the addCommentLink is present or not.
 */
class ViewPage extends FrontEndPaddlePage
{
    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * {@inheritdoc}
     */
    protected $path = 'node/%';

    /**
     * The XPath to the body field of the page.
     *
     * @var string
     */
    protected $bodyXPathSelector = '//div[@id="page-content"]//div[contains(@class, "field-name-body")]';

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
    }

    /**
     * {@inheritdoc}
     */
    public function checkPath()
    {
        // Get the language prefix, if any.
        $language_prefix = MultilingualService::getLanguagePathPrefix($this->webdriver);
        $css_class = "page-node";

        // Add the i18n language class when multilingual is turned on.
        if (!empty($language_prefix)) {
            $css_class .= ".i18n-" . $language_prefix;
        }

        // Do not check the path since we might be using path aliases. Instead
        // check for a CSS class on the HTML body element.
        try {
            $this->webdriver->byCss('body.' . $css_class);
        } catch (\Exception $e) {
            throw new PaddlePageWrongPathException($this->path, $this->webdriver->url());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPathArguments()
    {
        // Do not check for the actual path arguments since we might be using
        // url aliases. Instead get the nid from the body classes.
        $classes = ' ' . $this->webdriver->byXPath('//body')->attribute('class') . ' ';
        $matches = array();
        preg_match('/ page-node-(\d+) /', $classes, $matches);
        return !empty($matches[1]) ? array($matches[1]) : array();
    }

    /**
     * Returns the node ID of the current page.
     *
     * This is derived from the body class rather than from the URL so that it
     * also works on aliased URLs.
     *
     * @return int
     *   The node ID of the node that is shown on this page.
     *
     * @throws \Exception
     *   Thrown when the node ID could not be found in the body classes.
     */
    public function getNodeId()
    {
        $classes = explode(' ', $this->webdriver->byXPath('//body')->attribute('class'));
        foreach ($classes as $class) {
            // Check if the string is at least 11 characters long to avoid a
            // false match on the class 'page-node-' without an actual id.
            if (strlen($class) > 10 && substr($class, 0, 10) === 'page-node-') {
                return (int) substr($class, 10);
            }
        }

        throw new \Exception('The node ID could not be derived from the body classes.');
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'body':
                $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($this->bodyXPathSelector));

                if ((bool) count($elements)) {
                    return $this->webdriver->byXPath($this->bodyXPathSelector);
                }
                return false;
            case 'commentForm':
                try {
                    return new CommentForm($this->webdriver, $this->webdriver->byXPath('//form[contains(@class, "comment-form")]'));
                } catch (\Exception $e) {
                    return false;
                }
                // No break.
            case 'addCommentLink':
                $xpath = '//a[text() = "Add new comment"]';
                try {
                    $this->webdriver->byXPath($xpath);
                    return true;
                } catch (\Exception $e) {
                    return false;
                }
                // No break.
        }
        return parent::__get($property);
    }

    /**
     * Adds a comment to the node using the comment form.
     *
     * @param string $body
     *   The text to set as body of the comment.
     * @param string $name
     *   The text to set as name of the person posting the comment. If this is
     *   set it also means that the comment is anonymous as logged-in users
     *   cannot enter their name.
     *
     * @return int|bool
     *   The comment id, false if not found.
     */
    public function postComment($body, $name = '')
    {
        $this->commentForm->comment->waitUntilDisplayed();
        $this->commentForm->comment->fill($body);

        if ($name) {
            $this->commentForm->name->fill($name);
        }
        $this->commentForm->save->click();

        if ($name) {
            $this->webdriver->waitUntilTextIsPresent('Your comment has been queued for review by site administrators and will be published after approval.');
        } else {
            $this->webdriver->waitUntilTextIsPresent('Your comment has been posted.');
        }

        $query = new \EntityFieldQuery();
        $query->entityCondition('entity_type', 'comment')
            ->fieldCondition('comment_body', 'value', $body);
        $results = $query->execute();

        if (isset($results['comment'])) {
            $cids = array_keys($results['comment']);
            return $cids[0];
        }

        return false;
    }


    /**
     * Helper method to add a comment to the current node.
     *
     * @param string $comment
     *   The text of the comment.
     */
    public function addComment($comment = '')
    {
        $comment = !empty($comment) ? $comment : $this->alphanumericTestDataProvider->getValidValue();
        $this->commentForm->comment->fill($comment);
        $this->commentForm->save->click();
        $this->webdriver->waitUntilTextIsPresent('Your comment has been posted.');
    }
}
