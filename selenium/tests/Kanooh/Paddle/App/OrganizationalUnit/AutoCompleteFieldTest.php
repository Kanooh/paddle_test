<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OrganizationalUnit\AutoCompleteFieldTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit;

use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Core\Pane\Base\AutoCompleteFieldTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\OrganizationalUnitPanelsContentType;
use Kanooh\Paddle\Pages\Node\EditPage\EditOrganizationalUnitPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditOrganizationalUnitPageRandomFiller;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class AutoCompleteFieldTest
 * @package Kanooh\Paddle\App\OrganizationalUnit
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class AutoCompleteFieldTest extends AutoCompleteFieldTestBase
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
        $this->appService->enableApp(new OrganizationalUnit);

        $app = new ContactPerson;
        $this->appService->disableAppsByMachineNames(array($app->getModuleName()));
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        // Create with required fields only.
        $nid = $this->contentCreationService->createOrganizationalUnit($title);

        // Fill the other fields.
        $edit_page = new EditOrganizationalUnitPage($this);
        $edit_page->go($nid);

        $random_filler = new EditOrganizationalUnitPageRandomFiller();
        // Randomize all values.
        $random_filler->randomize();
        // But, keep the original title, if it was specified.
        if (!is_null($title)) {
            $random_filler->unitName = $title;
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
                $field_property = $node_object->$searchable_node_field;
                if (isset($field_property['und'][0]['value'])) {
                    $this->nodeData[$nid][$searchable_node_field] = $field_property['und'][0]['value'];
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
        return new OrganizationalUnitPanelsContentType($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getAutoCompleteFormField()
    {
        return $this->getPanelsContentType()->getForm()->organizationalUnitAutocompleteField;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpectedSuggestion($nid)
    {
        return
            $this->nodeData[$nid]['title'] . ' (node/' .
            $this->nodeData[$nid]['nid'] . ')';
    }

    /**
     * {@inheritdoc}
     */
    public function getPartialExpectedSuggestions($nid)
    {
        return array(
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
