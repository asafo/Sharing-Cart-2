<?php
/**
 *  Sharing Cart - Restore Operation
 *  
 *  @author  VERSION2, Inc.
 *  @version $Id: restore.php 540 2011-11-18 05:56:24Z malu $
 */

require_once '../../config.php';

require_once './classes/restore.php';
require_once './classes/record.php';

$record_id = required_param('id', PARAM_INT);
$course_id = required_param('course', PARAM_INT);
$section_i = required_param('section', PARAM_INT);
$return_to = urldecode(required_param('return', PARAM_TEXT));

try {
	set_time_limit(0);
	
	$restore = new sharing_cart\restore($course_id, $section_i);
	
	$record = sharing_cart\record::from_id($record_id);
	if ($record->userid != $USER->id)
		throw new sharing_cart\exception('capability');
	
	$restore->restore_file($record->filename);
	
	redirect($return_to);
} catch (Exception $ex) {
	//print_error('err:restore', 'block_sharing_cart', $return_to);
	error($ex->__toString()); // デバッグ用に詳細メッセージを表示
}
