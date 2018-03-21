# Behat Javascript Context

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/155668a485344c03b483c395e7a938dd)](https://app.codacy.com/app/edmondscommerce/behat-javascript-context?utm_source=github.com&utm_medium=referral&utm_content=edmondscommerce/behat-javascript-context&utm_campaign=badger)
[![Build Status](https://travis-ci.org/edmondscommerce/behat-javascript-context.svg?branch=master)](https://travis-ci.org/edmondscommerce/behat-javascript-context)

## By [Edmonds Commerce](https://www.edmondscommerce.co.uk)

A simple Behat Context for working with HTML and navigation

### Installation

Install via composer

    "edmondscommerce/behat-javascript-context": "dev-master"

### Include Context in Behat Configuration
        
    default:
        # ...
        suites:
            default:
                # ...
                contexts:
                    - # ...
                    - EdmondsCommerce\BehatJavascriptContext\JavascriptEventsContext
                    - EdmondsCommerce\BehatJavascriptContext\CookieContext
                    - EdmondsCommerce\BehatJavascriptContext\JavascriptAlertsContext
