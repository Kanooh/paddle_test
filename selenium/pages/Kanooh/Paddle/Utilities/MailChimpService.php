<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\MailChimpService.
 */

namespace Kanooh\Paddle\Utilities;

use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMailChimp\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMailChimp\SignupFormPage\SignupFormPage;
use Kanooh\Paddle\Pages\Element\MailChimp\SignupFormDeleteModal;
use Kanooh\WebDriver\WebDriverTestCase;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;

class MailChimpService
{
    /**
     * @var SignupFormPage
     */
    protected $addSignupFormPage;

    /**
     * @var AlphanumericTestDataProvider;
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Constructs a MailChimpService object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $api_key
     *   The MailChimp API key.
     */
    public function __construct(WebDriverTestCase $webdriver, $api_key = '')
    {
        $this->addSignupFormPage = new SignupFormPage($webdriver, 'admin/config/services/mailchimp/signup/add');
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($webdriver);
        $this->webdriver = $webdriver;

        $drupal = new DrupalService();
        $drupal->bootstrap($webdriver);

        if ($api_key) {
            $this->setMailChimpApiKey($api_key);
        }
    }

    /**
     * Creates a new Signup Form entity using the UI.
     *
     * @param  string $title
     *   The title of the Signup form entity.
     * @param  array $lists
     *   Array with the human-readable names of the lists for this Signup form.
     *
     * @return string
     *   The id of the created Signup Form entity.
     */
    public function createSignupFormUI($title = '', $lists = array())
    {
        // Set the variables if they were not passed.
        $title = !$title ? $this->alphanumericTestDataProvider->getValidValue() : $title;
        $lists = !count($lists) ? $this->getMailChimpLists() : $lists;

        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonCreateSignupForm->click();
        $this->addSignupFormPage->checkArrival();
        $this->addSignupFormPage->signupFormForm->title->fill($title);
        foreach ($lists as $list_name) {
            $this->addSignupFormPage->signupFormForm->selectListByName($list_name);
        }
        $this->addSignupFormPage->contextualToolbar->buttonSave->click();

        $this->configurePage->checkArrival();
        return $this->configurePage->signupFormsTable->getRowByTitle($title)->signupFormId;
    }

    /**
     * Creates a new Signup Form entity using the UI.
     *
     * @param  string $title
     *   The title of the Signup form entity to delete.
     * @param  int $signup_id
     *   The id of the Signup form entity to delete.
     *
     * @return bool
     *   True if the signup form was deleted, false otherwise.
     */
    public function deleteSignupFormUI($title = '', $signup_id = 0)
    {
        if ($title) {
            $row = $this->configurePage->signupFormsTable->getRowByTitle($title);
        } elseif ($signup_id) {
            $row = $this->configurePage->signupFormsTable->getRowById($signup_id);
        }

        if (isset($row)) {
            $row->linkDelete->click();
            $delete_modal = new SignupFormDeleteModal($this->webdriver);
            $delete_modal->waitUntilOpened();
            $delete_modal->form->deleteButton->click();
            $delete_modal->waitUntilClosed();
            $this->configurePage->checkArrival();
            if ($title) {
                $this->webdriver->waitUntilTextIsPresent('MailChimp Signup Form ' . $title . ' has been deleted.');
            }

            return true;
        }

        return false;
    }

    /**
     * Loads and returns a MailChimp Signup form entity.
     *
     * @param int $entity_id
     *   The id of the required entity.
     *
     * @return \MailchimpSignup
     *   The loaded entity.
     */
    public function loadEntity($entity_id)
    {
        return mailchimp_signup_load($entity_id);
    }

    /**
     * Retrieves the MailChimp lists.
     *
     * @return array
     *   Array containing the MailChimp lists with the names as values,
     *   keyed by the IDs.
     */
    public function getMailChimpLists()
    {
        $list_names = array();
        foreach (mailchimp_get_lists() as $id => $list) {
            $list_names[$id] = $list['name'];
        }

        return $list_names;
    }

    /**
     * Retrieves a MailChimp member info.
     *
     * @param string $list_id
     *   The id of the list in which to check for the member.
     * @param string $email
     *   The email of the member for which to check.
     *
     * @return array | null
     *   Array containing the merge vars (fields) or null if not found.
     */
    public function getListMember($list_id, $email)
    {
        $members = $this->getListMembers($list_id);
        return !empty($members[$email]) ? $members[$email] : null;
    }

