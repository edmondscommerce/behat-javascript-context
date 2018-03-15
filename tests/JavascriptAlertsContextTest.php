<?php declare(strict_types=1);

namespace EdmondsCommerce\BehatJavascriptContext;


use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Mink;
use EdmondsCommerce\MockServer\MockServer;
use WebDriver\Exception\NoAlertOpenError;

class JavascriptAlertsContextTest extends AbstractTestCase
{
    /**
     * @var JavascriptAlertsContext
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
        $this->context = new JavascriptAlertsContext();
        $this->context->setMink($mink);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->seleniumSession->stop();
        $this->server->stopServer();
    }

    public function testCancelThePopupShouldCancelThePopup() {
        $url = $this->server->getUrl('/alert');

        $this->seleniumSession->visit($url);

        $this->context->iCancelThePopup();

        $this->expectException(NoAlertOpenError::class);

        $this->seleniumSession->getDriver()->getWebDriverSession()->getAlert_text();
    }

    public function testShouldSeeAnAlertAsking() {
        $url = $this->server->getUrl('/alert');

        $this->seleniumSession->visit($url);

        $expected = 'Hello World!';

        $actual = $this->context->iShouldSeeAnAlertAsking($expected);

        $this->assertEquals($expected, $actual);
    }

    public function testShouldSeeAnAlertAskingThatShouldNotMatchAndThrowAnException() {
        $url = $this->server->getUrl('/alert');

        $this->seleniumSession->visit($url);

        $expected = 'Some text!';

        $this->expectException(\Exception::class);

        $this->context->iShouldSeeAnAlertAsking($expected);
    }

    /**
     * @throws \Exception
     */
    public function testDisableTheAlertsShouldNotAllowAlertToBeDisplayed() {
        $url = $this->server->getUrl('/');

        $this->seleniumSession->visit($url);

        $this->context->iDisableTheAlerts();

        $expected = 'Hi!';

        $this->seleniumSession->executeScript("alert('" . $expected . "')");

        $this->expectException(NoAlertOpenError::class);

        $this->seleniumSession->getDriver()->getWebDriverSession()->getAlert_text();
    }

    public function testAcceptThePopupShouldAllowToExecuteAnyJavascriptAfterAcception() {
        $url = $this->server->getUrl('/alert');

        $this->seleniumSession->visit($url);

        $this->context->iAcceptThePopup();

        $alertDiv = $this->seleniumSession->evaluateScript("return document.getElementById('alertDiv').innerHTML;");

        $this->assertEquals('ACCEPTED', $alertDiv);
    }

    public function testPressButtonShouldSeeAnAlertSayingWelcome() {
        $url = $this->server->getUrl('/');

        $this->seleniumSession->visit($url);

        $button = 'btn';
        $expected = 'Welcome!';

        $this->assertEquals($expected, $this->context->iPressButtonShouldSeeAnAlertSaying($button, $expected));
    }

    public function testPressButtonShouldSeeAnAlertSayingWillThrowNoAlertFoundException() {
        $url = $this->server->getUrl('/');

        $this->seleniumSession->visit($url);

        $button = 'noalert-btn';
        $expected = 'Welcome!';

        $this->expectException(NoAlertOpenError::class);

        $this->context->iPressButtonShouldSeeAnAlertSaying($button, $expected);
    }

    public function testPressButtonShouldSeeAnAlertSayingWillThrowButtonNotFoundException() {
        $url = $this->server->getUrl('/');

        $this->seleniumSession->visit($url);

        $button = 'nonexistent-btn';
        $expected = 'Welcome!';

        $this->expectException(ElementNotFoundException::class);

        $this->context->iPressButtonShouldSeeAnAlertSaying($button, $expected);
    }
}