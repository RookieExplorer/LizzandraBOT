<?php
/**
 * Última mensagem
 */
try {
	$storage = new Storage();

	if(isset($user[0])) {
		$storage->query("UPDATE srv_lizzandra.tbl_user SET last_message = '" . date('Y-m-d H:i:s') . "' WHERE telegram_id = '" . $user[0]['telegram_id'] . "' LIMIT 1;");
	}
	if(isset($group[0])) {
		$storage->query("UPDATE srv_lizzandra.tbl_team SET last_message = '" . date('Y-m-d H:i:s') . "' WHERE telegram_id = '" . $group[0]['telegram_id'] . "' LIMIT 1;");
	}
	
} catch (Exception $e) {
	#
}

/**
 * Encaminhamento
 */
if(isset($telegram['message']['forward_sender_name'])) {
	require_once('request' . DIRECTORY_SEPARATOR . 'forward' . DIRECTORY_SEPARATOR . 'disabled.php');
}
else if(isset($telegram['message']['forward_from'])) {
	require_once('request' . DIRECTORY_SEPARATOR . 'forward' . DIRECTORY_SEPARATOR . 'enabled.php');
}

/**
 * Comandos
 */
else if(isset($telegram['message']['entities'][0]['type'])) {
	$item = explode(' ', $telegram['message']['text']);
	$item[0] = 'request' . DIRECTORY_SEPARATOR . 'command' . strtolower($item[0]) . '.php';
		if(file_exists($item[0])) {
			require_once($item[0]);
		}
		else {
			exit();
		}
}

/**
 * Callback
 */
else if(isset($telegram['callback_query'])) {
	$item = explode(' ', $telegram['callback_query']['data']);
	$item[0] = 'request' . DIRECTORY_SEPARATOR . 'callback' . DIRECTORY_SEPARATOR . strtolower($item[0]) . '.php';
		if(file_exists($item[0])) {
			require_once($item[0]);
		}
		else {
			exit();
		}
}

/**
 * Mensagem
 */
else {
	require_once('message' . DIRECTORY_SEPARATOR . 'index.php');
		exit();
}