    /**
     * Retrieves all the members of a MailChimp lists.
     *
     * @param string $list_id
     *   The id of the list in which to get the members.
     *
     * @return array
     *   Array keyed by the emails and having for value the merge vars (fields)
     *   for the member.
     */
    public function getListMembers($list_id)
    {
        $members = array();

        // Get the info from MailChimp.
        $result = mailchimp_get_members($list_id);
        if ($result['total'] > 0) {
            foreach ($result['data'] as $member) {
                $members[$member['email']] = $member['merges'];
            }
        }

        return $members;
    }

    /**
     * Sets the MailChimp API key.
     */
    public function setMailChimpApiKey($api_key)
    {
        variable_set('mailchimp_api_key', $api_key);
    }

    /**
     * Delete the MailChimp API key.
     */
    public function deleteMailChimpApiKey()
    {
        variable_del('mailchimp_api_key');
    }

    /**
     * Create a new campaign in MailChimp.
     *
     * @param null|string $type
     *   The campaign type to create (regular, plaintext, absplit, rss, auto).
     *   Defaults to regular.
     * @param array $options
     *   The options for the new campaign. The following are required:
     *     - subject: the campaign subject line.
     *     - list_id: the id of the list that is going to receive the campaign.
     *     - from_name: the name to show on the campaign mail.
     *     - from_email: the mail to use as reply-to on the campaign mail.
     * @param array $content
     *   The actual content of the campaign - usually HTML.
     *
     * @return array
     *   The result of calling the create method in MailChimp API.
     */
    public function createCampaign($type, $options, $content = array())
    {
        $type = empty($type) ? 'regular' : $type;
        $content += array('html' => '');
        $mc = mailchimp_get_api_object();
        return $mc->campaigns->create($type, $options, $content);
    }

    /**
     * Remove a campaign from MailChimp.
     *
     * @param int $campaign_id
     * @param bool $throw_exceptions
     *
     * @throws \Exception
     *   Thrown if not ignored by this method.
     *
     * @return array
     *   The result of calling the create method in MailChimp API.
     */
    public function deleteCampaign($campaign_id, $throw_exceptions = true)
    {
        $mc = mailchimp_get_api_object();
        try {
            return $mc->campaigns->delete($campaign_id);
        } catch (\Exception $e) {
            if ($throw_exceptions) {
                throw $e;
            }
        }
    }

    /**
     * Clean up campaigns that have been sent longer than 1 week ago.
     *
     * Because MailChimp does not allow to delete them for 7 days.
     * We assume no test needs such campaign after that period.
     * By default this method will try to delete the 25 oldest campaigns.
     *
     * @param bool $throw_exceptions
     * @param int $limit
     * @param string $sort_dir
     */
    public function cleanupSentCampaigns($throw_exceptions = true, $limit = 25, $sort_dir = 'ASC')
    {
        $mc = mailchimp_get_api_object();
        $date_time = new \DateTime('-1 week');
        $filters = array(
            'sendtime_end' => $date_time->format('Y-m-d H:i:s'),
            'status' => 'sent',
        );
        $campaigns = $mc->campaigns->getList($filters, 0, $limit, 'create_time', $sort_dir);
        foreach ($campaigns['data'] as $campaign) {
            $this->deleteCampaign($campaign['id'], $throw_exceptions);
        }
    }

    /**
     * @param string $campaign_id
     *   The id of the campaign to retrieve.
     *
     * @param bool $reset
     *   True to bypass cache.
     *
     * @return mixed
     *   False on failure, the campaign info otherwise.
     */
    public function getCampaignInfo($campaign_id, $reset = true)
    {
        return mailchimp_get_campaign_data($campaign_id, $reset);
    }

    /**
     * @param object $node
     *
     * @return int|bool
     *   False on failure, the campaign id otherwise.
     */
    public function getCampaignIdFromNewsletter($node)
    {
        if ($node->type == 'newsletter') {
            return $node->field_paddle_mailchimp_cid[LANGUAGE_NONE]['0']['value'];
        } else {
            return false;
        }
    }
}
