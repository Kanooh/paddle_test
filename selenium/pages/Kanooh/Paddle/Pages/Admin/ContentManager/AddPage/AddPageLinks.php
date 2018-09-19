<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPageLinks.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\AddPage;

use Kanooh\Paddle\Pages\Element\Links\Links;

/**
 * Links for the AddPage page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkPaddleAdvancedSearchPage
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkBasicPage
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkCalendarItem
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkPaddleCirroPage
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkCompanyPage
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkSimpleContactPage
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkPaddleEblPage
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkOrganizationalUnit
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkPaddleMapsPage
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkContactPerson
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkNewsItem
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkNewsletter
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkQuizPage
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkAssetTypeFile
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkAssetTypeImage
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkAssetTypeVideo
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkFormbuilderPage
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkPollPage
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkProductPage
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkOfferPage
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkPaddlePublication
 */
class AddPageLinks extends Links
{

    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        $add_node_xpath = '//div[contains(@class, "pane-node-add-content-type-selection")]';
        $add_asset_xpath = '//div[contains(@class, "pane-create-atom-links-list")]';
        return array(
            'PaddleAdvancedSearchPage' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-paddle_advanced_search_page")]',
                'title' => 'Advanced search page',
            ),
            'BasicPage' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-basic_page")]',
                'title' => 'Basic page',
            ),
            'CalendarItem' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-calendar_item")]',
                'title' => 'Calendar item',
            ),
            'PaddleCirroPage' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-paddle_cirro_page")]',
                'title' => 'CIRRO page',
            ),
            'CompanyPage' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-company_page")]',
                'title' => 'Publication',
            ),
            'SimpleContactPage' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-simple_contact_page")]',
                'title' => 'Simple Contact Page',
            ),
            'PaddleEblPage' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-paddle_ebl_page")]',
                'title' => 'EBL Page',
            ),
            'OrganizationalUnit' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-organizational_unit")]',
                'title' => 'Organizational unit',
            ),
            'PaddleMapsPage' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-paddle_maps_page")]',
                'title' => 'Maps page',
            ),
            'ContactPerson' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-contact_person")]',
                'title' => 'Contact person',
            ),
            'NewsItem' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-news_item")]',
                'title' => 'News item',
            ),
            'Newsletter' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-newsletter")]',
                'title' => 'Newsletter',
            ),
            'QuizPage' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-quiz_page")]',
                'title' => 'Quiz page',
            ),
            'AssetTypeFile' => array(
                'xpath' => $add_asset_xpath . '//a[contains(@class, "create-asset-file")]',
                'title' => 'File',
            ),
            'AssetTypeImage' => array(
                'xpath' => $add_asset_xpath . '//a[contains(@class, "create-asset-image")]',
                'title' => 'Image',
            ),
            'AssetTypeVideo' => array(
                'xpath' => $add_asset_xpath . '//a[contains(@class, "create-asset-video")]',
                'title' => 'Video',
            ),
            'FormbuilderPage' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-paddle_formbuilder_page")]',
                'title' => 'Paddle Formbuilder Page',
            ),
            'PollPage' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-poll")]',
                'title' => 'Poll',
            ),
            'ProductPage' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-paddle_product")]',
                'title' => 'Product',
            ),
            'OfferPage' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-offer")]',
                'title' => 'Offer',
            ),
            'PaddlePublication' => array(
                'xpath' => $add_node_xpath . '//a[contains(@class, "create-paddle_publication")]',
                'title' => 'Publication',
            ),
        );
    }
}
