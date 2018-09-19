<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\MailChimp.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The MailChimp app.
 */
class MailChimp implements AppInterface
{
    /**
     * @var string
     *   The campaign id for testing purposes.
     */
    public static $testCampaignId = '1e08ab6593';

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-mailchimp';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_mailchimp';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
