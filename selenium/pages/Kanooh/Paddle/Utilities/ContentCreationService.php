<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\ContentCreationService.
 */

namespace Kanooh\Paddle\Utilities;

use Kanooh\Paddle\Apps\AdvancedSearch;
use Kanooh\Paddle\Apps\Calendar;
use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Apps\Formbuilder;
use Kanooh\Paddle\Apps\HolidayParticipation;
use Kanooh\Paddle\Apps\MailChimp;
use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Apps\Maps;
use Kanooh\Paddle\Apps\Poll;
use Kanooh\Paddle\Apps\Publication;
use Kanooh\Paddle\Apps\Quiz;
use Kanooh\Paddle\Apps\SimpleContact;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateCalendarItemModal;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateContactPersonModal;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateNewsletterModal;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateNodeModal;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateQuizPageModal;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\EditPage\ContactPerson\ContactPersonEditPage as ContactPersonEditPage;
use Kanooh\Paddle\Pages\Node\EditPage\ContactPersonRandomFiller;
use Kanooh\Paddle\Pages\Node\EditPage\Poll\PollPage;
use Kanooh\Paddle\Pages\Node\TranslatePage\TranslatePage;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\Paddle\Utilities\MailChimpService;
use Kanooh\WebDriver\WebDriverTestCase;
use stdClass;

/**
 * Utility class to help creating content.
 */
class ContentCreationService
{
    /**
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * @var ViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * All page ids created using this class.
     *
     * This property is static because it's used in the cleanUp() method, which
     * is often called in the tearDown() method of a test. In the tearDown we
     * often need to create a new class object because the old one is no longer
     * available. In that case all created ids would be lost if they're not
     * stored as a static property.
     *
     * @var array
     */
    public static $createdIds;

    /**
     * @var CreateNodeModal
     */
    protected $createNodeModal;

    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var TranslatePage
     */
    protected $translatePage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Constructs a ContentCreationService object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param UserSessionService $userSessionService
     *   The user session service.
     */
    public function __construct(WebDriverTestCase $webdriver, UserSessionService $userSessionService)
    {
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->webdriver = $webdriver;
        $this->userSessionService = $userSessionService;
        $this->addContentPage = new AddPage($webdriver);
        $this->appService = new AppService($webdriver, $this->userSessionService);
        $this->createNodeModal = new CreateNodeModal($this->webdriver);
        $this->editPage = new EditPage($webdriver);
        $this->translatePage = new TranslatePage($webdriver);
        $this->administrativeNodeViewPage = new ViewPage($webdriver);

        $drupal = new DrupalService();
        $drupal->bootstrap($webdriver);
    }

    /**
     * Create a contact person.
     *
     * @param string $first_name
     *   First name.
     * @param string $last_name
     *   Last name.
     * @param array $data
     *   Optional contact person data. Array keys should match contact person
     *   fields.
     *
     * @return int
     *   The node ID of the contact person that just got created.
     */
    public function createContactPerson($first_name = null, $last_name = null, $data = array())
    {
        $first_name = !empty($first_name) ? $first_name : $this->alphanumericTestDataProvider->getValidValue();
        $last_name = !empty($last_name) ? $last_name : $this->alphanumericTestDataProvider->getValidValue();
        
        // Enable the contact person app if it is not yet enabled.
        $this->appService->enableApp(new ContactPerson);

        $node = new stdClass();
        $node->title = $first_name . ' ' . $last_name;
        $node->type = 'contact_person';
        node_object_prepare($node);
        $node = node_submit($node);

        // The contact person needs extra fields filled out because the title is
        // split in 2 parts.
        $node->field_paddle_cp_first_name['und'][0]['value'] = $first_name;
        $node->field_paddle_cp_last_name['und'][0]['value'] = $last_name;
        
        node_save($node);

        if (!empty($data)) {
            $wrapper = entity_metadata_wrapper('node', $node);

            foreach ($data as $field => $value) {
                if ($wrapper->{$field}->type() == 'addressfield') {
                    foreach ($value as $key => $content) {
                        $wrapper->{$field}->{$key}->set($content);
                    }
                } else {
                    $wrapper->{$field}->set($value);
                }
            }

            $wrapper->save();
        }

        return $this->getIdFromNodeObject($node);
    }

