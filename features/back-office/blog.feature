Feature: The blog page is visible

  Scenario: I can see the home page
    Given I am on "/en/blog"
    Then I should see "This is a demo application"

    Given I am on "/fr/blog"
    Then I should see "Ceci est une application de démonstration"

    Given I am on "/en/blog"
    And I follow "Symfony doc"
    Then I should be on "https://symfony.com/doc"

    Given I am on "/en/blog"
    And I click "showcode"
    Then I should see popup "#sourceCodeModal"

