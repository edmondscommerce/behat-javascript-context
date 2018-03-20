<?php declare(strict_types=1);

namespace EdmondsCommerce\BehatJavascriptContext;

use Behat\Mink\Mink;
use EdmondsCommerce\MockServer\MockServer;

/**
 * Class JavascriptEventsContextTest
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @package EdmondsCommerce\BehatJavascriptContext
 */
class JavascriptEventsContextTest extends AbstractTestCase
{
    /**
     * @var JavascriptEventsContext
     */
    private $context;

    /**
     * @var MockServer
     */
    private $server;

    /**
     * @throws \Exception
     */
    public function setUp()
    {
        parent::setUp();

        //Set up the mock server
        $this->server = new MockServer(__DIR__ . '/assets/routers/router.php', $this->getContainerIp(), 8080);
        $this->server->startServer();

        $mink = new Mink(['selenium2' => $this->seleniumSession]);
        $mink->setDefaultSessionName('selenium2');
        $this->seleniumSession->start();

        //Set up Mink in the class
        $this->context = new JavascriptEventsContext();
        $this->context->setMink($mink);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->seleniumSession->stop();
        $this->server->stopServer();
    }

    public function testWaitForDocumentReadyShouldFullyLoadThePage() {
        $url = $this->server->getUrl('/');

        $this->seleniumSession->visit($url);

        $this->assertTrue($this->context->iWaitForDocumentReady());
    }

    public function testWaitForDocumentReadyShouldFailToFullyLoadThePageWithin10s() {
        $url = $this->server->getUrl('/slow-loading-js-host');

        $this->seleniumSession->visit($url);

        $this->assertTrue($this->context->iWaitForDocumentReady());
    }

    public function testWaitForElementToBeVisibleWillBeVisible() {
        $url = $this->server->getUrl('/');

        $this->seleniumSession->visit($url);

        $css = '#invisibleElement';

        $this->assertTrue($this->context->iWaitForElementToBeVisible($css));
    }

    public function testWaitForElementToBeVisibleWillNotBeVisible() {
        $url = $this->server->getUrl('/jquery');

        $this->seleniumSession->visit($url);

        $css = '#invisibleElement';

        $this->assertFalse($this->context->iWaitForElementToBeVisible($css));
    }

    public function testWaitForJQueryShouldNotFindJquery() {
        $url = $this->server->getUrl('/');

        $this->seleniumSession->visit($url);

        $this->assertFalse($this->context->iWaitForJQuery());
    }

    public function testWaitForJQueryShouldFindJquery() {
        $url = $this->server->getUrl('/jquery');

        $this->seleniumSession->visit($url);

        $this->assertTrue($this->context->iWaitForJQuery());
    }

    public function testWaitForAjaxToFinishJqueryQuickCallTest() {
        $url = $this->server->getUrl('/jquery-ajax');

        $this->seleniumSession->visit($url);

        $this->assertTrue($this->context->iWaitForAjaxToFinish());
    }

    public function testWaitForAjaxToFinishJquerySlowCallTestWillFailToLoadIn10s() {
        $url = $this->server->getUrl('/jquery-ajax-slow');

        $this->seleniumSession->visit($url);

        $this->assertFalse($this->context->iWaitForAjaxToFinish());
    }

    public function testWaitForAjaxToFinishPrototypeJsQuickCallTest() {
        $url = $this->server->getUrl('/prototypejs-ajax');

        $this->seleniumSession->visit($url);

        $this->assertTrue($this->context->iWaitForAjaxToFinish());
    }

    public function testWaitForAjaxToFinishPrototypeJsSlowCallTestWillFailToLoadIn10s() {
        $url = $this->server->getUrl('/prototypejs-ajax-slow');

        $this->seleniumSession->visit($url);

        $this->assertFalse($this->context->iWaitForAjaxToFinish());
    }

    public function testWaitForAjaxToFinishJqueryAndPrototypeJsBothCallsPassesQuick() {
        $url = $this->server->getUrl('/jquery-and-prototypejs-ajax');

        $this->seleniumSession->visit($url);

        $this->assertTrue($this->context->iWaitForAjaxToFinish());
    }

    public function testWaitForAjaxToFinishJqueryAndPrototypeJsBothCallsFailsToLoadIn20sSlow() {
        $url = $this->server->getUrl('/jquery-and-prototypejs-ajax-slow');

        $this->seleniumSession->visit($url);

        $this->assertFalse($this->context->iWaitForAjaxToFinish());
    }

    public function testWaitForJqueryAjaxToFinishQuickCall() {
        $url = $this->server->getUrl('/jquery-ajax');

        $this->seleniumSession->visit($url);

        $this->assertTrue($this->context->iWaitForJqueryAjaxToFinish());
    }

    public function testWaitForJqueryAjaxToFinishSlowCall() {
        $url = $this->server->getUrl('/jquery-ajax-slow');

        $this->seleniumSession->visit($url);

        $this->assertFalse($this->context->iWaitForJqueryAjaxToFinish());
    }

    public function testWaitForPrototypeJsAjaxToFinishQuickCall() {
        $url = $this->server->getUrl('/prototypejs-ajax');

        $this->seleniumSession->visit($url);

        $this->assertTrue($this->context->iWaitForPrototypeJsAjaxToFinish());
    }

    public function testWaitForPrototypeJsAjaxToFinishSlowCall() {
        $url = $this->server->getUrl('/prototypejs-ajax-slow');

        $this->seleniumSession->visit($url);

        $this->assertFalse($this->context->iWaitForPrototypeJsAjaxToFinish());
    }
}