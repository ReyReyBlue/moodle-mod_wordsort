<?php
defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/mod/wordsort/backup/moodle2/restore_wordsort_stepslib.php');

/**
 * Defines the restore task for the Word Sort activity.
 *
 * @package    mod_wordsort
 * @copyright  2026 Reelika Pihl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Defines the restore task for mod_wordsort.
 */
class restore_wordsort_activity_task extends restore_activity_task {

    /**
     * Defines the restore settings.
     *
     * @return void
     */
    protected function define_my_settings() {
    }

    /**
     * Defines the restore steps.
     *
     * @return void
     */
    protected function define_my_steps() {
        $this->add_step(
            new restore_wordsort_activity_structure_step(
                'wordsort_structure',
                'wordsort'
            )
        );
    }

    /**
     * Defines how activity links are decoded during restore.
     *
     * @return array
     */
    public static function define_decode_contents() {
        $contents = [];

        $contents[] = new restore_decode_content(
            'wordsort',
            ['intro'],
            'wordsort'
        );

        return $contents;
    }

    /**
     * Defines how activity links are decoded during restore.
     *
     * @return array
     */
    public static function define_decode_rules() {
        $rules = [];

        $rules[] = new restore_decode_rule(
            'WORDSORT',
            '/mod/wordsort/view.php?id=$1',
            'course_module'
        );

        return $rules;
    }
}