<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License.
//
// @package    mod_wordsort
// @copyright 2026 Reelika Pihl
// @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

defined('MOODLE_INTERNAL') || die();

$functions = [

    'mod_wordsort_start_attempt' => [
        'classname'   => 'mod_wordsort\external',
        'methodname'  => 'start_attempt',
        'description' => 'Start a Word Sort attempt.',
        'type'        => 'write',
        'ajax'        => true,
],

    'mod_wordsort_save_attempt' => [
        'classname'   => 'mod_wordsort\external',
        'methodname'  => 'save_attempt',
        'description' => 'Save a Word Sort attempt.',
        'type'        => 'write',
        'ajax'        => true,
    ],
];
