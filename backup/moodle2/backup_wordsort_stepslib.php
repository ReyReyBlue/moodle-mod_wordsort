<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Defines the backup structure for the Word Sort activity.
 *
 * @package    mod_wordsort
 * @copyright  2026 Reelika Pihl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Defines the backup structure for mod_wordsort.
 */
class backup_wordsort_activity_structure_step extends backup_activity_structure_step {

    /**
     * Defines the structure to be backed up.
     *
     * @return backup_nested_element
     */
    protected function define_structure() {

        $wordsort = new backup_nested_element(
            'wordsort',
            ['id'],
            [
                'name',
                'intro',
                'introformat',
                'categoryleft',
                'categoryright',
                'timingmode',
                'timevalue',
                'maxattempts',
                'shufflewords',
                'feedbackmode',
                'timecreated',
                'timemodified',
                'finalsubmission',
            ]
        );

        $words = new backup_nested_element(
            'words'
        );

        $word = new backup_nested_element(
            'word',
            ['id'],
            [
                'word',
                'correctside',
                'sortorder',
            ]
        );

        $wordsort->add_child($words);
        $words->add_child($word);

        $wordsort->set_source_table(
            'wordsort',
            ['id' => backup::VAR_ACTIVITYID]
        );

        $word->set_source_table(
            'wordsort_words',
            ['wordsortid' => backup::VAR_PARENTID],
            'id ASC'
        );

        return $this->prepare_activity_structure($wordsort);
    }
}