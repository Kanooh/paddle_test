<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\NewsletterViewPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage;

/**
 * The contextual toolbar for the administrative node view of a newsletter node.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSendTestEmail
 *   The "Send test e-mail" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSendCampaign
 *   The "Send campaign" button.
 */
class NewsletterViewPageContextualToolbar extends LandingPageViewPageContextualToolbar
{

    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        // Defines all the possible contextual buttons.
        $buttons = array(
            'SendTestEmail' => array(
                'title' => 'Send test e-mail',
            ),
            'SendCampaign' => array(
                'title' => 'Send',
            ),
        );
        return $buttons + parent::buttonInfo();
    }
}
