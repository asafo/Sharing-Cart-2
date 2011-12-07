<?php
/**
 *  Sharing Cart - Backup Operation
 *  
 *  @author  VERSION2, Inc.
 *  @version $Id: backup.php 540 2011-11-18 05:56:24Z malu $
 */

require_once '../../config.php';

require_once './classes/backup.php';
require_once './classes/record.php';

$course_id = required_param('course', PARAM_INT);
$cm_id     = required_param('module', PARAM_INT);
$return_to = urldecode(required_param('return', PARAM_TEXT));

try {
	set_time_limit(0);
	
	$backup = new sharing_cart\backup($course_id, $cm_id);
	
	$filename = sprintf('shared-%s-%s.mbz',
		$backup->get_mod_name(), date('Ymd-Hi'));
	
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
