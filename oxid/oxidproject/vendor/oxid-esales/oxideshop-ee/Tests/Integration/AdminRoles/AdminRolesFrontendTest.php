<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopEnterprise\Tests\Integration\AdminRoles;

/**
 * Class AdminRolesFrontendTest
 *
 * @package OxidEsales\EshopEnterprise\Tests\Integration\AdminRoles
 */
class AdminRolesFrontendTest extends \OxidEsales\EshopEnterprise\Tests\Integration\AdminRoles\AdminRolesTestCase
{
    /**
     * Some controllers need not to be checked.
     *
     * @var array
     */
    protected $skipCheck = [
        'systeminfo',
        'module_config'
    ];

    /**
     * We have some exceptions, where it is ok for a readonly rights admin to get a
     * not disabled input field. One example is to be able to select language from dropdown to get
     * data displayed in the chosen language.
     *
     * @var array
     */
    protected $noReadOnly = [
        'news_text' => [
            'textarea' => [
                //NOTE: to disable this textarea field needs some refactoring of shop TextEditorHandler first.
                //At least the submit button is disabled.
                'editor_oxnews__oxlongdesc  oxnews__oxlongdesc'
            ]
        ],
        'newsletter_main' => [
            'textarea' => [
                //NOTE: to disable this textarea field needs some refactoring of shop TextEditorHandler first.
                //At least the submit button is disabled.
                'editor_oxnewsletter__oxtemplate  oxnewsletter__oxtemplate'
            ]
        ],
        'adminlinks_main' => [
            'textarea' => [
                //NOTE: to disable this textarea field needs some refactoring of shop TextEditorHandler first.
                //At least the submit button is disabled.
                'editor_oxlinks__oxurldesc  oxlinks__oxurldesc'
            ]
        ],
        'content_main' => [
            'textarea' => [
                //NOTE: to disable this textarea field needs some refactoring of shop TextEditorHandler first.
                //At least the submit button is disabled.
                'editor_oxcontents__oxcontent  oxcontents__oxcontent'
            ]
        ],
        'pricealarm_main' => [
            'textarea' => [
                //NOTE: to disable this textarea field needs some refactoring of shop TextEditorHandler first.
                //At least the submit button is disabled.
                'editor_oxpayments__oxlongdesc  oxpayments__oxlongdesc'
            ]
        ],
    ];

    /**
     * Check that rendered output has readonly disabled fields according to readonly role.
     * Runs over all admin controllers and checks the input fields.
     * NOTE: <name>_list controller are not checked here, als they only display data
     *       (which was verified when writing this test).
     *
     * @group adminroles
     */
    public function testRenderedOutput()
    {
        $maps = $this->getNavigationMaps();
        $this->assignFieldsToRole(array_keys($maps['id2cl']), 'oxview', 1);
        $this->assignFieldsToRole(array_keys($maps['id2list']), 'oxview', 1);

        $this->preventRedirects();
        $this->mockCookieCount();
        $this->logInTestAdmin();

        $fails = [];
        $adminControllers = $this->getAdminControllerIdToNavigationGroup();
        foreach ($adminControllers as $controllerId => $navigationGroup) {

            //We can skip all lists as they only display data
            if ( in_array($controllerId, $this->skipCheck)
                || (false !== strpos($controllerId, 'list'))
            ) {
                continue;
            }

            $this->prepareForAdminRequest($controllerId);
            $content = $this->getTemplateOutput($controllerId);
            $result = $this->runChecks($content);

            if (!empty($result) && ($result !== $this->noReadOnly[$controllerId])) {
                $fails[$controllerId] = $result;
            }

            $this->clearTmpDir();
        }

        $this->assertTrue(empty($fails), serialize($fails));
    }

