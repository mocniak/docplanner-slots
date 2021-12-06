Feature:
    In order to make an doctor's appointment
    As a user
    I want to see doctors slots and be able to filter and sort them

    Scenario: Displaying all the slots when there's no filtering
        Given doctor "Adoring Shtern" has a slot on "2020-02-01"
        And doctor "Adoring Shtern" has a slot on "2020-02-01"
        And doctor "Brave Ramanujan" has a slot on "2020-02-01"
        And doctor "Brave Ramanujan" has a slot on "2020-02-01"
        When I display slot list
        Then I see the page
        And I see 4 slots

    Scenario: Displaying slots filtered by earliest date
        Given doctor "Adoring Shtern" has a slot on "2020-02-01"
        And doctor "Adoring Shtern" has a slot on "2020-02-02"
        And doctor "Brave Ramanujan" has a slot on "2020-02-03"
        And doctor "Brave Ramanujan" has a slot on "2020-02-04"
        When I display slot list with slots after "2020-02-02"
        Then I see the page
        And I see 3 slots
        And I see on the page that doctor "Adoring Shtern" has a slot on "2020-02-02"
        And I see on the page that doctor "Brave Ramanujan" has a slot on "2020-02-03"
        And I see on the page that doctor "Brave Ramanujan" has a slot on "2020-02-04"
