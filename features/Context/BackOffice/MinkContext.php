<?php

namespace Context\BackOffice;

use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Mink\Exception\ElementNotFoundException;
use Knp\FriendlyContexts\Context\MinkContext as BaseMinkContext;

class MinkContext extends BaseMinkContext
{
	/**
	 * @When /^I hover the navbar (\d+)(?:st|nd|rd|th) "(.+)" (link|button)$/
	 */
	public function iHoverTheNavbarXthLinkOrButton($x, $text, $originalElement)
	{
		$element = $originalElement === 'link' ? 'a' : 'button';

		$page = $this->getSession()->getPage();

		$linksOrButtons = $page->findAll('css', $element);

		if (empty($linksOrButtons)) {
			throw new \Exception(sprintf('No %s element was found on this page'));
		}

		$links = array_values(array_filter($linksOrButtons, function ($item) use ($text) {
			if ($text === $item->getText()) {
				return $item;
			}
			if ($text === $item->getHtml()) {
				return $item;
			}
		}));

		if ($x > count($links)) {
			throw new \Exception(sprintf(
				'Not enough elements: %s found, %s expected',
				count($links),
				$x
			));
		}

		$links[$x - 1]->mouseOver();
		$this->getSession()->wait(5000, "$('.subMenu').children().length > 1");
	}

	/**
	 * @AfterStep
	 */
	public function printLastResponseOnError(AfterStepScope $event)
	{
		if (!$event->getTestResult()->isPassed()) {
			$this->saveDebugScreenshot();
		}
	}

	/**
	 * @Then /^save screenshot$/
	 */
	public function saveDebugScreenshot()
	{
		$filename = microtime(true).'.png';
		$path = __DIR__.'/../../../behat_screenshots';
		if (!file_exists($path)) {
			mkdir($path);
		}
		$this->saveScreenshot($filename, $path);
	}

	/**
	 * @When I launch the search
	 */
	public function iLaunchTheSearch()
	{
		$this->getSession()->executeScript("var e = new Event('submit');document.getElementsByClassName('SearchFormBar')[0].dispatchEvent(e);");
		$this->getSession()->wait(5000, "jQuery('.contentDetails').children().length > 1");
	}

    /**
     * @Given I wait for autocomplete results
     */
    public function iWaitForAutocompleteResults()
    {
        $this->getSession()->wait(5000, "jQuery('.searchResults').children().length > 1");
    }

    /**
     * @When I click :button
     * @param $button
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function iClickShowCode($button)
    {
        $this->getSession()->executeScript(
            "$('#".$button."').click()"
        );
    }

    /**
     * @Then /^(?:|I )should see popup "(?P<div>[^"]*)"$/
     */
    public function popupElementOnPage($popup) {
        $element = $this->getSession()->getPage();
        $nodes = $element->findAll('css', '.source-code button');
        foreach ($nodes as $node) {
            if ($node->getAttribute('data-target') === $popup) {
                if ($node->isVisible()) {
                    return;
                }
                else {
                    throw new \Exception("Popup \"$popup\" not visible.");
                }
            }
        }
        throw new \Behat\Mink\Exception\ElementNotFoundException($this->getSession(), 'popup', 'button', $popup);
    }

	/**
	 * @When I test javascript
	 */
	public function iTestJavascript() {
		$title = $this->getSession()->executeScript("return 'string';");
		echo 'I\'m correctly on the webpage entitled "'.$title.'"';
	}
}