    /**
     * @param string $controllerId Controller key
     *
     * @return mixed
     */
    protected function getTemplateOutput($controllerId)
    {
        $resolvedClass = \OxidEsales\Eshop\Core\Registry::getControllerClassNameResolver()->getClassNameById($controllerId);
        $shopControl = oxNew(\OxidEsales\Eshop\Core\ShopControl::class);
        $controller = $shopControl->_initializeViewObject($resolvedClass, 'render');

        return $this->doRender($controller, $this->getAdditionalViewData($controllerId));
    }
    /**
     * Test helper to render output.
     *
     * @param object $controller  Controller object
     * @param array  $addViewData Optional view data
     *
     * @return string
     */
    protected function doRender($controller, $addViewData = [])
    {
        $template = $controller->render();

        //prepare output
        $output = oxNew(\OxidEsales\Eshop\Core\Output::class);
        $viewData = $output->processViewArray($controller->getViewData(), $controller->getClassName());
        if (is_array($addViewData)) {
            $viewData = array_merge($viewData, $addViewData);
        }
        $controller->setViewData($viewData);
        return \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsView::class)->getTemplateOutput($template, $controller);

  }

    /**
     * Test helper to simulate admin request.
     *
     * @param string $controllerId
     */
    protected function prepareForAdminRequest($controllerId)
    {
        $requestData = [
            'stoken' => \OxidEsales\Eshop\Core\Registry::getSession()->getSessionChallengeToken(),
            'force_admin_sid' => \OxidEsales\Eshop\Core\Registry::getSession()->getId(),
            'cl' => $controllerId
        ];
        foreach ($requestData as $key => $value) {
            $this->setRequestParameter($key, $value);
        }
    }

    /**
     * Test helper to load xPath from string.
     *
     * @param string $content
     */
    protected function getXPath($content)
    {
        libxml_use_internal_errors(true);
        $domDoc = new \DOMDocument();
        $domDoc->loadHTML($content, LIBXML_NOERROR);
        return new \DOMXPath($domDoc);
    }

    /**
     * Run checks on input fields.
     *
     * @param DOMXPath $xPath
     *
     * @return array
     */
    protected function runChecks($content)
    {
        $result = [];
        $xPath = $this->getXPath($content);
        $queries = [
            'input'    => "//input[@type!='hidden' and not(contains(@id, 'helpBtn'))]",
            'select'   => "//select[not(contains(@id, 'test_editlanguage')) and not(contains(@name, 'changelang'))]",
            'textarea' => "//textarea"
        ];

        foreach ($queries as $key => $query) {
            $miss = $this->assertReadonlyInput($xPath, $query);
            if (!empty($miss)) {
                $result[$key] = $miss;
            }
        }
        return $result;
    }

    /**
     * @param DOMXPath $xPath
     * @param string   $query
     *
     * @return array
     */
    protected function assertReadonlyInput($xPath, $query)
    {
        $misses = [];
        $nodeList = $xPath->query($query);
        if ($nodeList->length) {
            for ($i=0; $i<$nodeList->length; $i++) {
                $item = $nodeList->item($i);
                if (('readonly' !== $item->getAttribute('readonly')) && ('disable' !== $item->getAttribute('disabled'))) {
                    $misses[] = trim($item->getAttribute('id') . ' ' . $item->getAttribute('type') . ' ' . $item->getAttribute('name'));
                }
            }
        }
        return $misses;
    }

    protected function getAdditionalViewData($controllerId)
    {
        $theme = oxNew(\OxidEsales\Eshop\Core\Theme::class);
        $theme->load('flow');

        $pricealarm = $this->getMockBuilder(\OxidEsales\Eshop\Application\Model\PriceAlarm::class)
            ->disableOriginalConstructor()
            ->setMethods(['getArticle', 'getPriceAlarmCurrency'])
            ->getMock();
        $pricealarm->expects($this->any())
            ->method('getArticle')
            ->willReturn(oxNew(\OxidEsales\Eshop\Application\Model\Article::class));

        $user = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $user->load(self::ADMIN_USER_ID);

        $data = [
            'theme_config' => [
                'oTheme' => $theme
            ],
            'order_article' => [
                'edit' => oxNew(\OxidEsales\Eshop\Application\Model\Order::class)
            ],
            'pricealarm_main' => [
                'edit' => $pricealarm
            ],
            'user_extend' => [
                'edit' => $user
            ],
            'order_downloads' => [
                'edit' => []
            ],
            'order_main' => [
                'edit' => oxNew(\OxidEsales\Eshop\Application\Model\Order::class)
            ],
            'article_stock' => [
                'amountprices' => []
            ]
        ];

        return isset($data[$controllerId]) ? $data[$controllerId] : [];
    }
}
