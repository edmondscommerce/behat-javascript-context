<?php namespace EdmondsCommerce\BehatJavascriptContext;

use /** @noinspection PhpDeprecationInspection */
    Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;

/** @noinspection PhpDeprecationInspection */
class CookieContext extends RawMinkContext implements SnippetAcceptingContext
{

    /**
     * @Then I set the cookie :cookieName value to :cookieValue
     * @param string $cookieName
     * @param string $cookieValue
     */
    public function iSetCookieValue($cookieName, $cookieValue)
    {
        $this->getSession()->setCookie($cookieName, $cookieValue);
    }

    /**
     * @Then I delete the cookie :cookieName
     * @param string $cookieName
     */
    public function iDeleteTheCookie($cookieName)
    {
        $this->getSession()->setCookie($cookieName, null);
    }
}
