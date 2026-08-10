16 July 2026

2.5 h

Created the initial Moodle activity module structure. 
Implemented the first plugin files.
Created the English language pack.
Prepared project documentation.
Verified plugin detection through Moodle's plugin management interface.
Result

Moodle successfully detected the custom Word Sort activity module as an installable plugin.
___________________________________________________________________________________________

17 July 2026

3 h

Created written and on paper visualitation how things would look like and thinking what parameters are needed.
___________________________________________________________________________________________

20 July 2026

4,5 h

Moodle installed the first version of my plugin. Made first commit in github and invited my teacher and the instructor, so they can see my work. Comment, if needed. Readme file is only, that is in Estonian - my head hurts if i have to think and code in multible language.

2 h

Complete activity settings form (branch: feature/mod-form-settings)
___________________________________________________________________________________________

21 July 2026

5,5 h

Redesigned activity settings stored in the database. Timing settings. Maximum attempts. Shuffle words option. Feedback mode. Left and right category fields. Validation for required categories. Validation that categories cannot be the same (ignoring case and surrounding spaces). Activity installs and saves correctly.

4 h 

Everything related to creating, editing, deleting and ordering the words that belong to a Word Sort activity.
___________________________________________________________________________________________

22 July 2026

10 h

Added a "Add word" button, actions column, edit button works. Up/down buttons and functions. Bulk add words

started feature/student-interface, found an error in teacher interface.
___________________________________________________________________________________________

23 July 2026

10 h

Teacher toolbar added: Teacher-only toolbar displayed on the activity page. Direct access to Add word, Bulk add, Manage words, and Edit settings. Uses standard Moodle buttons and capability checks. Integrated into view.php without affecting the student view.
Start screen

Activity screen, connecting moodle with AMD module. 
___________________________________________________________________________________________

24 July 2026

10h

I moved the gameplay logic from PHP to JavaScript. Passed all words from PHP to the AMD module. Implemented the game loop. Implemented answer checking. Implemented score tracking. Finished the first complete playable version. Tracked down and fixed the scoring issue.
___________________________________________________________________________________________

25 July 2026

10h

Refactored the game flow into clear functions. Added a dedicated results screen. Moved the layout into view.php instead of building HTML in JavaScript. Implemented a working stopwatch lifecycle (start → stop → display). Cleaned up the DOM handling by storing element references.
___________________________________________________________________________________________

26 July 2026

3 h 

We can see now multible attempts.
___________________________________________________________________________________________

27 July 2026

10 h

Activity readiness check in view.php. Teacher guidance when no words exist. Preview Activity button in managewords.php. Attempt progress (1 / 5) on the results screen. Best score on the results screen. Improve manage words page layout and teacher workflow. Extra 2h for trying to move out of localhost to view my moodple paige on an better computer.
___________________________________________________________________________________________

28 July 2026

12h

Shuffle setting works. Shuffles on every new attempt. No page refresh required. Feedback after every choice is nor available. A lot of time went to debugging to find and implement the JavaScript logic to display feedback under the given words.
___________________________________________________________________________________________

29 July 2026

10 h 

Implement the Submit action. Create the submission summary/report. Review answers after submission → detailed report only after submitting.
___________________________________________________________________________________________

30 July 2026

4 h

Improved submission review layout. Feedback works. Respect the teacher's Feedback mode setting: No feedback → no per-word review.

___________________________________________________________________________________________

31 July 2026

10 h

Implement attempt saving via AJAX web service
___________________________________________________________________________________________

1 August 2026

4 h

Teacher report permissions. Teacher report page that lists every attempt. Gradebook integration
___________________________________________________________________________________________

2 August 2026

8 h

Added status field for attempt lifecycle. Create attempts on start and update on submission
___________________________________________________________________________________________

3 August 2026

10 h

Review page is READY.
___________________________________________________________________________________________

4 August 2026

11 h

Report page valmis. Esines palju probleeme ja jamasid, et moodle gradebookist hinne "passed" saada.
___________________________________________________________________________________________

5 August 2026

10 h

Import/export - for easy re-using already added words/categories. Export works, import reads file, but is not complete and ready 
___________________________________________________________________________________________

6 August 2026

8 h

