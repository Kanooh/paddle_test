<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\WhoIsWho\WhoIsWhoPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\WhoIsWho;

use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\WhoIsWho\WhoIsWhoPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Who is who pane.
 *
 * @property TeamMember[] $teamMembers
 */
class WhoIsWhoPane extends Pane
{

    /**
     * @var WhoIsWhoPanelsContentType
     */
    public $contentType;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid = '', $pane_xpath_selector = '')
    {
        parent::__construct($webdriver, $uuid, $pane_xpath_selector);

        $this->contentType = new WhoIsWhoPanelsContentType($this->webdriver);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'teamMembers':
                $team_members = array();
                $xpath = $this->xpathSelector . '//a//div[@class="team-member"]';
                $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
                foreach ($elements as $element) {
                    $team_member = new TeamMember($element);
                    $team_members[$team_member->name] = $team_member;
                }
                return $team_members;
                break;
        }

        throw new \Exception("Property with name $name not defined");
    }
}
