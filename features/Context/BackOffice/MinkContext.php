<?php

namespace Context\BackOffice;

use Behat\Behat\Hook\Scope\AfterStepScope;
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
}
