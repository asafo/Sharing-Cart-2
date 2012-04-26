<?php
/**
 *  Sharing Cart - Backup Operation
 *  
 *  @author  VERSION2, Inc.
 *  @version $Id: backup.php 698 2012-04-26 03:24:55Z malu $
 */

require_once '../../config.php';

require_once './classes/backup.php';
require_once './classes/record.php';

const MAX_FILENAME = 20;

$course_id = required_param('course', PARAM_INT);
$cm_id     = required_param('module', PARAM_INT);
$return_to = urldecode(required_param('return', PARAM_TEXT));

try {
	set_time_limit(0);
	
	$backup = new sharing_cart\backup($course_id, $cm_id);
	
	$cleanname = clean_filename(strip_tags($backup->get_mod_text()));
	$textlib = textlib_get_instance();
	if ($textlib->strlen($cleanname) > MAX_FILENAME)
		$cleanname = $textlib->substr($cleanname, 0, MAX_FILENAME) . '_';
	$filename = sprintf('%s-%s.mbz', $cleanname, date('Ymd-His'));
	
	$backup->save_as($filename);
	
	$record = new sharing_cart\record(array(
		'modname'  => $backup->get_mod_name(),
		'modtext'  => $backup->get_mod_text(),
		'filename' => $filename,
	));
	$record->insert();
	
	redirect($return_to);
} catch (Exception $ex) {
	//print_error('err:backup', 'block_sharing_cart', $return_to);
	error($ex->__toString()); // デバッグ用に詳細メッセージを表示
}
