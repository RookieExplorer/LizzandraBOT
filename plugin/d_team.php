<?php
try {
    $storage = new Storage();

    if (isset($telegram['message']['chat']['type']) && in_array($telegram['message']['chat']['type'], ['group', 'supergroup'])) {
        $chat_id = $telegram['message']['chat']['id'];
        $title = $telegram['message']['chat']['title'];
    } else if (isset($telegram['chat']['type']) && in_array($telegram['chat']['type'], ['group', 'supergroup'])) {
        $chat_id = $telegram['chat']['id'];
        $title = $telegram['chat']['title'];
    }

    if (isset($chat_id)) {
        while ($storage->query("SELECT * FROM srv_lizzandra.tbl_team WHERE telegram_id = '$chat_id' LIMIT 1;")->num_rows() <= 0) {
            $storage->insert_array('srv_lizzandra.tbl_team', [
                'telegram_id' => $chat_id,
                'telegram_title' => $title,
            ]);

            track('team');
        }

        $group = $storage->query('LAST')->fetch_assoc();

        if ($group[0]['passport'] == 'disabled') {
            telegram('leaveChat', ['chat_id' => $group[0]['telegram_id']]);
            exit();
        }

        $storage->query("UPDATE srv_lizzandra.tbl_team SET signature = signature WHERE telegram_id = '" . $group[0]['telegram_id'] . "' LIMIT 1;");
    }
} catch (Exception $e) {
    #
}
