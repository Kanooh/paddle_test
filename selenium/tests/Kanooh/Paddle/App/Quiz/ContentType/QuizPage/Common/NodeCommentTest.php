<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Quiz\ContentType\QuizPage\Common\NodeCommentTest.
 */

namespace Kanooh\Paddle\App\Quiz\ContentType\QuizPage\Common;

use Kanooh\Paddle\Apps\Quiz;
use Kanooh\Paddle\App\Comment\ContentType\Base\NodeCommentTestBase;
use Kanooh\Paddle\Pages\Node\ViewPage\QuizViewPage;

/**
 * Class NodeCommentTest.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeCommentTest extends NodeCommentTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new Quiz);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createQuizPageViaUI($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getContentTypeName()
    {
        return 'quiz_page';
    }

    /**
     * Quiz specific test to test the placement of the comment box.
     */
    public function testCommentPlacement()
    {
        $type_name = $this->getContentTypeName();
        $this->configurePage->go();
        $content_type = 'type' . str_replace(' ', '', ucwords(str_replace('_', ' ', $type_name)));

        // Now check the content type settings.
        $this->configurePage->form->$content_type->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Open up comments for a newly created quiz and go to the front end.
        $nid = $this->setupNode();
        $this->editPage->go($nid);
        $this->editPage->commentRadioButtons->open->select();
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $front_page = new QuizViewPage($this);
        $front_page->go($nid);

        // Verify the comments are placed below the quiz itself.
        $this->assertTrue($front_page->checkCommentsBelowParticipationForm());

        // Reset the defaults.
        $this->configurePage->go();
        $this->configurePage->form->$content_type->uncheck();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');
    }
}
