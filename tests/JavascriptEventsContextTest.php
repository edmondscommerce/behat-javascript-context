<?php declare(strict_types=1);

namespace EdmondsCommerce\BehatJavascriptContext;

use Behat\Mink\Mink;
use EdmondsCommerce\MockServer\MockServer;

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

    public function testWaitForAjaxToFinishQuick() {
        $url = $this->server->getUrl('/ajax');

        $this->seleniumSession->visit($url);

        $this->assertTrue($this->context->iWaitForAjaxToFinish());
    }

    public function testWaitForAjaxToFinishWillFailToLoadAjaxContentIn10s() {
        $url = $this->server->getUrl('/ajax-slow');

        $this->seleniumSession->visit($url);

        $this->assertFalse($this->context->iWaitForAjaxToFinish());
    }

    public function testWaitForJqueryAjaxToFinish() {
        $url = $this->server->getUrl('/jquery-ajax');

        $this->seleniumSession->visit($url);

        $this->assertTrue($this->context->iWaitForJqueryAjaxToFinish());
    }

    public function testWaitForJqueryAjaxToFinishWillFailToLoadAjaxContentIn10s() {
        $url = $this->server->getUrl('/jquery-ajax-slow');

        $this->seleniumSession->visit($url);

        $this->assertFalse($this->context->iWaitForJqueryAjaxToFinish());
    }
}