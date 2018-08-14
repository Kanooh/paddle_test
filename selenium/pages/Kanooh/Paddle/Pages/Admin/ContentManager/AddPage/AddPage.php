<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\AddPage;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The 'Add' page of the Paddle Content Manager module.
 *
 * @property CreateNodeModal $createNodeModal
 *   The title modal to create a new node.
 * @property AddPageLinks $links
 *   The links on the page.
 */
class AddPage extends PaddlePage
{

    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content_manager/add';

    /**
     * The title modal to create a new node.
     *
     * @var CreateNodeModal
     */
    protected $createNodeModal;

    /**
     * The links on the page.
     *
     * @var AddPageLinks
     */
    protected $links;

    /**
     * The title modal to create a new node.
     *
     * This function, an explicit getter, only exists to make the otherwise
     * magic property accessible from within this class itself.
     * @todo Remove this once createLandingPage() and createNode() are gone.
     *
     * @return CreateNodeModal
     */
    private function getCreateNodeModal()
    {
        if (!isset($this->createNodeModal)) {
            $this->createNodeModal = new CreateNodeModal($this->webdriver);
        }
        return $this->createNodeModal;
    }

    /**
     * The links on the page.
     *
     * This function, an explicit getter, only exists to make the otherwise
     * magic property accessible from within this class itself.
     * @todo Remove this once createNode() is gone.
     *
     * @return AddPageLinks
     */
    private function getLinks()
    {
        if (!isset($this->links)) {
            $this->links = new AddPageLinks($this->webdriver);
        }
        return $this->links;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'createNodeModal':
                return $this->getCreateNodeModal();
            case 'links':
                return $this->getLinks();
        }
        return parent::__get($property);
    }

    /**
     * Creates a node of the given type with an optional title.
     *
     * @deprecated Use \Kanooh\Paddle\Utilities\ContentCreationService instead.
     *
     * @param string $type
     *   The content type of the node to be created. This should be in CamelCase
     *   format, eg. 'BasicPage'. For a full list of options, see the keys of
     *   the array returned by AddPageLinks::linkInfo().
     * @param string $title
     *   Optional title for the new node. Defaults to a random string.
     *
     * @return int
     *   The nid of the created node.
     *
     * @see AddPageLinks::linkInfo()
     */
    public function createNode($type, $title = null)
    {
        // Default to a random title.
        $random = new Random();
        $title = $title ?: $random->name(8);

        // Check that we are on the right page.
        $this->checkPath();

        // Click on the appropriate link.
        $this->getLinks()->{'link' . $type}->click();

        // Fill in the title in the modal.
        $this->getCreateNodeModal()->waitUntilOpened();
        $this->webdriver->assertTextPresent('information modal dialog');
        $this->createNodeModal->title->fill($title);

        $this->getCreateNodeModal()->submit();
        $this->getCreateNodeModal()->waitUntilClosed();

        // Wait until we see confirmation that the node has been created.
        $this->webdriver->waitUntilElementIsPresent('//div[@id="messages"]');
        $this->webdriver->waitUntilTextIsPresent('has been created.');

        // We land on the administrative node view.
        $admin_node_view = new ViewPage($this->webdriver);
        $admin_node_view->checkArrival();

        return $admin_node_view->getNodeIDFromUrl();
    }

    /**
     * Create a landing page with chosen layout and an optional title.
     *
     * @todo Rewrite this to not use any direct calls to the webdriver, use the
     *   class methods instead.
     *
     * @deprecated Use createLandingPage() from
     *   \Kanooh\Paddle\Utilities\ContentCreationService instead.
     *
     * @param string $layout
     *   Title of the layout.
     * @param string $title
     *   Title for the new landing page.
     *
     * @return int
     *   The nid of the created landing page.
     */
    public function createLandingPage($layout, $title = null)
    {
        // Check that we are on the right page.
        $this->checkPath();

        if (empty($title)) {
            $random = new Random();
            $title = $random->name(8);
        }

        // Choose a landing page layout.
        $this->webdriver->waitUntilTextIsPresent('Add new landing page');
        $layout_image = $this->getLandingPageLayoutImage($layout);
        $layout_image->click();

        // Wait until the modal dialog is entirely loaded.
        $this->getCreateNodeModal()->waitUntilOpened();
        $this->webdriver->assertTextPresent('Landing Page information modal dialog');
        $this->createNodeModal->title->fill($title);

        $this->getCreateNodeModal()->submit();
        $this->getCreateNodeModal()->waitUntilClosed();

        // Wait until we see confirmation that the landing page has been created.
        $this->webdriver->waitUntilElementIsPresent('//div[@id="messages"]');
        $this->webdriver->waitUntilTextIsPresent('has been created.');

        // We land on the admin node view.
        $admin_node_view = new ViewPage($this->webdriver);
        $admin_node_view->checkArrival();

        return $admin_node_view->getNodeIDFromUrl();
    }

    /**
     * Get the landing page layout image by its title.
     *
     * @param string $layout_title
     *   The title of the layout.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   Image tag element.
     */
    public function getLandingPageLayoutImage($layout_title)
    {
        $xpath = '//ul[@class="layout"]//a[@title="' . $layout_title . '"]//img';
        $element = $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
        return $element;
    }
}
