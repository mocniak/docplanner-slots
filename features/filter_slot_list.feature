Feature:
    In order to make an doctor's appointment
    As a user
    I want to see doctors slots and be able to filter and sort them

    Background:
        Given doctor "Adoring Shtern" has a slot on "2020-02-01"
        And doctor "Adoring Shtern" has a slot on "2020-02-02"
        And doctor "Brave Ramanujan" has a slot on "2020-02-03"
        And doctor "Brave Ramanujan" has a slot on "2020-02-04"

    Scenario: Displaying all the slots when there's no filtering
        When I display slot list
        Then I see the page
        And I see 4 slots

    Scenario: Displaying slots which are after a given date
        When I display slot list with slots after "2020-02-02"
        Then I see the page
        And I see 3 slots
        And I see on the page that doctor "Adoring Shtern" has a slot on "2020-02-02"
        And I see on the page that doctor "Brave Ramanujan" has a slot on "2020-02-03"
        And I see on the page that doctor "Brave Ramanujan" has a slot on "2020-02-04"

    Scenario: Displaying slots which are before a given date
        When I display slot list with slots before "2020-02-03"
        Then I see the page
        And I see 3 slots
        And I see on the page that doctor "Adoring Shtern" has a slot on "2020-02-01"
        And I see on the page that doctor "Adoring Shtern" has a slot on "2020-02-02"
        And I see on the page that doctor "Brave Ramanujan" has a slot on "2020-02-03"

    Scenario: Displaying slots which are after and before given dates
        When I display slot list with slots after "2020-02-02" and before "2020-02-03"
        Then I see the page
        And I see 2 slots
        And I see on the page that doctor "Adoring Shtern" has a slot on "2020-02-02"
        And I see on the page that doctor "Brave Ramanujan" has a slot on "2020-02-03"

    Scenario: Displaying slots which are on one day
        When I display slot list with slots after "2020-02-03" and before "2020-02-03"
        Then I see the page
        And I see 1 slot
        And I see on the page that doctor "Brave Ramanujan" has a slot on "2020-02-03"

    Scenario: Displaying no slots when they are none between given dates
        When I display slot list with slots after "2020-02-05" and before "2020-02-06"
        Then I see the page
        And I see 0 slots
