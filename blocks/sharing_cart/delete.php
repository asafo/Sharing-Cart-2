<?php
/**
 *  Sharing Cart - Delete Operation
 *  
 *  @author  VERSION2, Inc.
 *  @version $Id: delete.php 540 2011-11-18 05:56:24Z malu $
 */

require_once '../../config.php';

require_once './classes/storage.php';
require_once './classes/record.php';

require_login();

$record_id = required_param('id', PARAM_INT);
$return_to = urldecode(required_param('return', PARAM_TEXT));

try {
	$record = sharing_cart\record::from_id($record_id);
	if ($record->userid != $USER->id)
		throw new sharing_cart\exception('capability');
	
	$storage = new sharing_cart\storage();
	$storage->delete($record->filename);
	
	$record->delete();
	
	redirect($return_to);
} catch (Exception $ex) {
	//print_error($ex->getMessage(), 'block_sharing_cart', $return_to);
	error($ex->__toString());
}
