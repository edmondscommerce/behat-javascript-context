<?php declare(strict_types=1);

namespace EdmondsCommerce\BehatJavascriptContext;


use Behat\Mink\Mink;
use EdmondsCommerce\MockServer\MockServer;

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

    public function testCancelThePopupWillCancelThePopup() {


    }
}