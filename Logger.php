<?php

define('LOG_PATH', '/var/log/');

abstract class Logger {

	private static $fileLogger;
	
	/**
	 * Inicializa
	 * @access private static
	 * @var string $name
	 */
	private static function initialize ($name = '') {
		if ($name === '') {
			$name = 'log' . date('Y-m-d') . '.txt';
		}

		self::$fileLogger = fopen(LOG_PATH . $name, 'a+');

		if (!self::$fileLogger) {
			throw new Exception("No se puede abrir el log llamado: " . $name);
		}
	}

	/**
	 * Registra el log
	 * @access public static
	 * @var string $type
	 * @var string $msg
	 * @var string $name_log
	 */
	public static function log ($type = 'INFO', $msg = '', $name_log = '') {
		$date = date(DATE_RFC1036);
		$uri = $_SERVER['REQUEST_URI'];
		$msg = "[$date][$type] " . $msg;
		self::initialize($name_log);
		fputs(self::$fileLogger, $msg . PHP_EOL);
		fclose(self::$fileLogger);
	}
}