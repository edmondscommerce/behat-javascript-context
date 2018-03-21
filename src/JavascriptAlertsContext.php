<?php namespace EdmondsCommerce\BehatJavascriptContext;


use Behat\MinkExtension\Context\RawMinkContext;

use RuntimeException;


class JavascriptAlertsContext extends RawMinkContext
{

    /**
     * @When I cancel the popup
     * @When I dismiss the alert
     */
    public function iCancelThePopup()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->getSession()->getDriver()->getWebDriverSession()->dismiss_alert();
    }

    /**
     * @Then I should see an alert asking :arg1
     * @When I see an alert containing :arg1
     * @param $arg1
     * @return string $text
     * @throws \RuntimeException
     */
    public function iShouldSeeAnAlertAsking($arg1): string
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $text = $this->getSession()->getDriver()->getWebDriverSession()->getAlert_text();
        if ($arg1 !== $text) {
            throw new RuntimeException('The alert\'s text "' . $text . '" does not equal ' . $arg1);
        }

        return $text;
    }

    /**
     * @Given I disable the alerts
     */
    public function iDisableTheAlerts()
    {
        $this->getSession()->wait(2000, 'window.alert = function() {}');
    }

    /**
     * @When I accept the popup
     * @Then I accept the dialog
     */
    public function iAcceptThePopup()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->getSession()->getDriver()->getWebDriverSession()->accept_alert();
    }

    /**
     * @Then /^I press button "([^"]*)" should see an alert saying "([^"]*)"$/
     * @param $button
     * @param $arg1
     * @return string
     * @throws \RuntimeException
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function iPressButtonShouldSeeAnAlertSaying($button, $arg1): string
    {
        $this->getSession()->getPage()->pressButton($button);

        return $this->iShouldSeeAnAlertAsking($arg1);
    }
}
