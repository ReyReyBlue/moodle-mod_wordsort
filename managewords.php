<?php

require('../../config.php');
require_once($CFG->dirroot . '/mod/wordsort/lib.php');

$id = optional_param('id', 0, PARAM_INT);

if (!$id) {
    throw new moodle_exception('missingparam', '', '', 'id');
}

$wordid = optional_param('wordid', 0, PARAM_INT);

$cm = get_coursemodule_from_id('wordsort', $id, 0, false, MUST_EXIST);
$wordrecord = null;

$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$wordsort = $DB->get_record('wordsort', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

$PAGE->set_url('/mod/wordsort/managewords.php', ['id' => $cm->id]);
$PAGE->set_context($context);
$PAGE->set_title(format_string($wordsort->name));
$PAGE->set_heading(format_string($course->fullname));

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('managewords', 'wordsort'));

$addwordurl = new moodle_url('/mod/wordsort/editword.php', [
    'id' => $cm->id
]);

echo $OUTPUT->single_button(
    $addwordurl,
    get_string('addword', 'wordsort'),
    'get'
);

echo html_writer::tag('p', '<strong>Left category:</strong> ' . s($wordsort->categoryleft));
echo html_writer::tag('p', '<strong>Right category:</strong> ' . s($wordsort->categoryright));

$words = $DB->get_records(
    'wordsort_words',
    ['wordsortid' => $wordsort->id],
    'sortorder ASC, id ASC'
);

$maxsortorder = $DB->get_field_sql(
    "SELECT MAX(sortorder)
       FROM {wordsort_words}
      WHERE wordsortid = ?",
    [$wordsort->id]
);

if (empty($words)) {
    echo '<p>No words have been added yet.</p>';
}
else {

    $table = new html_table();

    $table->head = [
    get_string('word', 'wordsort'),
    get_string('category', 'wordsort'),
    get_string('actions')
];

    foreach ($words as $word) {

    $editurl = new moodle_url('/mod/wordsort/editword.php', [
        'id' => $cm->id,
        'wordid' => $word->id
    ]);

    $deleteurl = new moodle_url('/mod/wordsort/deleteword.php', [
        'id' => $cm->id,
        'wordid' => $word->id
    ]);

    $moveupurl = new moodle_url('/mod/wordsort/moveup.php', [
        'id' => $cm->id,
        'wordid' => $word->id
    ]);

    $movedownurl = new moodle_url('/mod/wordsort/movedown.php', [
        'id' => $cm->id,
        'wordid' => $word->id
    ]);

$actions = '';

$actions .= $OUTPUT->action_icon(
    $editurl,
    new pix_icon('t/edit', get_string('edit'))
);

$actions .= $OUTPUT->action_icon(
    $deleteurl,
    new pix_icon('t/delete', get_string('delete'))
);

if ($word->sortorder > 0) {
    $actions .= $OUTPUT->action_icon(
        $moveupurl,
        new pix_icon('t/up', get_string('moveup', 'wordsort'))
    );
}

if ($word->sortorder < $maxsortorder) {
    $actions .= $OUTPUT->action_icon(
        $movedownurl,
        new pix_icon('t/down', get_string('movedown', 'wordsort'))
    );
}

        $category = ($word->correctside == 0)
            ? $wordsort->categoryleft
            : $wordsort->categoryright;

        $table->data[] = [
            format_string($word->word),
            format_string($category),
            $actions
        ];
    }

    echo html_writer::table($table);
}

echo $OUTPUT->footer();