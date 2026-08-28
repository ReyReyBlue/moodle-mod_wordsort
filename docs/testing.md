# Word Sort – Final Testing

## 1. Overview

This document describes the final functional and regression testing of the Word Sort Moodle activity.

Testing was performed after the main implementation was completed and after identified issues were corrected. The purpose of the testing was to verify that the activity works correctly for both students and teachers and that the main features operate as expected in the target Moodle environment.

## 2. Test Environment

Testing was performed in the real Moodle test environment used for acceptance testing.

The plugin was tested as a Moodle activity module:

- Plugin: Word Sort (`mod_wordsort`)
- Moodle version: Moodle 4.5
- Test type: Functional and regression testing
- Test environment: Real Moodle test environment

## 3. Test Results

A total of **43 test cases** were performed.

**Result: 43/43 tests passed.**

| Area | Tests | Passed | Failed |
|---|---:|---:|---:|
| Activity setup | 1–7 | 7 | 0 |
| Gameplay | 8–14 | 7 | 0 |
| Timer | 15–20 | 6 | 0 |
| Feedback | 21–24 | 4 | 0 |
| Attempts and submission | 25–30 | 6 | 0 |
| Teacher review and results | 31–43 | 13 | 0 |
| **Total** | **1–43** | **43** | **0** |

## 4. Activity Setup

The following functionality was tested:

1. Creating a Word Sort activity.
2. Entering both categories.
3. Validation when Category 1 is empty.
4. Validation when Category 2 is empty.
5. Adding words.
6. Editing words.
7. Deleting words.

**Result: 7/7 passed.**

## 5. Gameplay

The following functionality was tested:

8. Starting an activity.
9. Selecting the left category.
10. Selecting the right category.
11. Correct answer handling and score increase.
12. Incorrect answer handling.
13. Completing all words.
14. Shuffling words.

**Result: 7/7 passed.**

## 6. Timer

The following timing functionality was tested:

15. No timer.
16. Countdown timer.
17. Countdown warning state.
18. Countdown reaching zero.
19. Stopwatch.
20. Stopwatch warning/limit behaviour.

The timer warning state was also verified to change to red at the configured point.

**Result: 6/6 passed.**

## 7. Feedback

The following feedback functionality was tested:

21. Immediate feedback enabled.
22. Immediate feedback disabled.
23. Feedback displayed after completing a game.
24. Final submitted state when post-game feedback is disabled.

**Result: 4/4 passed.**

## 8. Attempts and Submission

The following functionality was tested:

25. Starting the first attempt.
26. Starting another attempt using Try Again.
27. Finishing an attempt while additional attempts remain.
28. Finishing the final allowed attempt.
29. Refreshing after submission.
30. Correct attempt count after submission.

Particular attention was given to the final submission behaviour.

After correction, pressing **Finish** displays the final submitted state immediately without requiring the student to refresh the page.

**Result: 6/6 passed.**

## 9. Teacher Review and Results

The following teacher-facing functionality was tested:

31. Opening the Review page.
32. Correct left-category answer.
33. Correct right-category answer.
34. Incorrect left/right answer combination.
35. Incorrect right/left answer combination.
36. Matching the number of correct icons with the numeric score.
37. Displaying the student's selected category.
38. Displaying the correct category.
39. Viewing student attempts in the teacher report.
40. Verifying scores in the report.
41. Verifying multiple attempts.
42. Verifying final submission status.
43. Opening and reviewing results from the teacher side.

**Result: 13/13 passed.**

## 10. Issues Identified During Final Testing

### 10.1 Final submission screen required a refresh

During testing, pressing **Finish** successfully saved the final submission, but the browser continued to display the previous start-screen state.

Refreshing the page caused the correct submitted state to appear.

The issue was caused by the server-side submission state being updated without updating the already-rendered start screen in the browser.

The JavaScript was updated so that, after a successful final submission, the existing start screen is immediately changed to display the submitted state.

The behaviour was then tested again successfully.

**Status: Fixed and verified.**

### 10.2 Review result icons displayed incorrectly

During testing, the numeric score was correct, but the result icons on the teacher review page did not correspond to whether the student's answer matched the correct category.

The problem was caused by treating the `correct` value as a Boolean. The value actually represents the correct category using the left/right identifiers (`0` or `1`).

The review logic was corrected to compare the student's selected category with the correct category.

The following combinations were verified:

| Correct category | Student answer | Result |
|---|---|---|
| Left | Left | Correct |
| Left | Right | Incorrect |
| Right | Left | Incorrect |
| Right | Right | Correct |

The review results were then tested again successfully, including verification that the number of correct icons matches the numeric score.

**Status: Fixed and verified.**

## 11. Final Result

All 43 final functional and regression tests passed in the target Moodle test environment.

The Word Sort activity was subsequently tested by the client/school in the real Moodle environment and confirmed to be working.

**Final test result: 43/43 passed.**