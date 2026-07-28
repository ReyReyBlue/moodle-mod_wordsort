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

2 h

Shuffle setting works. Shuffles on every new attempt. No page refresh required. 
___________________________________________________________________________________________

Up Next: 

Attempt limits
Saving results to Moodle
Optional immediate feedback
Animations and polish