Import - is working and takes in .csv files that are exported from wordsort module. Starting clean-up and polishes. Ended up finding an bigger issue and will deal with it with a clear mind tomorrow.  
___________________________________________________________________________________________

7 August 2026

9 h

Check text limit on title screen (done), Edit setting page is nicer to look at. Improve attempt counter on the start screen. Prevent starting when no attempts remain (done).Testing import and fixing problem.
___________________________________________________________________________________________

8 August 2026

7 h + 5 h (troubleshooting and testing/repairing)

Testing: Found that the activity did not have a "lock" screen after submitting and counting the attempts was not working as intended. Testing and fixing the "try again" logic and "finish" button logic.
___________________________________________________________________________________________

10 August 2026

4 h

Testing. 
_ - _ - _ - _ - _ - _ - _ - _ - _ - _ - _ - _ - _ - _
1. No feedback — normal completion + Try again

Feedback: No feedback   ✅
Max attempts: 3 or 5    ✅
Timer: none             ✅ 

Test A — complete game → Try again
Start attempt 1.
    Play all words.             ✅  
    On Result screen, verify:
        Score is correct.       ✅
        Best score is correct.  ✅
        Try again is visible.   ✅
        Finish is visible.      ✅
    Press Try again.
Verify you return to the Start screen.
    Verify:
        1/3 (or 1/5)                -/✅
        Start button is visible     ✅
        No lock message.            ✅

Teacher view
    While you are still on the Start screen:
        Attempt 1    Submitted          ✅
    Then press Start.
        Teacher should now see:
            Attempt 2    In progress    ✅
            Attempt 1    Submitted      ✅

This is an important test because it proves that submitted ≠ final.

2. No feedback — Finish

Continue with attempt 2.
    Complete the game.                                      
        Press Finish.                                                           ✅
            No Submission summary should appear.    
            You should immediately see the final screen:                        -/✅
                You have tried: 2/3 times.                                      -/✅
                Your results have been sent to your teacher. Have a nice day!   -/✅

Leave the activity.
    Open it again.
        It must still show the final locked screen.     ✅

Teacher view
    You should see:
        Attempt 2    Submitted    [score]       ✅
        Attempt 1    Submitted    [score]       ✅

And no new attempt should be created just by reopening the activity. ✅

Results (1&2) - Start-screen transition occasionally displays the previous screen briefly before updating; final state is correct after refresh.

3. partA: Feedback enabled (after each move) — Finish

Complete an attempt and press Finish.   
Expected:
        Result   ✅
       ↓
        Finish  ✅

The student should be able to see the answers during this session. ✅

3. partB: Feedback enabled (after full test) — Finish

Complete an attempt and press Finish.   
Expected:
        Finish
        ↓
        Submission summary / Review ✅

The review should not become a permanent way to view the answers. The final locked screen should be what the student gets after reopening. ✅

4. Feedback enabled — Try again - Finish

Complete an attempt.

Press:
    Try again

Expected:
    Attempt 1 → Submitted   ✅
            ↓
    Start screen            ✅
            ↓
    Start                   ✅
            ↓
    Attempt 2 → In progress ✅

It should not lock merely because the previous attempt was submitted.

Final result is presented to student after pressing finish and not after try again. Sucess ✅ 

5. Maximum attempts

Max attempts = 3

Then:

Attempt 1 → Try again   ✅
Attempt 2 → Try again   ✅
Attempt 3 → Finish      ✅

After Finish:

You have tried: 3/3 times. ✅
Your results have been sent to your teacher...

6. Refresh during an active attempt

Max attempts something like 5.                          ✅
Start an attempt.                                       ✅
Answer a few words, but do not finish.                  ✅
Refresh the browser while the game is still active.     ✅
Observe where the student lands.                        ✅
Check the teacher report before pressing Start again.   ✅

Expected teacher status:
                    In progress     ✅
Not Abandoned, not Submitted.

Then student:
                    Press Start.    ✅

Because the previous attempt was genuinely unfinished, the old attempt should become Abandoned.
A new attempt should become In progress.

Teacher should then see:
        Attempt 2 — In progress     ✅
        Attempt 1 — Abandoned       ✅

___________________________________________________________________________________________

10 August 2026

- h

reviewing and blendind the moodle language string with current plugin.
___________________________________________________________________________________________


