<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\ContactPerson.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Contact Person app.
 */
class ContactPerson implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-contact-person';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_contact_person';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
