<?php
defined('MOODLE_INTERNAL') || die();

// Plugin specific information.

$string['pluginname'] = 'Word Sort';
$string['modulename'] = 'Word Sort';
$string['modulenameplural'] = 'Word Sort activities';
$string['pluginadministration'] = 'Word Sort administration';
$string['activitysetup'] = 'Word Sort setup';
$string['wordsort:viewreports'] = 'View Word Sort reports';
$string['exportwords'] = 'Export';
$string['importwords'] = 'Import';
$string['csvfile'] = 'CSV file';

// Activity settings.
$string['activityoptions'] = 'Activity options';
$string['shufflewords'] = 'Shuffle words';
$string['moveup'] = 'Move up';
$string['movedown'] = 'Move down';
$string['start'] = 'Start';
$string['teachertools'] = 'Teacher tools';
$string['backtoreport'] = 'Back to report';

// Word
$string['word'] = 'Word';
$string['words'] = 'Words';
$string['addword'] = 'Add word';
$string['wordsaved'] = 'Word saved successfully.';
$string['managewords'] = 'Manage words';
$string['deleteword'] = 'Delete word';
$string['confirmdeleteword'] = 'Are you sure you want to delete the word "{$a}"?';
$string['worddeleted'] = 'Word deleted.';
$string['bulkaddwords'] = 'Bulk add';
$string['bulkwordsadded'] = '{$a} words were added.';

// Attempts
$string['attempt'] = 'Attempt';
$string['maxattempts'] = 'Maximum attempts';
$string['attemptsettings'] = 'Attempts';
$string['attempts'] = 'Attempts';
$string['attemptsused'] = 'Attempts used';
$string['bestattempt'] = 'Best attempt';
$string['viewattempts'] = 'View attempts';
$string['attemptsreport'] = 'Student attempts report';

// Feedback
$string['feedbackmode'] = 'Feedback mode';
$string['feedbackeachmove'] = 'After each move';
$string['feedbacksubmit'] = 'After submission';
$string['feedbacknone'] = 'No feedback';

// Categories
$string['categoryleft'] = 'Left category';
$string['categoryright'] = 'Right category';
$string['errorcategoryleftrequired'] = 'Left category is required.';
$string['errorcategoryrightrequired'] = 'Right category is required.';
$string['errorcategoriesequal'] = 'Left and right categories must be different.';
$string['category'] = 'Category';
$string['categories'] = 'Categories';
$string['leftcategory'] = 'Left category';
$string['rightcategory'] = 'Right category';
$string['correctcategory'] = 'Correct category';

// Timing.
$string['timingsettings'] = 'Timing';
$string['timingmode'] = 'Timing mode';
$string['notimer'] = 'No timer';
$string['countdown'] = 'Countdown';
$string['stopwatch'] = 'Stopwatch';
$string['timevalue'] = 'Time limit / Target time (seconds)';
$string['timelimitlabel'] = 'Time limit';

// Results and review.
$string['finished'] = 'Finished';
$string['tryagain'] = 'Try again';
$string['submit'] = 'Finish';
$string['score'] = 'Score';
$string['submissionsummary'] = 'Performance summary';
$string['bestscore'] = 'Best score';
$string['youranswer'] = 'Your answer';
$string['correctanswer'] = 'Correct answer';
$string['status'] = 'Status';
$string['statusinprogress'] = 'In progress';
$string['statusabandoned'] = 'Abandoned';
$string['student'] = 'Student';
$string['percentage'] = 'Percentage';
$string['submitted'] = 'Submitted';
$string['review'] = 'Review';
$string['viewanswers'] = 'View answers';
$string['attemptreviewtitle'] = 'Attempt {$a}';
$string['attemptreview'] = 'Attempt review';
$string['studentanswer'] = 'Student answer';
$string['result'] = 'Result';
$string['correct'] = 'Correct';
$string['incorrect'] = 'Incorrect';
$string['passed'] = 'Passed';
$string['notpassed'] = 'Not passed';
$string['details'] = 'Details';
$string['grade'] = 'Grade';

// Status messages.
$string['nowordsadded'] = 'No words have been added yet.';
$string['previewactivity'] = 'Preview activity';
$string['previewactivitydesc'] = 'Preview the activity exactly as your students will see it.';
$string['activitysetupdesc'] = 'This activity is not ready for students yet. Complete the remaining setup steps below.';
$string['categoriesconfigured'] = 'Categories configured';
$string['wordsnotadded'] = 
    'Words have not been added yet
    
    Please add the words so that the students can do this activity.';
$string['activitysetupnext'] = 'Next step: add at least one word.';
$string['nextstep'] = 'Next step';
$string['addfirstword'] = 'Add your first word to begin building the activity.';
$string['invalidcategory'] = 'Invalid category "{$a}" found in the CSV file.';
$string['invalidcsv'] = 'Invalid CSV file.';
$string['cannotreadcsv'] = 'Could not read the uploaded CSV file.';
$string['importsuccess'] = 'Words imported successfully.';
$string['importsummary'] = 'Imported {$a->imported} words. Skipped {$a->skipped} duplicates.';
$string['nomoreattempts'] = 'You have used all available attempts.';
$string['categorymismatch'] = 'Import failed. The CSV categories do not match the categories configured for this activity.';
$string['activitysubmitted'] = 'Your results have been sent to your teacher. Have a nice day!';
$string['attemptsused'] = 'You have tried: {$a->used}/{$a->max} times.';
// End of file.