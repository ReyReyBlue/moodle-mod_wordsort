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

/**
 * Upgrade code for the Word Sort module.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_wordsort_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    // Create wordsort_attempts table.
    if ($oldversion < 2026072900) {

        $table = new xmldb_table('wordsort_attempts');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('wordsortid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('attempt', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '1');
        $table->add_field('score', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('totalwords', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('percentage', XMLDB_TYPE_NUMBER, '10,5', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timeused', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('wordsort_fk', XMLDB_KEY_FOREIGN, ['wordsortid'], 'wordsort', ['id']);

        $table->add_index('userid_idx', XMLDB_INDEX_NOTUNIQUE, ['userid']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_mod_savepoint(true, 2026072900, 'wordsort');
    }

    // Add answers field.
    if ($oldversion < 2026072901) {

        $table = new xmldb_table('wordsort_attempts');

        $field = new xmldb_field(
            'answers',
            XMLDB_TYPE_TEXT,
            null,
            null,
            null,
            null,
            null,
            'timecreated'
        );

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_mod_savepoint(true, 2026072901, 'wordsort');
    }

    // Add status field.
    if ($oldversion < 2026072902) {

        $table = new xmldb_table('wordsort_attempts');

        $field = new xmldb_field(
            'status',
            XMLDB_TYPE_CHAR,
            '20',
            null,
            XMLDB_NOTNULL,
            null,
            'inprogress',
            'answers'
        );

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_mod_savepoint(true, 2026072902, 'wordsort');
    }

    // Add final submission field.
    if ($oldversion < 2026080800) {

        $table = new xmldb_table('wordsort_attempts');

        $field = new xmldb_field(
            'finalsubmission',
            XMLDB_TYPE_INTEGER,
            '1',
            null,
            XMLDB_NOTNULL,
            null,
            '0',
            'status'
        );

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_mod_savepoint(true, 2026080800, 'wordsort');
    }

    // Add score field.
    if ($oldversion < 2026081216) {

        $table = new xmldb_table('wordsort_attempts');

        $field = new xmldb_field(
            'score',
            XMLDB_TYPE_INTEGER,
            '10',
            null,
            XMLDB_NOTNULL,
            null,
            '0',
            'correctanswers'
        );

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_mod_savepoint(true, 2026081216, 'wordsort');
    }

         // Ensure finalsubmission field exists.
    if ($oldversion < 2026081217) {

        $table = new xmldb_table('wordsort_attempts');

        $field = new xmldb_field(
            'finalsubmission',
            XMLDB_TYPE_INTEGER,
            '1',
            null,
            XMLDB_NOTNULL,
            null,
            '0',
            'status'
        );

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_mod_savepoint(true, 2026081217, 'wordsort');
    }

    return true;
}