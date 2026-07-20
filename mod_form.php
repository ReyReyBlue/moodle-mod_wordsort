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

// Timing
$mform->addElement('header', 'timingsettings',
    get_string('timingsettings', 'wordsort'));

    $timingoptions = [];

    $timingoptions[] = $mform->createElement(
    'radio',
    'timingmode',
    '',
    get_string('notimer', 'wordsort'),
    'none'
);

$timingoptions[] = $mform->createElement(
    'radio',
    'timingmode',
    '',
    get_string('countdown', 'wordsort'),
    'countdown'
);

$timingoptions[] = $mform->createElement(
    'radio',
    'timingmode',
    '',
    get_string('stopwatch', 'wordsort'),
    'stopwatch'
);

$mform->addGroup(
    $timingoptions,
    'timingmodegroup',
    get_string('timingmode', 'wordsort'),
    array('<br>'),
    false
);

$mform->setDefault('timingmode', 'none');

$mform->addElement(
    'text',
    'timevalue',
    get_string('timevalue', 'wordsort')
);

$mform->setType('timevalue', PARAM_INT);
$mform->setDefault('timevalue', 60);

$mform->hideIf(
    'timevalue',
    'timingmode',
    'eq',
    'none'
);

// Attempts.
$mform->addElement(
    'header',
    'attemptsettings',
    get_string('attemptsettings', 'wordsort')
);

// Maximum attempts.
$mform->addElement(
    'text',
    'maxattempts',
    get_string('maxattempts', 'wordsort')
);
$mform->setType('maxattempts', PARAM_INT);
$mform->setDefault('maxattempts', 1);

// Activity options.
$mform->addElement(
    'header',
    'activityoptions',
    get_string('activityoptions', 'wordsort')
);

// Shuffle items.
$mform->addElement(
    'advcheckbox',
    'shufflewords',
    get_string('shufflewords', 'wordsort')
);
$mform->setDefault('shufflewords', 1);

// Feedback mode.
$feedbackoptions = [];

$feedbackoptions[] = $mform->createElement(
    'radio',
    'feedbackmode',
    '',
    get_string('feedbackeachmove', 'wordsort'),
    'move'
);

$feedbackoptions[] = $mform->createElement(
    'radio',
    'feedbackmode',
    '',
    get_string('feedbacksubmit', 'wordsort'),
    'submit'
);

$feedbackoptions[] = $mform->createElement(
    'radio',
    'feedbackmode',
    '',
    get_string('feedbacknone', 'wordsort'),
    'none'
);

$mform->addGroup(
    $feedbackoptions,
    'feedbackgroup',
    get_string('feedbackmode', 'wordsort'),
    '<br>'
);

$mform->setDefault('feedbackmode', 'move');

    // Standard Moodle settings.
    $this->standard_coursemodule_elements();

    // Save / Cancel buttons.
    $this->add_action_buttons();
    }
}