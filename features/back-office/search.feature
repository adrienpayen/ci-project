Feature: The search working

  @javascript
  Scenario: I can search an article
    Given I am on "/en/blog/search"
    When I fill in "Search for..." with "lorem"
    And I wait for autocomplete results
    Then I should see "Lorem Ipsum"