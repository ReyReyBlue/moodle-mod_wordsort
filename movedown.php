<?php

require('../../config.php');

$id = required_param('id', PARAM_INT);
$wordid = required_param('wordid', PARAM_INT);

$cm = get_coursemodule_from_id('wordsort', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$wordsort = $DB->get_record('wordsort', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

$PAGE->set_url('/mod/wordsort/movedown.php', [
    'id' => $cm->id,
    'wordid' => $wordid
]);

$PAGE->set_context($context);

$currentword = $DB->get_record(
    'wordsort_words',
    [
        'id' => $wordid,
        'wordsortid' => $wordsort->id
    ],
    '*',
    MUST_EXIST
);

$nextword = $DB->get_record_sql(
    "SELECT *
       FROM {wordsort_words}
      WHERE wordsortid = ?
        AND sortorder > ?
   ORDER BY sortorder ASC
      LIMIT 1",
    [
        $wordsort->id,
        $currentword->sortorder
    ]
);

if (!$nextword) {
    redirect(new moodle_url('/mod/wordsort/managewords.php', [
        'id' => $cm->id
    ]));
}

$temp = $currentword->sortorder;

$currentword->sortorder = $nextword->sortorder;
$nextword->sortorder = $temp;

$DB->update_record('wordsort_words', $currentword);
$DB->update_record('wordsort_words', $nextword);

redirect(new moodle_url('/mod/wordsort/managewords.php', [
    'id' => $cm->id
]));