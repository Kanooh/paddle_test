<?php
/**
 * @file
 * Definition of Drupal\Tests\paddle_apps\AppTest.
 */

namespace Drupal\Tests\paddle_apps;

use Drupal\paddle_apps\App;

/**
 * Tests the Drupal\paddle_apps\App class.
 */
class AppTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Provides valid app levels.
     *
     * @return array
     *   The valid levels.
     *
     * @see testGetLevelReturnsLevel()
     */
    public function levels()
    {
        return array(
          array(App::LEVEL_EXTRA),
          array(App::LEVEL_FREE),
        );
    }

    /**
     * Tests if the level passed to the constructor gets returned by getLevel().
     *
     * @dataProvider levels
     * @param string $level
     *   The app level to test with.
     */
    public function testGetLevelReturnsLevel($level)
    {
        $info = array(
          'paddle' => array(
            'level' => $level,
          ),
        );

        $app = new App($info);

        $this->assertEquals($level, $app->getLevel());
    }

    /**
     * Tests that when no level is provided, the level is set to 'free'.
     */
    public function testLevelDefaultsToFree()
    {
        $info = array();

        $app = new App($info);

        $this->assertEquals(App::LEVEL_FREE, $app->getLevel());
    }

    /**
     * Tests that an exception is thrown when an invalid level is provided.
     *
     * @expectedException \Drupal\paddle_apps\InvalidAppLevelException
     * @expectedExceptionMessage The specified level foo of app paddle_foo is not valid.
     */
    public function testInvalidValueForLevelIsNotAllowed()
    {
        $info = array(
          'paddle' => array(
            'level' => 'foo',
          ),
          'name' => 'paddle_foo',
        );

        new App($info);
    }
}
