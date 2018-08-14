<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Quiz.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Quiz app.
 */
class Quiz implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-quiz';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_quiz';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
