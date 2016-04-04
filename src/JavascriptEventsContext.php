<?php namespace EdmondsCommerce\BehatJavascriptContext;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;

class JavascriptEventsContext extends RawMinkContext implements Context, SnippetAcceptingContext
{
    /**
     * @Then /^I wait for AJAX to finish$/
     */
    public function iWaitForAjaxToFinish()
    {
        $this->getSession()->wait(10000, '((0 === Ajax.activeRequestCount) && (0 === jQuery.active))');
        $this->getSession()->wait(1000);
    }

    /**
     * @Then /^I wait for AJAX Content to load$/
     */
    public function iWaitForAjaxContentToLoad()
    {
        $this->getSession()->wait(5000);
    }

    /**
     * @Then I wait for the document ready event
     * @Then I wait for the page to fully load
     * @Then I wait for the page to reload
     */
    public function iWaitForDocumentReady()
    {
        $this->getSession()->wait(10000, '("complete" === document.readyState)');
    }

    /**
     * @Then I wait for jQuery to finish loading
     */
    public function iWaitForJQuery()

    {
        $this->getSession()->wait(5000, 'typeof window.jQuery == "function"');
    }

    /**
     * @Given I wait for the element :arg1 to be visible
     */
    public function iWaitForElementToBeVisible($arg1)
    {
        $this->getSession()->wait(5000, 'jQuery("' . $arg1 . '").is(\':visible\')');
    }

    
}