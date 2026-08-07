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

// Categories.
$mform->addElement(
    'text',
    'categoryleft',
    get_string('categoryleft', 'wordsort')
);
$mform->setType('categoryleft', PARAM_TEXT);
$mform->addRule(
    'categoryleft',
    null,
    'required',
    null,
    'client');

$mform->setType('categoryleft', PARAM_TEXT);

$mform->addElement(
    'text',
    'categoryright',
    get_string('categoryright', 'wordsort'));
$mform->setType('categoryright', PARAM_TEXT);
$mform->addRule(
    'categoryright',
    null,
    'required',
    null,
    'client');

$mform->setType('categoryright', PARAM_TEXT);

// Timing
$mform->addElement('header', 'timingsettings',
    get_string('timingsettings', 'wordsort'));

    $timingoptions = [];

    $timingoptions[] = $mform->createElement(
    'radio',
    'timingmode',
    '',
    get_string('notimer', 'wordsort'),
    0);

$timingoptions[] = $mform->createElement(
    'radio',
    'timingmode',
    '',
    get_string('countdown', 'wordsort'),
    1);

$timingoptions[] = $mform->createElement(
    'radio',
    'timingmode',
    '',
    get_string('stopwatch', 'wordsort'),
    2
);

$mform->addGroup(
    $timingoptions,
    'timingmodegroup',
    get_string('timingmode', 'wordsort'),
    ['&nbsp;&nbsp;&nbsp;&nbsp;'],
    false
);

$mform->setDefault('timingmode', 0);

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
    0
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
    get_string('feedbacknone', 'wordsort'),
    0
);

$feedbackoptions[] = $mform->createElement(
    'radio',
    'feedbackmode',
    '',
    get_string('feedbackeachmove', 'wordsort'),
    1
);

$feedbackoptions[] = $mform->createElement(
    'radio',
    'feedbackmode',
    '',
    get_string('feedbacksubmit', 'wordsort'),
    2
);

$mform->addGroup(
    $feedbackoptions,
    'feedbackgroup',
    get_string('feedbackmode', 'wordsort'),
    ['&nbsp;&nbsp;&nbsp;&nbsp;'],
    false
);

$mform->setDefault('feedbackmode', 0);

// Standard Moodle settings.
    $this->standard_grading_coursemodule_elements();
    $this->standard_coursemodule_elements();

    // Save / Cancel buttons.
    $this->add_action_buttons();
    }

    public function validation($data, $files) {
    $errors = parent::validation($data, $files);

    $left = trim(core_text::strtolower($data['categoryleft']));
    $right = trim(core_text::strtolower($data['categoryright']));

    if ($left === '') {
        $errors['categoryleft'] =
            get_string('errorcategoryleftrequired', 'wordsort');
    }

    if ($right === '') {
        $errors['categoryright'] =
            get_string('errorcategoryrightrequired', 'wordsort');
    }

    if ($left !== '' && $right !== '' && $left === $right) {
        $errors['categoryright'] =
            get_string('errorcategoriesequal', 'wordsort');
    }

    return $errors;
}
}