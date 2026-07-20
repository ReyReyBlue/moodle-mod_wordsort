<?php

require('../../config.php');

$id = required_param('id', PARAM_INT);

redirect(new moodle_url('/course/view.php', ['id' => $id]));