<?php
/**
 * Track
 */
function track($input) {
	$storage = new Storage();
		if($storage->query("SELECT code FROM srv_lizzandra.tbl_track WHERE date = '" . date('Y-m-d') . "' LIMIT 1;")->num_rows() > 0) {
			$storage->query("UPDATE srv_lizzandra.tbl_track SET " . $input . " = " . $input . "+1 WHERE date = '" . date('Y-m-d') . "' LIMIT 1;");
		}
		else {
			$storage->insert_array('srv_lizzandra.tbl_track', [
				'date' => date('Y-m-d'),
				$input => 1,
			]);
		}
}
