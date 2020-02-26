# THlEd BUGTRACKER

## Motivation

### Why

This project should showcase my programming skills.
Therefore it has to be a topic other people in the IT industry can relate to.
Even better if it solves or at least helps to fight a business problem.
This is why I chose to build a bug tracker since every tech company struggles with bugs.
My software should help to organize and keep track of occurring bugs so that they can be fixed in time and do not get forgotten.

To meet my intention to showcase my programming skills, the software needs to fulfill the requirements described in the next section.

### Requirements

- Architectural Pattern: MVC (Model-View-Controller)
- clean UI: Bootstrap
- Database: relational DB
- Security: Authentication & Authorization
- external API: Auth0

### Similar Software

- Jira
- Github/Gitlab Issue Tracker
- Bugzilla

## Software Requirements Specification (SRS)

### Technology

- PHP 7.4
- Symfony 5.0
- Bootstrap 4.4
- PostgreSQL 12.1

### Views

- Log in
- List
  - Filters
    - Project
    - State
- Bug
- Account

### Actions

- Create Bug
- Find Bug
- Invite Participant
- Log out

### Elements of a Bug

- Meta
  - Bug ID
  - Project
    - Choose
  - (Version)
    - Choose or create
  - (Component)
    - Choose or create
  - Priority
    - High
    - Normal
    - Low
  - Due Date
  - Updated Date
  - Creation Date
- Content
  - Title
  - Summary
  - Steps to reproduce
  - Expected Result
  - Actual Result
  - (Media)
  - Comments
- Participants
  - Reporter
  - Assignee
- State
  - Status
    - Open
    - In Progress
    - Testing
    - Closed
- Actions
  - Delete

### Participants

- Roles
  - Admin
    - Invite Participants
  - Project Owner
    - Invite Developer
    - Create Projects
    - Create Bugs
  - Developer
    - Create Bugs
    - Assign to Bug

## Code Quality

### Software

- PHPUnit
- PHP_CodeSniffer
- PHPStan
- SonarCloud
