<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_wordsort_mod_form extends moodleform_mod {

    public function definition() {
    $mform = $this->_form;

    // General.
    $mform->addElement('text', 'name', get_string('name'));
    $mform->setType('name', PARAM_TEXT);

    $this->standard_intro_elements();

    // Game settings.
$mform->addElement('header', 'gamesettings', get_string('gamesettings', 'wordsort'));

// Category 1.
$mform->addElement(
    'text',
    'category1',
    get_string('category1', 'wordsort')
);
$mform->setType('category1', PARAM_TEXT);

// Category 2.
$mform->addElement(
    'text',
    'category2',
    get_string('category2', 'wordsort')
);
$mform->setType('category2', PARAM_TEXT);

// Time limit.
$mform->addElement(
    'text',
    'timelimit',
    get_string('timelimit', 'wordsort')
);
$mform->setType('timelimit', PARAM_INT);
$mform->setDefault('timelimit', 60);

// Maximum attempts.
$mform->addElement(
    'text',
    'maxattempts',
    get_string('maxattempts', 'wordsort')
);
$mform->setType('maxattempts', PARAM_INT);
$mform->setDefault('maxattempts', 1);

// Shuffle items.
$mform->addElement(
    'advcheckbox',
    'shuffleitems',
    get_string('shuffleitems', 'wordsort')
);
$mform->setDefault('shuffleitems', 1);

// Immediate feedback.
$mform->addElement(
    'advcheckbox',
    'immediatefeedback',
    get_string('immediatefeedback', 'wordsort')
);
$mform->setDefault('immediatefeedback', 1);

    // Standard Moodle settings.
    $this->standard_coursemodule_elements();

    // Save / Cancel buttons.
    $this->add_action_buttons();
    }
}