Feature:
    In order to have data allowing to show doctors' appointment slots
    As a admin
    I want to have import working

    Scenario: It imports all the data from supplier API
        Given in the supplier API there is a doctor "Adoring Shtern"
        And in the supplier API there is a doctor "Adoring Shtern"
        And the API says that doctor "Adoring Shtern" has a slot on "2020-02-01" from "15:00" to "15:30"
        And the API says that doctor "Adoring Shtern" has a slot on "2020-02-01" from "15:30" to "16:00"
        And the API says that doctor "Brave Ramanujan" has a slot on "2020-02-01" from "14:00" to "14:30"
        And the API says that doctor "Brave Ramanujan" has a slot on "2020-02-01" from "14:30" to "15:00"
        When I import slots from the supplier
        And I see that doctor "Adoring Shtern" has a slot on "2020-02-01" from "15:00" to "15:30"
        And I see that doctor "Adoring Shtern" has a slot on "2020-02-01" from "15:30" to "16:00"
        And I see that doctor "Brave Ramanujan" has a slot on "2020-02-01" from "14:00" to "14:30"
        And I see that doctor "Brave Ramanujan" has a slot on "2020-02-01" from "14:30" to "15:00"
