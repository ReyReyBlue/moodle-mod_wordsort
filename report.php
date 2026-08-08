<?php
require('../../config.php');
require_once($CFG->libdir . '/gradelib.php');

$id = required_param('id', PARAM_INT);

$cm = get_coursemodule_from_id('wordsort', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$wordsort = $DB->get_record('wordsort', ['id' => $cm->instance], '*', MUST_EXIST);
$item = grade_item::fetch([
    'courseid' => $cm->course,
    'itemtype' => 'mod',
    'itemmodule' => 'wordsort',
    'iteminstance' => $wordsort->id,
    'itemnumber' => $cm->completiongradeitemnumber
]);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

require_capability('mod/wordsort:viewreports', $context);

$PAGE->set_url('/mod/wordsort/report.php', ['id' => $cm->id]);
$PAGE->set_context($context);
$PAGE->set_title(format_string($wordsort->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->requires->css('/mod/wordsort/styles.css');
$PAGE->requires->js('/mod/wordsort/module.js');

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('attemptsreport', 'mod_wordsort'));

$attempts = $DB->get_records(
    'wordsort_attempts',
    ['wordsortid' => $wordsort->id],
    'timecreated DESC'
);

$students = [];

//broken//

foreach ($attempts as $attempt) {

    if (!isset($students[$attempt->userid])) {

        $students[$attempt->userid] = [
            'user' => $DB->get_record('user', ['id' => $attempt->userid]),
            'bestattempt' => null,
            'latestattempt' => $attempt,
            'submittedattempts' => 0,
            'attempts' => [],
        ];
    }

    // Store every attempt.
    $students[$attempt->userid]['attempts'][] = $attempt;
        if ($attempt->status === 'submitted') {
        $students[$attempt->userid]['submittedattempts']++;
    }

    // Keep the highest submitted score.
    if ($attempt->status === 'submitted') {

        if (
            $students[$attempt->userid]['bestattempt'] === null ||
            $attempt->percentage >
            $students[$attempt->userid]['bestattempt']->percentage
        ) {

            $students[$attempt->userid]['bestattempt'] = $attempt;
        }
    }

    // Keep the newest attempt.
    if ($attempt->timecreated >
        $students[$attempt->userid]['latestattempt']->timecreated) {

        $students[$attempt->userid]['latestattempt'] = $attempt;
    }
}

$table = new html_table();

$table->head = [
    get_string('student', 'mod_wordsort'),
    get_string('bestscore', 'mod_wordsort'),
    get_string('grade', 'mod_wordsort'),
    get_string('attempts', 'mod_wordsort'),
    get_string('submitted', 'mod_wordsort'),
    get_string('details', 'mod_wordsort'),
];

echo html_writer::start_tag('table', [
    'class' => 'generaltable wordsort-report'
]);

echo html_writer::start_tag('thead');
echo html_writer::start_tag('tr');

foreach ($table->head as $heading) {
    echo html_writer::tag('th', $heading);
}

echo html_writer::end_tag('tr');
echo html_writer::end_tag('thead');

echo html_writer::start_tag('tbody');

    // We'll add the rows here next.
foreach ($students as $student) {

    $grade = '-';
    $bestscore = '-';

    if ($student['bestattempt']) {

        $bestscore = round($student['bestattempt']->percentage, 1) . '%';

        if ($item && $item->gradepass > 0) {
            $grade = $student['bestattempt']->percentage >= $item->gradepass
                ? get_string('passed', 'mod_wordsort')
                : get_string('notpassed', 'mod_wordsort');
        }
    }

    echo html_writer::start_tag('tr');

    echo html_writer::tag('td', fullname($student['user']));

    echo html_writer::tag('td', $bestscore);

    echo html_writer::tag('td', $grade);

    echo html_writer::tag(
        'td',
        $student['submittedattempts'] . '/' . $wordsort->maxattempts
    );

    echo html_writer::tag(
        'td',
        userdate($student['latestattempt']->timecreated)
    );

    echo html_writer::tag(
        'td',
        html_writer::tag(
            'button',
            $OUTPUT->pix_icon(
                't/collapsed',
                get_string('details', 'mod_wordsort')
            ),
            [
                'class' => 'btn btn-outline-secondary wordsort-details-button',
                'type' => 'button',
                'data-target' => 'details-' . $student['user']->id,
                'data-collapsed' => $OUTPUT->pix_icon(
                    't/collapsed',
                    get_string('details', 'mod_wordsort')
                ),
                'data-expanded' => $OUTPUT->pix_icon(
                    't/expanded',
                    get_string('details', 'mod_wordsort')
                ),
            ]
        )
    );

    echo html_writer::end_tag('tr');

$details = '';

$details .= html_writer::start_tag('table', [
    'class' => 'generaltable'
]);

$details .= html_writer::start_tag('thead');

$details .= html_writer::tag(
    'tr',
    html_writer::tag('th', get_string('attempt', 'mod_wordsort')) .
    html_writer::tag('th', get_string('status', 'mod_wordsort')) .
    html_writer::tag('th', get_string('score', 'mod_wordsort')) .
    html_writer::tag('th', get_string('submitted', 'mod_wordsort')) .
    html_writer::tag('th', get_string('review', 'mod_wordsort'))
);

$details .= html_writer::end_tag('thead');

$details .= html_writer::start_tag('tbody');

    foreach ($student['attempts'] as $attempt) {

        $details .= html_writer::start_tag('tr');

        $details .= html_writer::tag('td', $attempt->attempt);

        if ($attempt->status === 'submitted') {
            $status = $OUTPUT->pix_icon(
                'i/completion-manual-y',
                get_string('submitted', 'mod_wordsort')
            ) . ' ' . get_string('submitted', 'mod_wordsort');

        } else if ($attempt->status === 'inprogress') {
            $status = $OUTPUT->pix_icon(
                'i/completion-manual-n',
                get_string('statusinprogress', 'mod_wordsort')
            ) . ' ' . get_string('statusinprogress', 'mod_wordsort');

        } else if ($attempt->status === 'abandoned') {
            $status = $OUTPUT->pix_icon(
                'i/completion-auto-fail',
                get_string('statusabandoned', 'mod_wordsort')
            ) . ' ' . get_string('statusabandoned', 'mod_wordsort');
        }

        $details .= html_writer::tag('td', $status);

        $details .= html_writer::tag(
            'td',
            $attempt->score .
            ' / ' .
            $attempt->totalwords .
            ' (' .
            round($attempt->percentage, 1) .
            '%)'
        );

        $details .= html_writer::tag(
            'td',
            userdate($attempt->timecreated)
        );

        $details .= html_writer::tag(
            'td',
            html_writer::link(
                new moodle_url(
                    '/mod/wordsort/review.php',
                    [
                        'id' => $cm->id,
                        'attemptid' => $attempt->id,
                    ]
                ),
                get_string('review', 'mod_wordsort')
            )
        );

        $details .= html_writer::end_tag('tr');
    }

$details .= html_writer::end_tag('tbody');
$details .= html_writer::end_tag('table');

// Details row.
echo html_writer::start_tag('tr', [
    'class' => 'wordsort-details-row',
    'id' => 'details-' . $student['user']->id,
]);

        echo html_writer::tag(
            'td',
            $details,
            [
                'colspan' => 6
            ]
        );

echo html_writer::end_tag('tr');
}   // closes foreach ($students as $student)


echo html_writer::end_tag('tbody');
echo html_writer::end_tag('table');

echo $OUTPUT->footer();