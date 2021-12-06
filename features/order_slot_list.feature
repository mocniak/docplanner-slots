Feature:
    In order to make an doctor's appointment
    As a user
    I want to see doctors slots and be able to filter and sort them

    Background:
        Given today is "2020-02-01"
        # in random-ish order
        And doctor "Brave Ramanujan" has a 30 minute slot on "2020-02-04"
        And doctor "Adoring Shtern" has a 45 minute slot on "2020-02-02"
        And doctor "Brave Ramanujan" has a 15 minute slot on "2020-02-03"
        And doctor "Adoring Shtern" has a 20 minute slot on "2020-02-01"

    Scenario: Displaying all the slots from closest to latest available
        When I display slot list ordered by "closest_available"
        Then I see the page
        And I see 4 slots
        And the 1st result is doctor "Adoring Shtern"'s slot on "2020-02-01"
        And the 2nd result is doctor "Adoring Shtern"'s slot on "2020-02-02"
        And the 3rd result is doctor "Brave Ramanujan"'s slot on "2020-02-03"
        And the 4th result is doctor "Brave Ramanujan"'s slot on "2020-02-04"

    Scenario: Displaying all the slots from closest to latest available
        When I display slot list ordered by "duration"
        Then I see the page
        And I see 4 slots
        And the 1st result is doctor "Adoring Shtern"'s slot on "2020-02-02"
        And the 2nd result is doctor "Brave Ramanujan"'s slot on "2020-02-04"
        And the 3rd result is doctor "Adoring Shtern"'s slot on "2020-02-01"
        And the 4th result is doctor "Brave Ramanujan"'s slot on "2020-02-03"
