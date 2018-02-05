Feature: The homepage is visible

  Scenario: I can see the home page
    Given I am on "/"
    Then I should see "Symfony demo"
