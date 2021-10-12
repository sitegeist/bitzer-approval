@fixtures
Feature: Schedule task

  As a user of Bitzer I want to be able to schedule an approval task

  Background:
    Given I have no content dimensions
    And I have the following NodeTypes configuration:
    """
    'unstructured': []
    'Neos.Neos:Document':
      properties:
        title:
          type: string
        uriPathSegment:
          type: string
    'Sitegeist.Bitzer:Testing.Document':
      superTypes:
        'Neos.Neos:Document': true

    """
    And I have the following workspaces:
      | Name   | Base Workspace |
      | live   |                |
      | review | live           |
    And I have the following nodes:
      | Identifier       | Path                                     | Node Type                         | Properties                                                       | Workspace |
      | sites            | /sites                                   | unstructured                      | {}                                                               | live      |
      | sity-mc-siteface | /sites/sity-mc-siteface                  | Neos.Neos:Document                | {}                                                               | live      |
      | nody-mc-nodeface | /sites/sity-mc-siteface/nody-mc-nodeface | Sitegeist.Bitzer:Testing.Document | {"title":"Nody McNodeface", "uriPathSegment":"nody-mc-nodeface"} | review    |
    And I have the following sites:
      | nodeName         | name | siteResourcesPackageKey |
      | sity-mc-siteface | Site | Sitegeist.TestSite      |
    And I have the following additional agents:
    """
    'Sitegeist.Bitzer:TestingAgent':
      parentRoles: ['Sitegeist.Bitzer:Agent']
    'Sitegeist.Bitzer:TestingAdministrator':
      parentRoles: ['Sitegeist.Bitzer:Administrator']
    """

  Scenario: Try to schedule an approval task without an object
    When the command ScheduleTask is executed with payload and exceptions are caught:
      | Key            | Value                                                               |
      | taskIdentifier | "tasky-mc-taskface"                                                 |
      | taskClassName  | "Sitegeist\\Bitzer\\Approval\\Domain\\Task\\Approval\\ApprovalTask" |
      | scheduledTime  | "2020-01-01T00:00:00+00:00"                                         |
      | agent          | "Sitegeist.Bitzer:TestingAgent"                                     |
      | properties     | {"description":"task description"}                                  |
    Then the last command should have thrown an exception of type "ObjectIsUndefined"

  Scenario: Try to schedule an approval task without an object using a constraint check result
    Given exceptions are collected in a constraint check result
    When the command ScheduleTask is executed with payload:
      | Key            | Value                                                               |
      | taskIdentifier | "tasky-mc-taskface"                                                 |
      | taskClassName  | "Sitegeist\\Bitzer\\Approval\\Domain\\Task\\Approval\\ApprovalTask" |
      | scheduledTime  | "2020-01-01T00:00:00+00:00"                                         |
      | agent          | "Sitegeist.Bitzer:TestingAgent"                                     |
      | properties     | {"description":"task description"}                                  |
    Then I expect the constraint check result to contain an exception of type "ObjectIsUndefined" at path "object"
    And I expect the task "tasky-mc-taskface" not to exist

  Scenario: Try to schedule an approval task with an object that no longer needs approval
    When the command ScheduleTask is executed with payload and exceptions are caught:
      | Key            | Value                                                                                            |
      | taskIdentifier | "tasky-mc-taskface"                                                                              |
      | taskClassName  | "Sitegeist\\Bitzer\\Approval\\Domain\\Task\\Approval\\ApprovalTask"                              |
      | scheduledTime  | "2020-01-01T00:00:00+00:00"                                                                      |
      | agent          | "Sitegeist.Bitzer:TestingAgent"                                                                  |
      | object         | {"nodeAggregateIdentifier":"sity-mc-siteface", "workspaceName":"live", "dimensionSpacePoint":{}} |
      | properties     | {"description":"task description"}                                                               |
    Then the last command should have thrown an exception of type "ObjectDoesNotExist"

  Scenario: Try to schedule an approval task with an object that no longer needs approval using a constraint check result
    Given exceptions are collected in a constraint check result
    When the command ScheduleTask is executed with payload:
      | Key            | Value                                                                                            |
      | taskIdentifier | "tasky-mc-taskface"                                                                              |
      | taskClassName  | "Sitegeist\\Bitzer\\Approval\\Domain\\Task\\Approval\\ApprovalTask"                              |
      | scheduledTime  | "2020-01-01T00:00:00+00:00"                                                                      |
      | agent          | "Sitegeist.Bitzer:TestingAgent"                                                                  |
      | object         | {"nodeAggregateIdentifier":"sity-mc-siteface", "workspaceName":"live", "dimensionSpacePoint":{}} |
      | properties     | {"description":"task description"}                                                               |
    Then I expect the constraint check result to contain an exception of type "ObjectDoesNotExist" at path "object"
    And I expect the task "tasky-mc-taskface" not to exist

  Scenario: Try to schedule an approval task with a manual target
    When the command ScheduleTask is executed with payload and exceptions are caught:
      | Key            | Value                                                                                              |
      | taskIdentifier | "tasky-mc-taskface"                                                                                |
      | taskClassName  | "Sitegeist\\Bitzer\\Approval\\Domain\\Task\\Approval\\ApprovalTask"                                |
      | scheduledTime  | "2020-01-01T00:00:00+00:00"                                                                        |
      | agent          | "Sitegeist.Bitzer:TestingAgent"                                                                    |
      | object         | {"nodeAggregateIdentifier":"nody-mc-nodeface", "workspaceName":"review", "dimensionSpacePoint":{}} |
      | target         | "https://www.neos.io"                                                                              |
      | properties     | {"description":"task description"}                                                                 |
    Then the last command should have thrown an exception of type "TargetIsInvalid"

  Scenario: Try to schedule an approval task with a manual target using a constraint check result
    Given exceptions are collected in a constraint check result
    When the command ScheduleTask is executed with payload:
      | Key            | Value                                                                                              |
      | taskIdentifier | "tasky-mc-taskface"                                                                                |
      | taskClassName  | "Sitegeist\\Bitzer\\Approval\\Domain\\Task\\Approval\\ApprovalTask"                                |
      | scheduledTime  | "2020-01-01T00:00:00+00:00"                                                                        |
      | agent          | "Sitegeist.Bitzer:TestingAgent"                                                                    |
      | object         | {"nodeAggregateIdentifier":"nody-mc-nodeface", "workspaceName":"review", "dimensionSpacePoint":{}} |
      | target         | "https://www.neos.io"                                                                              |
      | properties     | {"description":"task description"}                                                                 |
    Then I expect the constraint check result to contain an exception of type "TargetIsInvalid" at path "target"
    And I expect the task "tasky-mc-taskface" not to exist

  Scenario: Schedule an approval task
    Given I am authenticated as existing user "me"
    When the command ScheduleTask is executed with payload:
      | Key            | Value                                                                                              |
      | taskIdentifier | "tasky-mc-taskface"                                                                                |
      | taskClassName  | "Sitegeist\\Bitzer\\Approval\\Domain\\Task\\Approval\\ApprovalTask"                                |
      | scheduledTime  | "2020-01-02T00:00:00+00:00"                                                                        |
      | agent          | "Sitegeist.Bitzer:TestingAgent"                                                                    |
      | object         | {"nodeAggregateIdentifier":"nody-mc-nodeface", "workspaceName":"review", "dimensionSpacePoint":{}} |
      | properties     | {"description":"task description"}                                                                 |
    Then I expect the task "tasky-mc-taskface" to exist
    And I expect this task to be of class "Sitegeist\Bitzer\Approval\Domain\Task\Approval\ApprovalTask"
    And I expect this task to have action status "https://schema.org/PotentialActionStatus"
    And I expect this task to be scheduled to "2020-01-02T00:00:00+00:00"
    And I expect this task to be assigned to "Sitegeist.Bitzer:TestingAgent"
    And I expect this task to be about '{"nodeAggregateIdentifier":"nody-mc-nodeface", "workspaceName":"review", "dimensionSpacePoint":{}}'
    And I expect this task to have the adjusted target "http://localhost/neos/management/workspaces?moduleArguments[%40package]=neos.neos&moduleArguments[%40controller]=module\management\workspaces&moduleArguments[%40action]=show&moduleArguments[%40format]=html&moduleArguments[workspace][__identity]=review&moduleArguments[%40subpackage]="
    And I expect this task to have the properties:
      | Key         | Value            |
      | description | task description |
