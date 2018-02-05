Feature: The homepage is visible

  Scenario: I can see the title
    Given I am on "/"
    Then I should see "Welcome to the Symfony Demo application" in the "h1" element

  @javascript
  Scenario: Test javascript
    Given I go to "/en/blog"
    When I test javascript
    Then print current URL