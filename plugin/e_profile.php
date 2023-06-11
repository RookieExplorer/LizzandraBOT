<?php
/**
 * Encaminhamento
 */
if(isset($telegram['message']['forward_from'])) {
	try {
		$storage = new Storage();

		while($storage->query("SELECT * FROM srv_lizzandra.tbl_user WHERE telegram_id = '" . $telegram['message']['forward_from']['id'] . "' LIMIT 1;")->num_rows() <= 0) {
			/**
			 * Registro de Usuário
			 */
			$storage->insert_array('srv_lizzandra.tbl_user', [
				'telegram_id' => (isset($telegram['message']['forward_from']['id']) ? $telegram['message']['forward_from']['id'] : ''),
				'telegram_username' => (isset($telegram['message']['forward_from']['username']) ? $telegram['message']['forward_from']['username'] : ''),
				'telegram_name' => (isset($telegram['message']['forward_from']['first_name']) ? $telegram['message']['forward_from']['first_name'] : ''),
			]);

			/**
			 * Track
			 */
			track('user');

		}

		$forward_user = $storage->query('LAST')->fetch_assoc();
	} catch (Exception $e) {
		#
	}
}

/**
 * Callback
 */
if(isset($telegram['callback_query']['message'])) {
	try {
		$storage = new Storage();
		$user = $storage->query("SELECT * FROM srv_lizzandra.tbl_user WHERE telegram_id = '" . $telegram['callback_query']['message']['chat']['id'] . "' LIMIT 1;")->fetch_assoc();
	} catch (Exception $e) {
		#
	}
}

/**
 * Mensagem
 */
if(isset($telegram['message'])) {
	try {
		$storage = new Storage();

		while($storage->query("SELECT * FROM srv_lizzandra.tbl_user WHERE telegram_id = '" . $telegram['message']['from']['id'] . "' LIMIT 1;")->num_rows() <= 0) {
			$storage->insert_array('srv_lizzandra.tbl_user', [
				'telegram_id' => (isset($telegram['message']['from']['id']) ? $telegram['message']['from']['id'] : ''),
				'telegram_username' => (isset($telegram['message']['from']['username']) ? $telegram['message']['from']['username'] : ''),
				'telegram_name' => (isset($telegram['message']['from']['first_name']) ? $telegram['message']['from']['first_name'] : ''),
			]);

			/**
			 * Track
			 */
			track('user');
		}

		$user = $storage->query('LAST')->fetch_assoc();
	} catch (Exception $e) {
		#
	}
}
