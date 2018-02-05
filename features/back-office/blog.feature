Feature: The blog page is visible

  Scenario: I can see the home page
    Given I am on "/en/blog"
    Then I should see "This is a demo application"
