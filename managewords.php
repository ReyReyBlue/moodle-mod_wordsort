<?php

require('../../config.php');
require_once($CFG->dirroot . '/mod/wordsort/lib.php');

$id = required_param('id', PARAM_INT);

$cm = get_coursemodule_from_id('wordsort', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$wordsort = $DB->get_record('wordsort', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

$PAGE->set_url('/mod/wordsort/managewords.php', ['id' => $cm->id]);
$PAGE->set_context($context);
$PAGE->set_title(format_string($wordsort->name));
$PAGE->set_heading(format_string($course->fullname));

echo $OUTPUT->header();

echo $OUTPUT->heading('Manage words');

echo html_writer::tag('p', '<strong>Left category:</strong> ' . s($wordsort->categoryleft));
echo html_writer::tag('p', '<strong>Right category:</strong> ' . s($wordsort->categoryright));

$words = $DB->get_records(
    'wordsort_words',
    ['wordsortid' => $wordsort->id],
    'sortorder ASC, id ASC'
);

if (empty($words)) {
    echo '<p>No words have been added yet.</p>';
}
else {

    $table = new html_table();

    $table->head = [
        get_string('word', 'wordsort'),
        get_string('category', 'wordsort')
    ];

    foreach ($words as $word) {

        $category = ($word->correctside == 0)
            ? $wordsort->categoryleft
            : $wordsort->categoryright;

        $table->data[] = [
            format_string($word->word),
            format_string($category)
        ];
    }

    echo html_writer::table($table);
}

echo $OUTPUT->footer();