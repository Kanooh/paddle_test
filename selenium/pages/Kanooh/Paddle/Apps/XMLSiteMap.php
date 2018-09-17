<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\ReCaptcha.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The ReCaptcha app.
 */
class XMLSiteMap implements AppInterface
{

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-xml-sitemap';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_xml_sitemap';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
