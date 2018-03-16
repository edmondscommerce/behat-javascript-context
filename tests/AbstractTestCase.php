<?php declare(strict_types=1);

namespace EdmondsCommerce\BehatJavascriptContext;

use Behat\Mink\Driver\Selenium2Driver;
use PHPUnit\Framework\TestCase;
use Behat\Mink\Session;

abstract class AbstractTestCase extends TestCase
{
    /**
     * @var Session
     */
    protected $seleniumSession;

    protected $containerIp;

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @param bool $headless
     */
    protected function setUpSeleniumMink(bool $headless = true)
    {
        $args = [
            'chrome' => [
                'switches' => [
                    '--disable-gpu',
                    '--window-size=1920,1080',
                    '--start-maximised',
                ],
            ],
        ];

        if ($headless) {
            $args['chrome']['switches'][] = '--headless';
        }

        $driver = new Selenium2Driver('chrome', $args);

        $this->seleniumSession = new Session($driver);
    }

    public function setUp()
    {
        parent::setUp();
        $this->setUpSeleniumMink(false);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     * @return mixed
     */
    protected function getContainerIp()
    {
        $commandToExecute = 'ip addr show eth0 | grep "inet\b" | awk \'{print $2}\' | cut -d/ -f1';

        exec($commandToExecute, $commandOutput, $exitCode);


        return array_pop($commandOutput);
    }
}
