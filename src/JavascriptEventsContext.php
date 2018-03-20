<?php namespace EdmondsCommerce\BehatJavascriptContext;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeStepScope;
use Behat\MinkExtension\Context\RawMinkContext;

class JavascriptEventsContext extends RawMinkContext implements Context, SnippetAcceptingContext
{
    public function initializePendingAjaxRequestsVariable() {
        $script = <<<JS
    (function() {
        window.XMLHttpRequest.pendingAjaxRequests = 0;
        
var open = window.XMLHttpRequest.prototype.open,  
  send = window.XMLHttpRequest.prototype.send;

function openReplacement(method, url, async, user, password) {  
  this._url = url;
  return open.apply(this, arguments);
}

function sendReplacement(data) {  
  if(this.onreadystatechange) {
    this._onreadystatechange = this.onreadystatechange;
  }
 
    window.XMLHttpRequest.pendingAjaxRequests = 1;
  
  this.onreadystatechange = onReadyStateChangeReplacement;
  return send.apply(this, arguments);
}

function onReadyStateChangeReplacement() {
    if (this.readyState === 4) {
        window.XMLHttpRequest.pendingAjaxRequests = 0;
    }
    
  console.log('Ready state changed to: ', this.readyState);
  
  if(this._onreadystatechange) {
    return this._onreadystatechange.apply(this, arguments);
  }
}

window.XMLHttpRequest.prototype.open = openReplacement;  
window.XMLHttpRequest.prototype.send = sendReplacement;
    })();
JS;
        $this->getSession()->executeScript($script);
    }

    /**
     * @TODO Add more javascript libraries as required.
     * @Then /^I wait for AJAX to finish$/
     */
    public function iWaitForAjaxToFinish()
    {
        $response = false;

        // jQuery ajax calls
        if ((bool) $this->getSession()->evaluateScript('typeof jQuery !== "undefined"')) {
            $response = $this->getSession()->wait(10000, "0 === jQuery.active");
        }

        // prototype.js ajax calls
        if ((bool) $this->getSession()->evaluateScript('typeof Ajax !== "undefined"')) {
            $response = $this->getSession()->wait(10000, "0 === Ajax.activeRequestCount");
        }

        return $response;
    }

    public function iWaitForJqueryAjaxToFinish() {
        return $this->getSession()->wait(10000, "0 === jQuery.active");
    }

    public function iWaitForPrototypeJsAjaxToFinish() {
        return $this->getSession()->wait(10000, "0 === Ajax.activeRequestCount");
    }

    /**
     * Selenium Web Driver will execute second parameter of wait() method only when document is loaded anyways
     * Therefore method cannot be tested for failure
     *
     * @Then I wait for the document ready event
     * @Then I wait for the page to fully load
     * @Then I wait for the page to reload
     */
    public function iWaitForDocumentReady()
    {
        return $this->getSession()->wait(10000, '("complete" === document.readyState)');
    }

    /**
     * @Then I wait for jQuery to finish loading
     */
    public function iWaitForJQuery()

    {
        return $this->getSession()->wait(5000, 'typeof window.jQuery == "function"');
    }

    /**
     * @Given I wait for the element :arg1 to be visible
     */
    public function iWaitForElementToBeVisible($arg1)
    {
        $script = <<<JS
(function() {
    function isVisible(e) {
        return !!( e.offsetWidth || e.offsetHeight || e.getClientRects().length );
    }
    
    return isVisible(document.querySelector('$arg1'));
})();
JS;


        return $this->getSession()->wait(5000, $script);
    }

    
}