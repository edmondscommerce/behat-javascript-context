<?php namespace EdmondsCommerce\BehatJavascriptContext;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;

class CookieContext extends RawMinkContext implements Context, SnippetAcceptingContext
{

    /**
     * @Then I set the cookie :cookieName value to :cookieValue
     */
    public function iSetCookieValue($cookieName, $cookieValue)
    {
        $this->getSession()->setCookie($cookieName, $cookieValue);
    }

    /**
     * @Then I delete the cookie :cookieName
     */
    public function iDeleteTheCookie($cookieName)
    {
        $this->getSession()->setCookie($cookieName, null);
    }
}
