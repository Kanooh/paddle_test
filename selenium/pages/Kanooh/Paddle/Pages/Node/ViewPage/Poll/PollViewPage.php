<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\Poll\PollViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\Poll;

use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Element\Poll\PollView;

/**
 * Class representing a Poll page in the frontend view.
 *
 * @property PollView $pollView
 */
class PollViewPage extends ViewPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'pollView':
                $element = $this->webdriver->byCssSelector('div.pane-content div.node-poll');
                return new PollView($this->webdriver, $element);
                break;
        }
        return parent::__get($property);
    }
}
