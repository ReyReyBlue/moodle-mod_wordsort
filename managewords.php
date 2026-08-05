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

/*
 * URLs
 */
$addwordurl = new moodle_url('/mod/wordsort/editword.php', [
    'id' => $cm->id
]);

$bulkaddurl = new moodle_url('/mod/wordsort/bulkadd.php', [
    'id' => $cm->id
]);

$previewurl = new moodle_url('/mod/wordsort/view.php', [
    'id' => $cm->id
]);

/*
 * Words
 */
$words = $DB->get_records(
    'wordsort_words',
    ['wordsortid' => $wordsort->id],
    'sortorder ASC, id ASC'
);

$maxsortorder = (int)$DB->get_field_sql(
    "SELECT MAX(sortorder)
       FROM {wordsort_words}
      WHERE wordsortid = ?",
    [$wordsort->id]
);

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('managewords', 'wordsort'));

//
// Categories
//
echo $OUTPUT->heading(get_string('categories', 'mod_wordsort'), 3);

echo html_writer::tag(
    'p',
    get_string('leftcategory', 'mod_wordsort') .
    ': <strong>' . s($wordsort->categoryleft) . '</strong>'
);

echo html_writer::tag(
    'p',
    get_string('rightcategory', 'mod_wordsort') .
    ': <strong>' . s($wordsort->categoryright) . '</strong>'
);

echo html_writer::empty_tag('hr');

//
// Words
//
echo $OUTPUT->heading(get_string('words', 'mod_wordsort'), 3);

if (empty($words)) {

    echo html_writer::tag(
        'p',
        get_string('nowordsadded', 'mod_wordsort')
    );

    echo html_writer::tag(
        'p',
        get_string('addfirstword', 'mod_wordsort')
    );

    echo $OUTPUT->single_button(
        $addwordurl,
        get_string('addword', 'wordsort'),
        'get'
    );

    echo $OUTPUT->single_button(
        $bulkaddurl,
        get_string('bulkaddwords', 'wordsort'),
        'get'
    );

} else {

    echo html_writer::link(
        new moodle_url('/mod/wordsort/editword.php', ['id' => $cm->id]),
        get_string('addword', 'mod_wordsort'),
        ['class' => 'btn btn-secondary me-3 mb-3']
    );

    echo html_writer::link(
        new moodle_url('/mod/wordsort/bulkadd.php', ['id' => $cm->id]),
        get_string('bulkaddwords', 'mod_wordsort'),
        ['class' => 'btn btn-secondary me-3 mb-3']
    );

    echo html_writer::link(
        new moodle_url('/mod/wordsort/export.php', ['id' => $cm->id]),
        get_string('exportwords', 'mod_wordsort'),
        ['class' => 'btn btn-secondary me-3 mb-3']
    );

    echo html_writer::link(
        new moodle_url('/mod/wordsort/import.php', ['id' => $cm->id]),
        get_string('importwords', 'mod_wordsort'),
        ['class' => 'btn btn-secondary me-3 mb-3']
    );

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

    echo html_writer::empty_tag('hr');

    echo $OUTPUT->heading(
        get_string('nextstep', 'mod_wordsort'),
        3
    );

    echo html_writer::div(
        get_string('previewactivitydesc', 'mod_wordsort'),
        'mb-3'
    );

    echo html_writer::link(
        $previewurl,
        get_string('previewactivity', 'mod_wordsort'),
        ['class' => 'btn btn-primary']
    );
}

echo $OUTPUT->footer();