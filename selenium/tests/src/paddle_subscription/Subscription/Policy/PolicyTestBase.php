<?php
/**
 * @file
 * Definition of Drupal\Tests\paddle_subscription\Subscription\Policy\PolicyTestBase.
 */

namespace Drupal\Tests\paddle_subscription\Subscription\Policy;

use Drupal\paddle_apps\App;
use Drupal\paddle_subscription\Subscription\Policy;
use Drupal\Tests\paddle_apps\FixedAppRepository;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;

/**
 * Base class for subscription policy tests.
 */
abstract class PolicyTestBase extends \PHPUnit_Framework_TestCase
{
    /**
     * Test double for an app store.
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $appStore;

    /**
     * The subscription policy to test with.
     *
     * This should be set in setUp() in child classes before calling
     * parent::setUp().
     *
     * @var Policy
     */
    protected $policy;

    /**
     * Test double for a user store.
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $userStore;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->appStore = $this->getMock('Drupal\\paddle_apps\\AppStore', array(), array($this->policy, new FixedAppRepository()));
        $this->userStore = $this->getMock('Drupal\\paddle_user\\UserStore', array(), array($this->policy));
    }

    /**
     * Tests if a given app can be installed on app store with a varying size of
     * already installed apps (up to an app store with 100 installed apps).
     *
     * @param App $app
     *   The app.
     * @param int|null $limit
     *   The expected upper limit of apps that can be installed. Null means
     *   no limit.
     */
    protected function canInstallInAppStoreOfVaryingSizes(App $app, $limit = null)
    {

        for ($i = 0; $i <= 100; $i++) {
            $appStore = clone $this->appStore;

            $appStore
              ->expects($this->any())
              ->method('countActiveAppsByLevel')
              ->with($app->getLevel())
              ->will($this->returnValue($i));

            $can_install = $this->policy->canInstall($appStore, $app);

            if (null === $limit || $i < $limit) {
                $this->assertTrue($can_install, "Can install app when there are {$i} apps of level {$app->getLevel()} already installed.");
            } else {
                $this->assertFalse($can_install, "Can't install app when there are {$i} apps of level {$app->getLevel()} already installed.");
            }
        }
    }

    /**
     * Tests if a given user can be added on a user store with a varying size of
     * already added users (up to a user store with 100 installed apps).
     *
     * @param \stdClass $user
     *   The user.
     * @param int|null $limit
     *   The expected upper limit of users that can be installed. Null means
     *   no limit.
     */
    protected function canAddUserInUserStoreOfVaryingSizes(\stdClass $user, $limit = null)
    {

        for ($i = 0; $i <= 100; $i++) {
            $user_store = clone $this->userStore;

            $user_store
              ->expects($this->any())
              ->method('countActiveUsers')
              ->will($this->returnValue($i));

            $can_add_another_user = $this->policy->canAddAnotherUser($user_store);

            if (null === $limit || $i < $limit) {
                $this->assertTrue($can_add_another_user);
            } else {
                $this->assertFalse($can_add_another_user);
            }
        }
    }

    /**
     * Constructs an App object with the specified level.
     *
     * @param string $level
     *   The app level.
     * @return App
     *   An App object.
     */
    protected function createAppWithLevel($level)
    {
        $app_info = array(
          'paddle' => array(
            'level' => $level,
          ),
        );
        $app = new App($app_info);

        return $app;
    }

    /**
     * Constructs a User object.
     *
     * @return \stdClass
     *   A user object.
     */
    protected function createUser()
    {
        // Create the user.
        return new \stdClass();
    }

    /**
     * Tests that the policy has an upper limit of free apps.
     */
    public function testAllowsMaximumFreeApps()
    {
        $policy = $this->policy;
        $limit = null;
        if (defined(get_class($policy) . '::LIMIT_FREE')) {
            $limit = $policy::LIMIT_FREE;
        }
        $app = $this->createAppWithLevel(App::LEVEL_FREE);
        $this->canInstallInAppStoreOfVaryingSizes($app, $limit);
    }

    /**
     * Tests that the policy has no limit of extra apps.
     */
    public function testAllowsMaximumExtraApps()
    {
        $app = $this->createAppWithLevel(App::LEVEL_EXTRA);
        $this->canInstallInAppStoreOfVaryingSizes($app);
    }

    /**
     * Tests that the policy has an upper limit of users.
     */
    public function testAllowsMaximumUsers()
    {
        $policy = $this->policy;
        $limit = null;
        if (defined(get_class($policy) . '::USER_LIMIT')) {
            $limit = $policy::USER_LIMIT;
        }
        $user = $this->createUser();
        $this->canAddUserInUserStoreOfVaryingSizes($user, $limit);
    }
}