    /**
     * Create a contact person through the interface.
     *
     * @param string $first_name
     *   First name.
     * @param string $last_name
     *   Last name.
     * @param array $data
     *   Optional contact person data. Array keys should match contact person
     *   random filler property names.
     * @param bool $fill_all_properties
     *   Fill out all properties. By default it only fills out the specified ones.
     *
     * @return int
     *   The node ID of the contact person that just got created.
     */
    public function createContactPersonViaUI($first_name = null, $last_name = null, $data = array(), $fill_all_properties = false)
    {
        // Generate random values if none given.
        if (is_null($first_name) || is_null($last_name)) {
            $contact_person_random_filler = new ContactPersonRandomFiller();
            $contact_person_random_filler->randomize();

            if (is_null($first_name)) {
                $first_name = $contact_person_random_filler->firstName;
            }
            if (is_null($last_name)) {
                $last_name = $contact_person_random_filler->lastName;
            }
        }

        // Enable the contact person app if it is not yet enabled.
        $this->appService->enableApp(new ContactPerson);

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "Contact person".
        $this->addContentPage->links->linkContactPerson->click();

        // Fill in the modal.
        $create_contact_person_modal = new CreateContactPersonModal($this->webdriver);
        $create_contact_person_modal->waitUntilOpened();
        $this->webdriver->assertTextPresent('information modal dialog');
        $create_contact_person_modal->firstName->fill($first_name);
        $create_contact_person_modal->lastName->fill($last_name);
        $create_contact_person_modal->submit();
        $create_contact_person_modal->waitUntilClosed();

        $this->waitUntilNodeCreated();

        $this->administrativeNodeViewPage->checkArrival();
        $nid = $this->getIdFromAdministrativeNodeViewPage();

        if (!empty($data)) {
            $editContactPersonPage = new ContactPersonEditPage($this->webdriver);
            $editContactPersonPage->go($nid);

            $contactPersonRandomFiller = new ContactPersonRandomFiller();
            if ($fill_all_properties) {
                $contactPersonRandomFiller->randomize();
            }
            $contactPersonRandomFiller->firstName = $first_name;
            $contactPersonRandomFiller->lastName = $last_name;
            foreach ($data as $property => $value) {
                $contactPersonRandomFiller->{$property} = $value;
            }
            $contactPersonRandomFiller->fill($editContactPersonPage);

            $editContactPersonPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        return $nid;
    }

    /**
     * Fill contact person fields with random values.
     *
     * @param int $node_id
     *   Node id.
     * @param string $ou_title
     *   title of the organization linked to the CP.
     *
     * @return ContactPersonRandomFiller
     *   The object that holds all values that were filled in.
     */
    public function fillContactPersonWithRandomValues($node_id, $ou_title = '')
    {
        $edit_page = new ContactPersonEditPage($this->webdriver);
        $edit_page->go($node_id);

        $random_filler = new ContactPersonRandomFiller();

        $random_filler = $random_filler->randomize();
        $random_filler->setOrganizationalUnitTitle($ou_title);
        $random_filler->fill($edit_page, $this->webdriver);

        $edit_page->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        return $random_filler;
    }

    /**
     * Create an organizational unit.
     *
     * @param string $title
     *   Title for the new organizational unit.
     * @param array $data
     *   Optional organization data. Array keys should match organizational
     *   unit fields.
     * @return int
     *   The node ID of the organizational unit that just got created.
     */
    public function createOrganizationalUnit($title = null, $data = array())
    {
        // Enable the organizational unit app if it is not yet enabled.
        $this->appService->enableApp(new OrganizationalUnit);

        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = 'organizational_unit';
        node_object_prepare($node);
        $node = node_submit($node);
        node_save($node);

        if (!empty($data)) {
            $wrapper = entity_metadata_wrapper('node', $node);

            foreach ($data as $field => $value) {
                if ($wrapper->{$field}->type() == 'addressfield') {
                    foreach ($value as $key => $content) {
                        $wrapper->{$field}->{$key}->set($content);
                    }
                } else {
                    $wrapper->{$field}->set($value);
                }
            }

            $wrapper->save();
        }

        return $this->getIdFromNodeObject($node);
    }

    /**
     * Create an organizational unit through the interface.
     *
     * @param string $title
     *   Title for the new organizational unit.
     * @return int
     *   The node ID of the organizational unit that just got created.
     */
    public function createOrganizationalUnitViaUI($title = null)
    {
        // Generate random values if none given.
        if (is_null($title)) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
        }

        // Enable the organizational unit app if it is not yet enabled.
        $this->appService->enableApp(new OrganizationalUnit);

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "Organizational unit".
        $this->addContentPage->links->linkOrganizationalUnit->click();

        $this->fillInTitleOnlyCreateNodeModal($title);
        $this->waitUntilNodeCreated();

        return $this->getIdFromAdministrativeNodeViewPage();
    }

    /**
     * Creates a maps page.
     *
     * @param string $title
     *   The title for the maps page.
     *
     * @return int
     *   The node ID of the maps page just created.
     */
    public function createMapsPage($title = null)
    {
        // Enable the maps app if it is not yet enabled.
        $this->appService->enableApp(new Maps);

        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = 'paddle_maps_page';
        node_object_prepare($node);
        $node = node_submit($node);
        node_save($node);

        return $this->getIdFromNodeObject($node);
    }

    /**
     * Create a maps page through the UI.
     *
     * @param string $title
     *   The title for the maps page.
     *
     * @return int
     *   The node ID of the maps page just created.
     */
    public function createMapsPageViaUI($title = null)
    {
        // Enable the maps app if it is not yet enabled.
        $this->appService->enableApp(new Maps);

        // Generate random values if none given.
        if (is_null($title)) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
        }

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "maps page".
        $this->addContentPage->links->linkPaddleMapsPage->click();

        $this->fillInTitleOnlyCreateNodeModal($title);
        $this->waitUntilNodeCreated();

