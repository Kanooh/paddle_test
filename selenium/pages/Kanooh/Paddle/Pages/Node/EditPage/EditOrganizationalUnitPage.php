<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\EditOrganizationalUnitPage.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\Text;

use Kanooh\Paddle\Pages\Element\Scald\AddAtomModal;
use Kanooh\Paddle\Pages\Element\Scald\Image\AddOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\ImageAtomField;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;

/**
 * Page to edit an organizational unit.
 *
 * @property ImageAtomField $logo
 * @property AutoCompletedText $openingHours
 * @property AutoCompletedText $parentEntity
 * @property AutoCompletedText $headOfUnitAutoComplete
 * @property Select $locationCountry
 * @property Text $unitName
 * @property Text $seoDescription
 * @property Text $headOfUnit
 * @property Text $locationName
 * @property Text $locationStreet
 * @property Text $locationStreetNumber
 * @property Text $locationPostalCode
 * @property Text $locationCity
 * @property Text $phone
 * @property Text $fax
 * @property Text $email
 * @property Text $website
 * @property Text $facebook
 * @property Text $twitter
 * @property Text $linkedin
 * @property Text $vatNumber
 */
class EditOrganizationalUnitPage extends EditPage
{

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'unitName':
                $element = $this->webdriver->byName('title');

                return new Text($this->webdriver, $element);
            case 'seoDescription':
                $element = $this->webdriver->byName('field_paddle_seo_description[und][0][value]');

                return new Text($this->webdriver, $element);
            case 'headOfUnit':
                $element = $this->webdriver->byName('field_paddle_ou_head_unit[und][0][value]');

                return new Text($this->webdriver, $element);
            case 'locationName':
                $element = $this->webdriver->byName('field_paddle_ou_address[und][0][name_line]');

                return new Text($this->webdriver, $element);
            case 'locationStreet':
                $element = $this->webdriver->byName('field_paddle_ou_address[und][0][thoroughfare]');

                return new Text($this->webdriver, $element);
            case 'locationStreetNumber':
                $element = $this->webdriver->byName('field_paddle_ou_address[und][0][premise]');

                return new Text($this->webdriver, $element);
            case 'locationPostalCode':
                $element = $this->webdriver->byName('field_paddle_ou_address[und][0][postal_code]');

                return new Text($this->webdriver, $element);
            case 'locationCity':
                $element = $this->webdriver->byName('field_paddle_ou_address[und][0][locality]');

                return new Text($this->webdriver, $element);
            case 'locationCountry':
                $element = $this->webdriver->byName('field_paddle_ou_address[und][0][country]');

                return new Select($this->webdriver, $element);
            case 'phone':
                $element = $this->webdriver->byName('field_paddle_ou_phone[und][0][value]');

                return new Text($this->webdriver, $element);
            case 'fax':
                $element = $this->webdriver->byName('field_paddle_ou_fax[und][0][value]');

                return new Text($this->webdriver, $element);
            case 'email':
                $element = $this->webdriver->byName('field_paddle_ou_email[und][0][email]');

                return new Text($this->webdriver, $element);
            case 'website':
                $element = $this->webdriver->byName('field_paddle_ou_website[und][0][value]');

                return new Text($this->webdriver, $element);
            case 'facebook':
                $element = $this->webdriver->byName('field_paddle_ou_facebook[und][0][value]');

                return new Text($this->webdriver, $element);
            case 'twitter':
                $element = $this->webdriver->byName('field_paddle_ou_twitter[und][0][value]');

                return new Text($this->webdriver, $element);
            case 'linkedin':
                $element = $this->webdriver->byName('field_paddle_ou_linkedin[und][0][value]');

                return new Text($this->webdriver, $element);
            case 'vatNumber':
                $element = $this->webdriver->byName('field_paddle_ou_vat_number[und][0][value]');

                return new Text($this->webdriver, $element);
            case 'logo':
                $element = $this->webdriver->byXPath('//div/input[@name="field_paddle_ou_logo[und][0][sid]"]/..');

                return new ImageAtomField($this->webdriver, $element);
            case 'openingHours':
                $element = $this->webdriver->byName('field_paddle_opening_hours[und][0][target_id]');

                return new AutoCompletedText($this->webdriver, $element);
            case 'parentEntity':
                $element = $this->webdriver->byName('field_paddle_ou_parent_entity[und][0][target_id]');

                return new AutoCompletedText($this->webdriver, $element);
            case 'headOfUnitAutoComplete':
                $element = $this->webdriver->byName('field_paddle_ou_cp_head_unit[und][0][target_id]');

                return new AutoCompletedText($this->webdriver, $element);
        }

        return parent::__get($property);
    }

    /**
     * Fills in the logo field using a file upload.
     */
    public function fillLogoField()
    {
        $this->logo->selectButton->click();
        $library_modal = new LibraryModal($this->webdriver);
        $library_modal->waitUntilOpened();

        // Add a new image.
        $library_modal->addAssetButton->click();

        $add_modal = new AddAtomModal($this->webdriver);
        $add_modal->waitUntilOpened();

        $image_path = dirname(__FILE__) . '/../../../../../../tests/Kanooh/Paddle/assets/sample_image.jpg';
        $image = $this->webdriver->file($image_path);
        $title = basename($image_path);

        $add_modal->form->fileList->uploadFiles($image);

        // Add an alternative text.
        $options_modal = new AddOptionsModal($this->webdriver);
        $options_modal->waitUntilOpened();

        $alt_text = 'sample_image.jpg';

        $options_modal->form->alternativeText->fill($alt_text);
        $options_modal->form->finishButton->click();

        // Make sure the image is added to the library correctly, and get its
        // atom id.
        $library_modal->waitUntilOpened();
        $this->webdriver->waitUntilTextIsPresent('Atom ' . $title . ', of type Image has been created.');

        $atom = $library_modal->library->items[0];

        // Insert the atom in the CKEditor.
        $atom->insertLink->click();
        $library_modal->waitUntilClosed();
    }
}
