<?php
/**
 * Definição de constantes
 */
define('LOG_FILE', __DIR__ . DIRECTORY_SEPARATOR . 'history' . DIRECTORY_SEPARATOR . 'error_log.php');

/**
 * Configuração de erros e log
 */
ini_set('error_reporting', E_ALL);
ini_set('error_log', LOG_FILE);
ini_set('log_errors', true);
ini_set('display_errors', true);

/**
 * Limpeza do arquivo de log
 */
$logContent = file(LOG_FILE);
if (count($logContent) > 50) {
  file_put_contents(LOG_FILE, implode('', array_slice($logContent, -50)), FALSE);
}

/**
 * Verificação da versão do PHP
 */
if (phpversion() < 7) {
  error_log('[PHP] Você está utilizando uma versão não homologada.');
  exit();
}

/**
 * Carregamento de extensões
 */
foreach(glob(__DIR__ . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . '*.php') as $item) {
  require_once($item);
}

/**
 * Carregamento de requisições
 */
foreach(glob(__DIR__ . DIRECTORY_SEPARATOR . 'request' . DIRECTORY_SEPARATOR . '*.php') as $item) {
  require_once($item);
}
