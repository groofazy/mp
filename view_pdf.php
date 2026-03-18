<?php

// local/mp/view_pdf.php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/pdflib.php');

$id = required_param('id', PARAM_INT);
$report = $DB->get_record('local_mp_reports', ['id' => $id], '*', MUST_EXIST);

$pdf = new pdf();
$pdf->AddPage();
// ... use $report->comments, $report->reportname etc. here ...

$pdf->Output(clean_filename($report->reportname) . ".pdf", 'I'); // 'I' for Inline View