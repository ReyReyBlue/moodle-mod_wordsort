<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Library of interface functions and constants for module wordsort.
 *
 * @package    mod_wordsort
 * @copyright  2026 Reelika Pihl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('WORDSORT_NOTIMER', 0);
define('WORDSORT_COUNTDOWN', 1);
define('WORDSORT_STOPWATCH', 2);
define('WORDSORT_FEEDBACKNONE', 0);
define('WORDSORT_FEEDBACKEACHMOVE', 1);
define('WORDSORT_FEEDBACKSUBMIT', 2);

/**
 * Creates a new Word Sort activity.
 *
 * @param stdClass $data
 * @param mod_wordsort_mod_form|null $mform
 * @return int
 */
function wordsort_add_instance($data, $mform = null) {
    global $DB;

    $data->timecreated = time();
    $data->timemodified = time();

    $data->id = $DB->insert_record('wordsort', $data);

    wordsort_grade_item_update($data);

    return $data->id;
}

/**
 * Updates an existing Word Sort activity.
 *
 * @param stdClass $data
 * @param mod_wordsort_mod_form|null $mform
 * @return bool
 */

function wordsort_update_instance($data, $mform = null) {
    global $DB;

    $data->id = $data->instance;
    $data->timemodified = time();

    $DB->update_record('wordsort', $data);

    wordsort_grade_item_update($data);

    return true;
}

/**
 * Deletes a Word Sort activity.
 *
 * @param int $id
 * @return bool
 */
function wordsort_delete_instance($id) {
    global $DB;

    if (!$wordsort = $DB->get_record('wordsort', ['id' => $id])) {
        return false;
    }

    $DB->delete_records('wordsort', ['id' => $wordsort->id]);

    return true;
}

/**
 * Indicates which Moodle features Word Sort supports.
 *
 * @param string $feature
 * @return mixed
 */
function wordsort_supports($feature) {
    switch ($feature) {

        case FEATURE_MOD_INTRO:
            return true;

        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;

        case FEATURE_GRADE_HAS_GRADE:
            return true;

        default:
            return null;
    }
}

require_once($CFG->libdir . '/gradelib.php');

/**
 * Creates or updates the grade item.
 *
 * @param stdClass $wordsort
 * @return int
 */
function wordsort_grade_item_update($wordsort) {

    $gradeitem = [];

    $gradeitem['itemname'] = clean_param($wordsort->name, PARAM_NOTAGS);
    $gradeitem['gradetype'] = GRADE_TYPE_VALUE;
    $gradeitem['grademax'] = 100;
    $gradeitem['grademin'] = 0;

    return grade_update(
        'mod/wordsort',
        $wordsort->course,
        'mod',
        'wordsort',
        $wordsort->id,
        0,
        null,
        $gradeitem
    );
}

/**
 * Updates a student's grade in the gradebook.
 *
 * @param stdClass $wordsort
 * @param int $userid
 * @param float $grade
 * @return int
 */
function wordsort_update_grades($wordsort, $userid, $grade) {

    $grades = [];

    $student = new stdClass();
    $student->userid = $userid;
    $student->rawgrade = $grade;

    $grades[$userid] = $student;

    return grade_update(
        'mod/wordsort',
        $wordsort->course,
        'mod',
        'wordsort',
        $wordsort->id,
        0,
        $grades
    );
}