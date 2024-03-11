<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Defines the import questions form.
 *
 * @package    qbank_generatequestion
 * @copyright  ISB Bayern, 2024
 * @author     Dr. Peter Mayer
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->dirroot . '/question/editlib.php');

global $PAGE, $USER, $DB, $OUTPUT;

// $courseid = required_param('courseid', PARAM_INT);

$thisurl = new \moodle_url('/question/bank/generatequestion/generatequestion.php');
$PAGE->set_url($thisurl);

$PAGE->set_context(\context_system::instance());
$pagetitle = get_string('generate_question', 'qbank_generatequestion');
$PAGE->set_title($pagetitle);
$PAGE->set_heading($pagetitle);

// No secondary navigation.
$PAGE->set_secondary_navigation(false);

// Now generate the template context to fill the categories select box.

list($thispageurl, $contexts, $cmid, $cm, $module, $pagevars) =
    question_edit_setup('import', '/question/bank/generatequestion/generatequestion.php');

list($catid, $catcontext) = explode(',', $pagevars['cat']);
if (!$category = $DB->get_record("question_categories", ['id' => $catid])) {
    throw new moodle_exception('nocategory', 'question');
}

$categorycontext = \context::instance_by_id($category->contextid);
$category->context = $categorycontext;
// This page can be called without courseid or cmid in which case.
// We get the context from the category object.
if ($contexts === null) { // Need to get the course from the chosen category.
    $contexts = new core_question\local\bank\question_edit_contexts($categorycontext);
    $thiscontext = $contexts->lowest();
    if ($thiscontext->contextlevel == CONTEXT_COURSE) {
        require_login($thiscontext->instanceid, false);
    } else if ($thiscontext->contextlevel == CONTEXT_MODULE) {
        list($module, $cm) = get_module_from_cmid($thiscontext->instanceid);
        require_login($cm->course, false, $cm);
    }
    $contexts->require_one_edit_tab_cap($edittab);
}

$tempcategories = qbank_managecategories\helper::question_category_options($contexts->having_one_edit_tab_cap('import'));

foreach ($tempcategories as $qbankname => $tempcategory) {
    foreach ($tempcategory as $key => $catname) {
        $categories[] = [
            'qbankname' => $qbankname,
            'qcatid_context' => $key,
            'catname' => $catname,
        ];
    }
}
$templatecontext = [];
$templatecontext['categories'] = $categories;

foreach (array_keys(question_bank::get_creatable_qtypes()) as $qtypename) {
    $templatecontext['qtypes'][] = [
        'shortname' => $qtypename,
        'name' => get_string('pluginname', 'qtype_' . $qtypename),
    ];
}

// $templatecontext['qtypes'] = array_keys(question_bank::get_creatable_qtypes());
\local_debugger\performance\debugger::print_debug('test', 'qtypes', $templatecontext['qtypes']);
// \local_debugger\performance\debugger::print_debug('test', 'categories', $categories);
// \local_debugger\performance\debugger::print_debug('test', 'categories', $templatecontext);

$PAGE->activityheader->disable();

echo $OUTPUT->header();

if (optional_param('action', '', PARAM_TEXT) == 'createquestion') {
    // CONVERT TO AN OBJECT
    $xml = required_param('qestionxml', PARAM_RAW);
    // $xml = required_param('category', PARAM_RAW);

    // $obj = SimpleXML_Load_String($xml);
    // \local_debugger\performance\debugger::print_debug('test', 'generatequestion', $obj);

    // PARSE OUT SOME ATTRIBUTES
    $fileformatnames = get_import_export_formats('import');


    // +++ Start Adaption.
    // Adapted from question/bank/importquestions/import.php.

    // File checks out ok.
    $fileisgood = false;

    // Work out if this is an uploaded file.
    // Or one from the filesarea.

    $fileformat = 'xml';
    $filedir = make_request_directory();
    $realfilename = uniqid() . "." . $fileformat;
    $importfile = $filedir . '/' . $realfilename;
    $filecreated = file_put_contents($importfile, $xml);

    // $realfilename = $importform->get_new_filename('newfile');
    // $importfile = make_request_directory() . "/{$realfilename}";
    // if (!$result = $importform->save_file('newfile', $importfile, true)) {
    //     throw new moodle_exception('uploadproblem');
    // }

    $formatfile = $CFG->dirroot . '/question/format/' . $fileformat . '/format.php';
    if (!is_readable($formatfile)) {
        throw new moodle_exception('formatnotfound', 'question', '', $fileformat);
    }

    require_once($formatfile);

    $classname = 'qformat_' . $fileformat;
    $qformat = new $classname();

    // Load data into class.
    $qformat->setCategory($category);
    $qformat->setContexts($contexts->having_one_edit_tab_cap('import'));
    $qformat->setCourse($COURSE);
    $qformat->setFilename($importfile);
    $qformat->setRealfilename($realfilename);
    // $qformat->setMatchgrades($form->matchgrades);
    // $qformat->setCatfromfile(!empty($form->catfromfile));
    // $qformat->setContextfromfile(!empty($form->contextfromfile));
    $qformat->setStoponerror(true);

    // Do anything before that we need to.
    if (!$qformat->importpreprocess()) {
        throw new moodle_exception('cannotimport', '', $thispageurl->out());
    }

    // Process the uploaded file.
    if (!$qformat->importprocess()) {
        throw new moodle_exception('cannotimport', '', $thispageurl->out());
    }

    // In case anything needs to be done after.
    if (!$qformat->importpostprocess()) {
        throw new moodle_exception('cannotimport', '', $thispageurl->out());
    }

    // Log the import into this category.
    $eventparams = [
        'contextid' => $qformat->category->contextid,
        'other' => ['format' => $fileformat, 'categoryid' => $qformat->category->id],
    ];

    // --- End Adaption.

    $event = \core\event\questions_imported::create($eventparams);
    $event->trigger();



    $params = $thispageurl->params() + ['category' => $qformat->category->id . ',' . $qformat->category->contextid];
    echo $OUTPUT->continue_button(new moodle_url('/question/edit.php', $params));
    echo $OUTPUT->footer();
    exit;
}


// Page header.
// $PAGE->set_title($txt->importquestions);
// $PAGE->set_heading($COURSE->fullname);


echo $OUTPUT->render_from_template('qbank_generatequestion/generation_form', $templatecontext);

echo $OUTPUT->footer();
