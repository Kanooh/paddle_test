<?php
/**
 * @file
 * Definition of Drupal\Tests\paddle_apps\FixedAppRepository.
 */

namespace Drupal\Tests\paddle_apps;

use Drupal\paddle_apps\App;
use Drupal\paddle_apps\AppRepository;

/**
 * Implementation of an app repository for testing purposes.
 */
class FixedAppRepository implements AppRepository
{
    /**
     * The list of active apps.
     *
     * @var App[]
     */
    protected $activeApps;

    /**
     * Constructs a new app repository, with a list of active apps.
     *
     * @param App[] $activeApps
     *   A list of active apps.
     */
    public function __construct($activeApps = array())
    {
        $this->activeApps = $activeApps;
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveApps()
    {
        return $this->activeApps;
    }
}
