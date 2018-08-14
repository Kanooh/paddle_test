<?php
/**
 * @file
 */

namespace Kanooh\Paddle\Core\Scald\Search;

/**
 * Test searches on atom entities in the media library.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class AssetsPageSearchTest extends SearchTestBase
{
    /**
     * {@inheritdoc}
     */
    protected function executeStepsTowardsLibrary()
    {
        $this->assetsPage->go();
    }

    /**
     * {@inheritdoc}
     */
    protected function getLibrary()
    {
        return $this->assetsPage->library;
    }
}
