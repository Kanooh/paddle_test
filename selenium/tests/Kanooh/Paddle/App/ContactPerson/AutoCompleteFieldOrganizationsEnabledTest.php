<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\AutoCompleteFieldOrganizationsEnabledTest.
 */

namespace Kanooh\Paddle\App\ContactPerson;

use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Core\Pane\Base\AutoCompleteFieldTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ContactPersonPanelsContentType;
use Kanooh\Paddle\Pages\Node\EditPage\ContactPerson\ContactPersonEditPage;
use Kanooh\Paddle\Pages\Node\EditPage\ContactPersonRandomFiller;
use Kanooh\Paddle\Utilities\AppService;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class AutoCompleteFieldOrganizationsEnabledTest extends AutoCompleteFieldTestBase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new ContactPerson);
        $this->appService->enableApp(new OrganizationalUnit());
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        // Create with required fields only.
        $nid = $this->contentCreationService->createContactPerson($title);

        // Fill the other fields.
        $edit_page = new ContactPersonEditPage($this);
        $edit_page->go($nid);

        $random_filler = new ContactPersonRandomFiller();
        // Randomize all values.
        $random_filler->randomize();
        // But, keep the original title, if it was specified.
        if (!is_null($title)) {
            $random_filler->firstName = $title;
        }

        // Fill and save.
        $random_filler->fill($edit_page);
        $edit_page->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Get the saved node, as an object.
        $node_object = node_load($nid);
        // Copy parts of it to our internal test data array.
        foreach ($this->getSearchableNodeFields() as $searchable_node_field) {
            if (isset($node_object->$searchable_node_field)) {
                $items = field_get_items('node', $node_object, $searchable_node_field);
                if (isset($items[0]['value'])) {
                    $this->nodeData[$nid][$searchable_node_field] = $items[0]['value'];
                } else {
                    $this->nodeData[$nid][$searchable_node_field] = $node_object->$searchable_node_field;
                }
            } else {
                $this->nodeData[$nid][$searchable_node_field] = '';
            }
        }

        return $nid;
    }

    /**
     * {@inheritdoc}
     */
    public function getPanelsContentType()
    {
        return new ContactPersonPanelsContentType($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getAutoCompleteFormField()
    {
        return $this->getPanelsContentType()->getForm()->contactPersonAutocompleteField;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpectedSuggestion($nid)
    {
        return $this->nodeData[$nid]['title'] . ' (node/' . $this->nodeData[$nid]['nid'] . ')';
    }

    /**
     * {@inheritdoc}
     */
    public function getPartialExpectedSuggestions($nid)
    {
        return array(
            // Part of the title.
            substr($this->nodeData[$nid]['title'], 0, 10),
            // The title.
            $this->nodeData[$nid]['title'],
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchableNodeFields()
    {
        return array(
            'nid',
            'title',
        );
    }
}
