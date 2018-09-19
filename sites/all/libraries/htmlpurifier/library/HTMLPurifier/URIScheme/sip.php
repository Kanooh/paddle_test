<?php

/**
 * Validates sip (Session Initiated Protocol) according to RFC 3261
 */

class HTMLPurifier_URIScheme_sip extends HTMLPurifier_URIScheme
{
    /**
     * @type bool
     */
    public $browsable = false;

    /**
     * @type bool
     */
    public $may_omit_host = true;

    /**
     * @param HTMLPurifier_URI $uri
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return bool
     */
    public function doValidate(&$uri, $config, $context)
    {
        $uri->userinfo = null;
        $uri->host     = null;
        $uri->port     = null;
        // @todo we need to validate path against RFC 3261
        return true;
    }
}

// vim: et sw=4 sts=4
