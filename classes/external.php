<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License.
//
// @package    mod_wordsort
// @copyright 2026 Reelika Pihl
// @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

namespace mod_wordsort;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/mod/wordsort/lib.php');

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;

/**
 * External functions for Word Sort.
 */
class external extends external_api {

    /**
     * Parameters for save_attempt().
     *
     * @return external_function_parameters
     */
    public static function save_attempt_parameters() {
        return new external_function_parameters([
            'wordsortid' => new external_value(PARAM_INT, 'Word Sort activity ID'),
            'score' => new external_value(PARAM_INT, 'Student score'),
            'totalwords' => new external_value(PARAM_INT, 'Total number of words'),
            'percentage' => new external_value(PARAM_FLOAT, 'Percentage score'),
            'timeused' => new external_value(PARAM_INT, 'Time used in seconds'),
            'attempt' => new external_value(PARAM_INT, 'Attempt number'),
        ]);
    }

    /**
     * Save a Word Sort attempt.
     *
     * @param int $wordsortid
     * @param int $score
     * @param int $totalwords
     * @param float $percentage
     * @param int $timeused
     * @param int $attempt
     * @return array
     */
    public static function save_attempt(
        int $wordsortid,
        int $score,
        int $totalwords,
        float $percentage,
        int $timeused,
        int $attempt
    ) {
        global $DB, $USER;

        $params = self::validate_parameters(
            self::save_attempt_parameters(),
            [
                'wordsortid' => $wordsortid,
                'score' => $score,
                'totalwords' => $totalwords,
                'percentage' => $percentage,
                'timeused' => $timeused,
                'attempt' => $attempt,
            ]
        );

        $record = new \stdClass();
        $record->wordsortid = $params['wordsortid'];
        $record->userid = $USER->id;
        $record->attempt = $params['attempt'];
        $record->score = $params['score'];
        $record->totalwords = $params['totalwords'];
        $record->percentage = $params['percentage'];
        $record->timeused = $params['timeused'];
        $record->timecreated = time();

        $DB->insert_record('wordsort_attempts', $record);

        $wordsort = $DB->get_record('wordsort', [
            'id' => $params['wordsortid']
        ], '*', MUST_EXIST);

        wordsort_update_grades(
            $wordsort,
            $USER->id,
            $params['percentage']
        );

        return [
            'success' => true,
        ];
    }

    /**
     * Return structure for save_attempt().
     *
     * @return external_single_structure
     */
    public static function save_attempt_returns() {
        return new external_single_structure([
            'success' => new external_value(
                PARAM_BOOL,
                'Whether the attempt was saved successfully'
            ),
        ]);
    }
}