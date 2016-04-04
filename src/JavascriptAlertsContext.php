<?php namespace EdmondsCommerce\BehatJavascriptContext;

use Behat\MinkExtension\Context\RawMinkContext;
use Exception;

class JavascriptAlertsContext extends RawMinkContext
{

    /**
     * @When I cancel the popup
     * @When I dismiss the alert
     */
    public function iCancelThePopup()
    {
        $this->getSession()->getDriver()->getWebDriverSession()->dismiss_alert();
    }

    /**
     * @Then I should see an alert asking :arg1
     * @When I see an alert containing :arg1
     */
    public function iShouldSeeAnAlertAsking($arg1)
    {
        $text = $this->getSession()->getDriver()->getWebDriverSession()->getAlert_text();
        if ($arg1 != $text)
        {
            throw new Exception('The alert\'s text "' . $text . '" does not equal' . $arg1);
        }
    }

    /**
     * @Given I disable the alerts
     */
    public function iDisableTheAlerts()
    {
        $this->getSession()->wait(5000, 'window.alert = function() {}');
    }

    /**
     * @When I accept the popup
     * @Then I accept the dialog
     */
    public function iAcceptThePopup()
    {
        $this->getSession()->getDriver()->getWebDriverSession()->accept_alert();
    }


}