        return $this->getIdFromAdministrativeNodeViewPage();
    }

    /**
     * Create a landing page.
     *
     * @param string $layout
     *   Title of the layout.
     * @param string $title
     *   Title for the new landing page.
     *
     * @return int
     *   The node ID of the landing page that just got created.
     */
    public function createLandingPage($layout = 'paddle_2_col_3_9', $title = null)
    {
        // Go to admin/content_manager/add.
        $this->addContentPage->go();

        // Generate random values if none given.
        if (is_null($layout)) {
            $layout = 'paddle_2_col_3_9';
        }
        if (is_null($title)) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
        }

        // Choose a landing page layout.
        $this->webdriver->waitUntilTextIsPresent('Add new landing page');
        $layout_image = $this->addContentPage->getLandingPageLayoutImage($layout);
        $layout_image->click();

        $this->fillInTitleOnlyCreateNodeModal($title);
        $this->waitUntilNodeCreated();

        return $this->getIdFromAdministrativeNodeViewPage();
    }

    /**
     * Fill in the title-only node creation modal.
     *
     * This code can be shared between node types that only have a title as
     * required field.
     *
     * @param string $title
     *   Node title.
     */
    private function fillInTitleOnlyCreateNodeModal($title)
    {
        // Wait until the modal dialog is entirely loaded.
        $modal = new CreateNodeModal($this->webdriver);
        $modal->waitUntilOpened();
        $this->webdriver->assertTextPresent('information modal dialog');
        $modal->title->fill($title);

        $modal->submit();
        $modal->waitUntilClosed();
    }

    /**
     * Wait until the node creation is successfully completed.
     */
    private function waitUntilNodeCreated()
    {
        // Wait until we see confirmation that the landing page has been created.
        $this->webdriver->waitUntilElementIsPresent('//div[@id="messages"]');
        $this->webdriver->waitUntilTextIsPresent('has been created.');
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Create a simple contact page.
     *
     * @param string $title
     *   Title for the new simple contact page.
     * @return int
     *   The node ID of the simple contact page that just got created.
     */
    public function createSimpleContact($title = null)
    {
        // Enable the simple contact app if it is not yet enabled.
        $this->appService->enableApp(new SimpleContact);

        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = 'simple_contact_page';
        node_object_prepare($node);
        $node = node_submit($node);
        node_save($node);

        return $this->getIdFromNodeObject($node);
    }

    /**
     * Create a simple contact page through the interface.
     *
     * @param string $title
     *   Title for the new simple contact page.
     * @return int
     *   The node ID of the simple contact page that just got created.
     */
    public function createSimpleContactViaUI($title = null)
    {
        // Generate random values if none given.
        if (is_null($title)) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
        }

        // Enable the simple contact app if it is not yet enabled.
        $this->appService->enableApp(new SimpleContact);

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "Simple contact".
        $this->addContentPage->links->linkSimpleContactPage->click();

        $this->fillInTitleOnlyCreateNodeModal($title);
        $this->waitUntilNodeCreated();

        return $this->getIdFromAdministrativeNodeViewPage();
    }

    /**
     * Create a basic page.
     *
     * @param string $title
     *   Title for the new basic page.
     * @return int
     *   The node ID of the basic page that just got created.
     */
    public function createBasicPage($title = null)
    {
        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = 'basic_page';
        node_object_prepare($node);
        $node = node_submit($node);
        node_save($node);

        return $this->getIdFromNodeObject($node);
    }

    /**
     * Create a basic page through the UI.
     *
     * @param string $title
     *   Title for the new basic page.
     *
     * @return int
     *   The node ID of the basic page that just got created.
     */
    public function createBasicPageViaUI($title = null)
    {
        // Generate random values if none given.
        if (is_null($title)) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
        }

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "Basic page".
        $this->addContentPage->links->linkBasicPage->click();

        $this->fillInTitleOnlyCreateNodeModal($title);
        $this->waitUntilNodeCreated();

        return $this->getIdFromAdministrativeNodeViewPage();
    }

    /**
     * Create a news item page.
     *
     * @param string $title
     *   Title for the new news page.
     * @return int
     *   The node ID of the news page that just got created.
     */
    public function createNewsItem($title = null)
    {
        // Enable the contact person app if it is not yet enabled.
        $this->appService->enableApp(new News);

        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = 'news_item';
        node_object_prepare($node);
        $node = node_submit($node);
        $node->status = 0;
        node_save($node);

        return $this->getIdFromNodeObject($node);
    }

    /**
     * Create a news item through the UI.
     *
     * @param string $title
     *   Title for the new news item.
     *
     * @return int
     *   The node ID of the news item that just got created.
     */
    public function createNewsItemViaUI($title = null)
    {
        // Generate random values if none given.
        if (is_null($title)) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
        }

        // Enable the news app if it is not yet enabled.
        $this->appService->enableApp(new News);

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "News item".
        $this->addContentPage->links->linkNewsItem->click();

        $this->fillInTitleOnlyCreateNodeModal($title);
        $this->waitUntilNodeCreated();

        return $this->getIdFromAdministrativeNodeViewPage();
    }

    /**
     * Create a overview page node. Since the UI doesn't contain a link to
     * create Overview pages we will use the DrupalNodeApi class.
     *
     * @param string $title
     *   Title for the new overview page.
     *
     * @return int
     *   The node ID of the overview page that just got created.
     */
    public function createOverviewPage($title = null)
    {
        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = 'paddle_overview_page';
        node_object_prepare($node);
        $node = node_submit($node);
        node_save($node);

        return $this->getIdFromNodeObject($node);
    }

    /**
     * Create a poll page.
     *
     * @param string $title
     *   Title for the new poll page.
     * @param array $choices
     *   An array of choices to create. Every element is an array composed of:
     *   - votes: the number of votes to prefill for that choice.
     *   - text: the text to use as choice.
     *   - weight: the weight to use for the choice.
     *
     * @return int
     *   The node ID of the poll page that just got created.
     */
    public function createPollPage($title = null, $choices = array())
    {
        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = 'poll';
        $node->runtime = 0;
        $node->choice = array();
        $node->field_paddle_poll_question[LANGUAGE_NONE][0]['value'] =
            $this->alphanumericTestDataProvider->getValidValue();

        node_object_prepare($node);
        $node = node_submit($node);
        node_save($node);

        // Always provide two choices at least.
        $choices = array_pad($choices, 2, array(
            'votes' => 0,
        ));

        // Prepare the insert query.
        $query = db_insert('poll_choice')
            ->fields(array('chvotes', 'chtext', 'weight', 'nid'));

        // Prepare the data to be inserted.
        foreach ($choices as $index => $choice) {
            $values = array(
                'chvotes' => isset($choice['votes']) ? $choice['votes'] : 0,
                'chtext' => isset($choice['text'])
                    ? $choice['text']
                    : $this->alphanumericTestDataProvider->getValidValue(),
                'weight' => isset($choice['weight']) ? $choice['weight'] : $index,
                'nid' => $node->nid,
            );

            $query->values($values);
        }

        $query->execute();

        // Return the node ID.
        return $this->getIdFromNodeObject($node);
    }

    /**
     * Create a poll page through the UI, with 1 question and 2 choices.
     *
     * @param string $title
     *   Title for the new poll page.
     *
     * @return int
     *   The node ID of the poll page that just got created.
     */
    public function createPollPageViaUI($title = null)
    {
        // Generate random values if none given.
        if (is_null($title)) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
        }

        // Enable the poll app if it is not yet enabled.
        $this->appService->enableApp(new Poll);

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "Poll".
        $this->addContentPage->links->linkPollPage->click();

        $this->fillInTitleOnlyCreateNodeModal($title);
        $this->waitUntilNodeCreated();

        $nid = $this->getIdFromAdministrativeNodeViewPage();

        // Fill in the question and choices as they are mandatory.
        $poll_edit_page = new PollPage($this->webdriver);
        $poll_edit_page->go($nid);
        $poll_edit_page->pollForm->question->fill($this->alphanumericTestDataProvider->getValidValue());
        $poll_edit_page->pollForm->choiceTable->rows[0]
            ->text->fill($this->alphanumericTestDataProvider->getValidValue());
        $poll_edit_page->pollForm->choiceTable->rows[1]
            ->text->fill($this->alphanumericTestDataProvider->getValidValue());
        $poll_edit_page->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        return $nid;
    }

    /**
     * Create a quiz page node.
     *
     * @param string $title
     *   Title for the new quiz page.
     *
     * @return int
     *   The node ID of the quiz page that just got created.
     *
     * @todo address the fact that the quiz entity reference field is mandatory.
     */
    public function createQuizPage($title = null)
    {
        // Enable the contact person app if it is not yet enabled.
        $this->appService->enableApp(new Quiz);

        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = 'quiz_page';
        node_object_prepare($node);
        $node = node_submit($node);
        node_save($node);

        return $this->getIdFromNodeObject($node);
    }

    /**
     * Create a quiz page.
     *
     * @param string $title
     *   Title for the quiz page.
     * @param int $qid
     *   Id of the quiz to reference to.
     *
     * @return int
     *   Node id of the quiz page that was created.
     */
    public function createQuizPageViaUI($title = null, $qid = null)
    {
        // Generate random title if none given.
        if (is_null($title)) {
            $title = $this->alphanumericTestDataProvider->getValidValue(8);
        }
        // Create a quiz if no id is given.
        if (is_null($qid)) {
            $quiz = QuizService::create(array('status' => 1));
            $qid = $quiz->qid;
        }

        // Enable the quiz app if not enabled yet.
        $this->appService->enableApp(new Quiz);

        // Go to admin/content_manager/add.
        $this->addContentPage->go();

        // Click on "quiz page".
        $this->addContentPage->links->linkQuizPage->click();

        // Fill in the necessary data and click submit.
        $modal = new CreateQuizPageModal($this->webdriver);
        $modal->waitUntilOpened();
        $modal->title->fill($title);
        $modal->quizReference->select($qid);
        $modal->submit();
        $modal->waitUntilClosed();

        // Wait until the node has been created.
        $this->waitUntilNodeCreated();

        return $this->getIdFromAdministrativeNodeViewPage();
    }

    /**
     * Create a newsletter node using node_save().
     *
     * @param string $title
     *   Title for the new newsletter.
     * @param array $campaign_options
     *   An array with the campaign required options:
     *     - list_id: the id of the list that is going to receive the campaign.
     *     - from_name: the name to show on the campaign mail.
     *     - from_email: the mail to use as reply-to on the campaign mail.
     *
     * @return int
     *   The node ID of the organizational unit that just got created.
     */
    public function createNewsletter($title = null, $campaign_options = array())
    {
        // Enable the MailChimp app if it is not yet enabled.
        $this->appService->enableApp(new MailChimp);

        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = 'newsletter';

        $api_key = getenv('mailchimp_api_key');
        $mailchimp_service = new MailChimpService($this->webdriver, $api_key);

        $lists = $mailchimp_service->getMailChimpLists();
        $list_ids = array_keys($lists);

        $default_campaign_options = array(
            'list_id' => $list_ids[0],
            'from_name' => 'Joske Vermeulen',
            'from_email' => 'joske.vermeulen@kanooh.be',
        );

        $campaign_options = array_merge($default_campaign_options, $campaign_options);

        // Create a new campaign from the values.
        $campaign_options['subject'] = $node->title;
        $campaign = $mailchimp_service->createCampaign(null, $campaign_options);
        $node->field_paddle_mailchimp_cid[LANGUAGE_NONE][0]['value'] = $campaign['id'];
        node_object_prepare($node);
        $node = node_submit($node);
        node_save($node);

        return $this->getIdFromNodeObject($node);
    }

    /**
     * Create a newsletter node through the interface.
     *
     * @param string $title
     *   Title for the new newsletter.
     * @param string $list_name
     *   The list to send the newsletter to. Can be one "listOne" or "listTwo".
     * @param string $from_name
     *   The name to show on the campaign mail.
     * @param string $from_email
     *   The mail to use as reply-to on the campaign mail.
     * @return int
     *   The node ID of the organizational unit that just got created.
     */
    public function createNewsletterViaUI($title = null, $list_name = 'listOne', $from_name = null, $from_email = null)
    {
        // Enable the MailChimp app if it is not yet enabled.
        $this->appService->enableApp(new MailChimp);

        // Generate random values if none given.
        $title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $from_name = !empty($from_name) ? $from_name : $this->alphanumericTestDataProvider->getValidValue();
        // The email domain must be a valid one, so we use kanooh.be as it's
        // the domain we used for our registration.
        $from_email = !empty($from_email) ? $from_email : 'developers@kanooh.be';

        // Now create the newsletter node.
        $this->addContentPage->go();
        $this->addContentPage->links->linkNewsletter->click();

        // Fill the required fields.
        $newsletter_modal = new CreateNewsletterModal($this->webdriver);
        $newsletter_modal->waitUntilOpened();
        $this->webdriver->assertTextPresent('information modal dialog');
        $newsletter_modal->title->fill($title);
        $newsletter_modal->{$list_name}->select();
        $newsletter_modal->fromName->fill($from_name);
        $newsletter_modal->fromEmail->fill($from_email);
        $newsletter_modal->submit();
        $newsletter_modal->waitUntilClosed();

        $this->waitUntilNodeCreated();

        return $this->getIdFromAdministrativeNodeViewPage();
    }

    /**
     * Edits a node.
     *
     * @param int $nid
     *   The ID of the node.
     * @param array $values
     *   The values of the node fields to change. Should be an associative array
     *   where the keys are the field name and the values - the value to edit.
     *   Supported fields: body, uid, status.
     */
    public function editNode($nid, $values)
    {
        $node = node_load($nid);

        if ($node) {
            // Set the body if any.
            if (!empty($values['body'])) {
                $node->body[LANGUAGE_NONE][0]['value'] = $values['body'];
                $node->body[LANGUAGE_NONE][0]['format'] = 'full_html';
            }

            // Set the author if any.
            if (!empty($values['uid'])) {
                $node->uid = $values['uid'];
            }

            // Set the publication status if any.
            if (!empty($values['status'])) {
                $node->status = $values['status'];
            }
            node_object_prepare($node);
            $node = node_submit($node);
            node_save($node);
        }
    }


    /**
     * Create a formbuilder page.
     *
     * @param string $title
     *   Title for the new formbuilder page.
     * @return int
     *   The node ID of the formbuilder page that just got created.
     */
    public function createFormbuilderPage($title = null)
    {
        // Enable the Formbuilder app if it is not yet enabled.
        $this->appService->enableApp(new Formbuilder);

        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = 'paddle_formbuilder_page';
        node_object_prepare($node);
        $node = node_submit($node);
        node_save($node);

        return $this->getIdFromNodeObject($node);
    }

    /**
     * Create a formbuilder page through the UI.
     *
     * @param string $title
     *   Title for the formbuilder page.
     *
     * @return int
     *   The node ID of the formbuilder page that just got created.
     */
    public function createFormbuilderPageViaUI($title = null)
    {
        // Generate random values if none given.
        if (is_null($title)) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
        }

        // Enable the Formbuilder paddlet if it is not yet enabled.
        $this->appService->enableApp(new Formbuilder);

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "Formbuilder page".
        $this->addContentPage->links->linkFormbuilderPage->click();

        $this->fillInTitleOnlyCreateNodeModal($title);
        $this->waitUntilNodeCreated();

        return $this->getIdFromAdministrativeNodeViewPage();
    }

    /**
     * Creates a calendar item.
     *
     * @param string $title
     *   The title for the calendar item.
     * @param int $start_date
     *   The start date in Unix timestamp. Empty for current time.
     * @param int $end_date
     *   The end date in Unix timestamp. Empty for no end date.
     *
     * @return int
     *   The node ID of the calendar item just created.
     */
    public function createCalendarItem($title = null, $start_date = null, $end_date = null)
    {
        // Enable the Calendar app if it is not yet enabled.
        $this->appService->enableApp(new Calendar);

        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = 'calendar_item';
        node_object_prepare($node);

        // If the start date is empty, use current time.
        $start_date = $start_date ?: time();

        // Get settings from field and instance. Needed to properly format
        // the date itself.
        $field = field_info_field('field_paddle_calendar_date');
        $instance = field_info_instance('node', 'field_paddle_calendar_date', 'calendar_item');
        $timezone = date_get_timezone($field['settings']['tz_handling']);
        $increment = $instance['widget']['settings']['increment'];

        // Generate a timestamp that follows the correct granularity.
        $date = new \DateObject($start_date, $timezone);
        $date->limitGranularity($field['settings']['granularity']);
        date_increment_round($date, $increment);
        $start_date = $date->getTimestamp();

        // Handle the end date, if available.
        if (!empty($end_date)) {
            $date = new \DateObject($end_date, $timezone);
            $date->limitGranularity($field['settings']['granularity']);
            date_increment_round($date, $increment);
            $end_date = $date->format('U');
        }

        // Use the property setters provided by the date module, as the
        // conversion in the correct format is done through validation
        // callbacks that we cannot access now.
        $wrapper = entity_metadata_wrapper('node', $node);
        $wrapper->field_paddle_calendar_date->value->set($start_date);
        if (!empty($end_date)) {
            $wrapper->field_paddle_calendar_date->value2->set($end_date);
        }

        $node = node_submit($node);
        node_save($node);

        return $this->getIdFromNodeObject($node);
    }

    /**
     * Create a calendar item through the UI.
     *
     * @param string $title
     *   The title for the calendar item.
     * @param int $start_date
     *   The start date in Unix timestamp. Empty for current time.
     * @param int $end_date
     *   The end date in Unix timestamp. Empty for no end date.
     *
     * @return int
     *   The node ID of the calendar item just created.
     */
    public function createCalendarItemViaUI($title = null, $start_date = null, $end_date = null)
    {
        // Enable the Calendar app if it is not yet enabled.
        $this->appService->enableApp(new Calendar);

        // Generate random values if none given.
        $title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $start_date = !empty($start_date) ? $start_date : time();
        $end_date = !empty($end_date) ? $end_date : time();

        // Now create the newsletter node.
        $this->addContentPage->go();
        $this->addContentPage->links->linkCalendarItem->click();

        // Fill the required fields.
        $modal = new CreateCalendarItemModal($this->webdriver);
        $modal->waitUntilOpened();
        $this->webdriver->assertTextPresent('information modal dialog');
        $modal->title->fill($title);
        // @todo Fill in date fields.
        $modal->submit();
        $modal->waitUntilClosed();

        $this->waitUntilNodeCreated();

        return $this->getIdFromAdministrativeNodeViewPage();
    }

    /**
     * Creates a advanced search page.
     *
     * @param string $title
     *   The title for the advanced search page.
     *
     * @return int
     *   The node ID of the advanced search page just created.
     */
    public function createAdvancedSearchPage($title = null)
    {
        // Enable the advanced search app if it is not yet enabled.
        $this->appService->enableApp(new AdvancedSearch);

        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = 'paddle_advanced_search_page';
        node_object_prepare($node);
        $node = node_submit($node);
        node_save($node);

        return $this->getIdFromNodeObject($node);
    }

    /**
     * Create a advanced search page through the UI.
     *
     * @param string $title
     *   The title for the advanced search page.
     *
     * @return int
     *   The node ID of the advanced search pagem just created.
     */
    public function createAdvancedSearchPageViaUI($title = null)
    {
        // Enable the advanced search app if it is not yet enabled.
        $this->appService->enableApp(new AdvancedSearch);

        // Generate random values if none given.
        if (is_null($title)) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
        }

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "advanced search page page".
        $this->addContentPage->links->linkPaddleAdvancedSearchPage->click();

        $this->fillInTitleOnlyCreateNodeModal($title);
        $this->waitUntilNodeCreated();

        return $this->getIdFromAdministrativeNodeViewPage();
    }

    /**
     * Creates a glossary definition.
     *
     * @param string $definition
     *   The definition to create an entry for.
     * @param string $description
     *   The description for the definition.
     */
    public function createGlossaryDefinition($definition, $description)
    {
        $entity = entity_create('paddle_glossary_definition', array('definition' => $definition));
        $wrapper = entity_metadata_wrapper('paddle_glossary_definition', $entity, array('bundle' => 'paddle_glossary_definition'));
        $wrapper->field_glossary_description->value->set($description);
        $wrapper->save();
    }

    /**
     * Creates a pane collection.
     *
     * @param string $title
     *   The title to create an entry for.
     */
    public function createPaneCollection($title)
    {
        $entity = entity_create('paddle_pane_collection', array('title' => $title));
        entity_save('paddle_pane_collection', $entity);
    }

    /**
     * Create a product.
     *
     * @param string $title
     *   Title for the new product.
     *
     * @return int
     *   The node ID of the product that just got created.
     */
    public function createProductPage($title = null)
    {
        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = 'paddle_product';
        node_object_prepare($node);
        $node = node_submit($node);
        node_save($node);

        return $this->getIdFromNodeObject($node);
    }

    /**
     * Create a product through the UI.
     *
     * @param string $title
     *   Title for the new product.
     *
     * @return int
     *   The node ID of the product that just got created.
     */
    public function createProductPageViaUI($title = null)
    {
        // Generate random values if none given.
        if (is_null($title)) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
        }

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "Product".
        $this->addContentPage->links->linkProductPage->click();

        $this->fillInTitleOnlyCreateNodeModal($title);
        $this->waitUntilNodeCreated();

        return $this->getIdFromAdministrativeNodeViewPage();
    }

    /**
     * Create a offer.
     *
     * @param string $title
     *   Title for the new offer.
     *
     * @return int
     *   The node ID of the offer that just got created.
     */
    public function createOfferPage($title = null)
    {
        // Enable the Holiday Participation app if it has not been enabled yet.
        $this->appService->enableApp(new HolidayParticipation);

        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = 'offer';
        node_object_prepare($node);
        $node = node_submit($node);
        node_save($node);

        return $this->getIdFromNodeObject($node);
    }

    /**
     * Create a offer through the UI.
     *
     * @param string $title
     *   Title for the new offer.
     *
     * @return int
     *   The node ID of the offer that just got created.
     */
    public function createOfferPageViaUI($title = null)
    {
        // Enable the Holiday Participation app if it has not been enabled yet.
        $this->appService->enableApp(new HolidayParticipation);

        // Generate random values if none given.
        if (is_null($title)) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
        }

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "Offer".
        $this->addContentPage->links->linkOfferPage->click();

        $this->fillInTitleOnlyCreateNodeModal($title);
        $this->waitUntilNodeCreated();

        return $this->getIdFromAdministrativeNodeViewPage();
    }

    /**
     * Create a publication page.
     *
     * @param string $title
     *   Title for the new publication page.
     * @return int
     *   The node ID of the publication page that just got created.
     */
    public function createPublicationPage($title = null)
    {
        // Enable the publication app if it is not yet enabled.
        $this->appService->enableApp(new Publication);

        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = 'paddle_publication';
        node_object_prepare($node);
        $node = node_submit($node);
        node_save($node);

        return $this->getIdFromNodeObject($node);
    }

    /**
     * Create a publication page through the UI.
     *
     * @param string $title
     *   Title for the new publication page.
     *
     * @return int
     *   The node ID of the publication page that just got created.
     */
    public function createPublicationPageViaUI($title = null)
    {
        // Generate random values if none given.
        if (is_null($title)) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
        }

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "Basic page".
        $this->addContentPage->links->linkPaddlePublication->click();

        $this->fillInTitleOnlyCreateNodeModal($title);
        $this->waitUntilNodeCreated();

        return $this->getIdFromAdministrativeNodeViewPage();
    }

    /**
     * Create a CIRRO page.
     *
     * @param string $title
     *   Title for the new CIRRO page.
     * @return int
     *   The node ID of the CIRRO page that just got created.
     */
    public function createCirroPage($title = null)
    {
        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = 'paddle_cirro_page';
        node_object_prepare($node);
        $node = node_submit($node);
        node_save($node);

        // Return the node ID.
        return $node->nid;
    }

    /**
     * Create an Ebl page.
     *
     * @param string $title
     *   Title for the new Ebl page.
     * @return int
     *   The node ID of the Ebl page that just got created.
     */
    public function createEblPage($title = null)
    {
        return $this->createNode('paddle_ebl_page', $title);
    }

    /**
     * Create an company page.
     *
     * @param string $title
     *   Title for the new company page.
     * @return int
     *   The node ID of the company page that just got created.
     */
    public function createCompanyPage($title = null)
    {
        return $this->createNode('company_page', $title);
    }

    /**
     * Create a node.
     *
     * @param string $title
     *   Title for the new node.
     * @param string $node_type
     *   Content type of the new node.
     * @return int
     *   The node ID of the node that just got created.
     *
     * @TODO: Refactor all other content types to use this method.
     */
    protected function createNode($node_type, $title = null)
    {
        $node = new stdClass();
        $node->title = !empty($title) ? $title : $this->alphanumericTestDataProvider->getValidValue();
        $node->type = $node_type;
        node_object_prepare($node);
        $node = node_submit($node);
        node_save($node);

        // Return the node ID.
        return $node->nid;
    }

    /**
     * Create a CIRRO page through the UI.
     *
     * @param string $title
     *   Title for the new CIRRO page.
     *
     * @return int
     *   The node ID of the CIRRO page that just got created.
     */
    public function createCirroPageViaUI($title = null)
    {
        // Generate random values if none given.
        if (is_null($title)) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
        }

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "Paddle Cirro page".
        $this->addContentPage->links->linkPaddleCirroPage->click();

        $this->fillInTitleOnlyCreateNodeModal($title);
        $this->waitUntilNodeCreated();

        // Return the node ID.
        return $this->administrativeNodeViewPage->getNodeIDFromUrl();
    }

    /**
     * Create a EBL page through the UI.
     *
     * @param string $title
     *   Title for the new EBL page.
     *
     * @return int
     *   The node ID of the EBL page that just got created.
     */
    public function createEblPageViaUI($title = null)
    {
        // Generate random values if none given.
        if (is_null($title)) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
        }

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "Paddle EBL page".
        $this->addContentPage->links->linkPaddleEblPage->click();

        $this->fillInTitleOnlyCreateNodeModal($title);
        $this->waitUntilNodeCreated();

        // Return the node ID.
        return $this->administrativeNodeViewPage->getNodeIDFromUrl();
    }

    /**
     * Create a company page through the UI.
     *
     * @param string $title
     *   Title for the new company page.
     *
     * @return int
     *   The node ID of the company page that just got created.
     */
    public function createCompanyPageViaUI($title = null)
    {
        // Generate random values if none given.
        if (is_null($title)) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
        }

        // Go to admin/content_manager/add.
        $this->addContentPage->go();
        // Click on "Paddle EBL page".
        $this->addContentPage->links->linkCompanyPage->click();

        $this->fillInTitleOnlyCreateNodeModal($title);
        $this->waitUntilNodeCreated();

        // Return the node ID.
        return $this->administrativeNodeViewPage->getNodeIDFromUrl();
    }

    /**
    * Creates an opening hours set.
    *
    * @param string $title
    *   The title of the opening hours set.
    */
    public function createOpeningHoursSet($title)
    {
        $entity = entity_create('opening_hours_set', array('title' => $title));
        entity_save('opening_hours_set', $entity);
    }

    /**
     * Change the language of the node.
     *
     * @param int $nid
     *   The node id of the node to change the language for.
     * @param $new_lang
     *   The new language for the node.
     */
    public function changeNodeLanguage($nid, $new_lang)
    {
        $node = node_load($nid);

        // Get the language of each field.
        $field_langs = field_language('node', $node);
        // Go through ALL field of this node.
        foreach ($field_langs as $field => $lang) {
            // If the field is in the wrong language get all field values.
            if ($lang != $new_lang) {
                $items = field_get_items('node', $node, $field, $lang);

                // Give the field the new language and remove the old language.
                if (!empty($items)) {
                    $node->{$field}[$new_lang] = $items;
                    unset($node->{$field}[$lang]);
                }
            }
        }
        // Set the new node language.
        $node->language = $new_lang;
        node_save($node);
    }

    /**
     * Schedule a node for publication.
     *
     * @param int $nid
     *   The NID of the node.
     */
    public function scheduleNodeForPublication($nid)
    {
        $this->editPage->go($nid);

        // We need to open the scheduler options first.
        $this->editPage->toggleSchedulerOptions();

        $publish_on_ts = strtotime('+1 day');
        $this->editPage->publishOnDate->clear();
        $this->editPage->publishOnDate->value(date('d/m/Y', $publish_on_ts));
        $this->editPage->publishOnTime->clear();
        $this->editPage->publishOnTime->value(date('H:i:s', $publish_on_ts));
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonSchedule->click();
    }

    /**
     * Schedule a node for depublication.
     *
     * @param int $nid
     *   The NID of the node.
     */
    public function scheduleNodeForDepublication($nid)
    {
        $this->editPage->go($nid);

        // We need to open the scheduler options first.
        $this->editPage->toggleSchedulerOptions();

        $unpublish_on_ts = strtotime('+2 days');
        $this->editPage->unpublishOnDate->clear();
        $this->editPage->unpublishOnDate->value(date('d/m/Y', $unpublish_on_ts));
        $this->editPage->unpublishOnTime->clear();
        $this->editPage->unpublishOnTime->value(date('H:i:s', $unpublish_on_ts));
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
    }

    /**
     * Moderate a node to a given moderation state.
     *
     * @param int $nid
     *   The node id of the node to moderate.
     * @param string $state
     *   The state to moderate the node to.
     */
    public function moderateNode($nid, $state)
    {
        $node = node_load($nid);
        workbench_moderation_moderate($node, $state);

        // Only finishing PHP requests invoke the shutdown functions.
        // @see http://php.net/manual/en/function.register-shutdown-function.php
        // This test code does not finish as long as the test does not finish.
        // Act as if the Drupal PHP request finishes here.
        // Needed because part of workbench_moderation_moderate() relies on it.
        _drupal_shutdown_function();
    }

    /**
     * Enables the rating functionality of the requested node.
     */
    public function enableRating($nid)
    {
        $this->editPage->go($nid);
        $this->editPage->enableRatingCheckbox->check();
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Translates a node in a given language.
     *
     * @param $nid
     *   The Identifier of the node.
     * @param $language
     *   The language which we want to translate the node in.
     *
     * @return int
     *   The node ID of the translated node.
     */
    public function translateNode($nid, $language)
    {
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $translated_nid = $this->createBasicPage($title);
        $this->changeNodeLanguage($translated_nid, $language);


        $this->translatePage->go($nid);
        $this->translatePage->selectExistingTranslation($language, $title);
        $this->translatePage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        return $translated_nid;
    }

    /**
     * Deletes all pages created via this class, or a specified list of ids.
     *
     * When a list of ids is specified, the pages created by this class will
     * not be deleted unless the method is called again without a list of ids.
     *
     * This method is static because it is often called in the tearDown() method
     * of a test, where we would otherwise need to create a new object of this
     * class because the old one is often no longer available.
     *
     * @param WebDriverTestCase $webdriver
     *   Webdriver test case to use to interact with the browser.
     * @param array|bool $ids
     *   Optional list of pages to delete instead of the ones created by this
     *   class.
     *
     * @see \Kanooh\Paddle\Utilities\AssetCreationService::cleanUp()
     */
    public static function cleanUp(WebDriverTestCase $webdriver, $ids = false)
    {
        // If no list of ids is specified, use the list of atoms that were
        // created by this class and reset the list so we don't try to delete
        // them twice if this method is called again later.
        if ($ids === false) {
            $ids = self::getCreatedIds();
            self::resetCreatedIds();
        }

        if (count($ids)) {
            $clean_up_service = new CleanUpService($webdriver);
            $clean_up_service->deleteEntities('node', false, $ids);
        }
    }

    /**
     * Returns a list of atom ids created by this class.
     *
     * @return array
     */
    public static function getCreatedIds()
    {
        return (is_array(self::$createdIds)) ? self::$createdIds : array();
    }

    /**
     * Resets the list of atom ids created by this class.
     */
    protected static function resetCreatedIds()
    {
        self::$createdIds = array();
    }

    /**
     * Returns the id of a newly created page, and stores it for later cleanup.
     *
     * @param StdClass $node
     *   Node object.
     * @return int
     *   Node id.
     */
    public function getIdFromNodeObject(StdClass $node)
    {
        $id = $node->nid;
        $this->rememberId($id);
        return $id;
    }

    /**
     * Returns the id of a newly created page, and stores it for later cleanup.
     *
     * @return int
     *   Node id.
     */
    public function getIdFromAdministrativeNodeViewPage()
    {
        $id = $this->administrativeNodeViewPage->getNodeIDFromUrl();
        $this->rememberId($id);
        return $id;
    }

    /**
     * Returns the id of a newly created page, and stores it for later cleanup.
     *
     * @param int $id
     *   Node id.
     */
    public function rememberId($id)
    {
        // Store the page id so we can remove the page later, if needed.
        if (!in_array($id, $this->getCreatedIds())) {
            self::$createdIds[] = $id;
        }
    }
}
