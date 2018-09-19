<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Poll\PollPage.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Poll;

use Kanooh\Paddle\Pages\Node\EditPage\EditPage;

/**
 * Page to edit a poll.
 *
 * @property PollForm $pollForm
 *   The edit news item form.
 */
class PollPage extends EditPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'pollForm':
                return new PollForm($this->webdriver, $this->webdriver->byId('poll-node-form'));
        }
        return parent::__get($property);
    }
}
