Feature: The admin page is visible

  Scenario: I can see the home page
    Given I am on "/en/login"
    Then I should see "Secure Sign in"

  Scenario: I can't login
    Given I am on "/en/login"
    And I fill in "username" with "admin"
    And I fill in "password" with "admin"
    And I press "submit-login"
    Then I should see "Invalid credentials."

  Scenario: I can login as admin
    Given I am on "/en/login"
    And I fill in "username" with "jane_admin"
    And I fill in "password" with "kitten"
    And I press "submit-login"
    Then I should see "Logout"

  Scenario: I can login as normal user
    Given I am on "/en/login"
    And I fill in "username" with "john_user"
    And I fill in "password" with "kitten"
    And I press "submit-login"
    Then I should see "Lorem ipsum"