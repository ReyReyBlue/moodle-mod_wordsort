<?php

require('../../config.php');

$id = required_param('id', PARAM_INT);

$cm = get_coursemodule_from_id('wordsort', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$wordsort = $DB->get_record('wordsort', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);
require_capability('mod/wordsort:viewreports', $context);

const COL_LEFTCATEGORY  = 0;
const COL_RIGHTCATEGORY = 1;
const COL_WORD          = 2;
const COL_CORRECT       = 3;

$words = $DB->get_records(
    'wordsort_words',
    ['wordsortid' => $wordsort->id],
    'sortorder ASC, id ASC'
);

$filename = clean_filename(
    'wordsort_' .
    $wordsort->categoryleft . '_' .
    $wordsort->categoryright
) . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

fputcsv($output, [
    'Left category',
    'Right category',
    'Word',
    'Correct'
]);

foreach ($words as $word) {

    $correct = ($word->correctside == 0)
        ? $wordsort->categoryleft
        : $wordsort->categoryright;

    fputcsv($output, [
        $wordsort->categoryleft,
        $wordsort->categoryright,
        $word->word,
        $correct
    ]);
}