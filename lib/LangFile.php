<?php

namespace Ammina\StopVirus;

use Bitrix\Main;

/**
 * Класс для работы с языковыми фразами
 */
class LangFile
{
	public static $prefix = [];

	/**
	 * Добавить языковые фразы в общий массив
	 *
	 * @param array $MESS Стандартный массив языковых фраз Битрикс
	 * @param array $messages Массив добавляемых языковых фраз
	 * @param string|null $prefix префикс языковых фраз
	 * @return void
	 */
	public static function setMessages(array &$MESS, array $messages, ?string $prefix = null): void
	{
		foreach ($messages as $k => $v) {
			if (is_array($v)) {
				self::setMessages($MESS, $v, $prefix . "_" . $k);
			} else {
				$MESS[$prefix . "_" . $k] = $v;
			}
		}
	}

	/**
	 * Подключение файла языковых фраз
	 *
	 * @param string $file Файл, для которого подключаются языковые фразы
	 * @param string|null $prefix Префикс языковых фраз
	 * @return void
	 * @throws Main\IO\InvalidPathException
	 */
	public static function loadMessages(string $file, ?string $prefix = null): void
	{
		$realPath = realpath($file);
		if ($realPath !== false) {
			$file = $realPath;
		}
		$normalizedFile = Main\IO\Path::normalize($file);
		if (!is_null($normalizedFile)) {
			self::$prefix[$normalizedFile] = $prefix;
			Main\Localization\Loc::loadMessages($normalizedFile);
		}
	}

	/**
	 * Установить префикс языковых фраз для файла
	 *
	 * @param string $prefix префикс
	 * @param string $file Файл, для которого указывается префикс
	 * @return void
	 * @throws Main\IO\InvalidPathException
	 */
	public static function setMessagesFile(string $prefix, string $file): void
	{
		$realPath = realpath($file);
		if ($realPath !== false) {
			$file = $realPath;
		}
		$normalizedFile = Main\IO\Path::normalize($file);
		if (!is_null($normalizedFile) && !array_key_exists($normalizedFile, self::$prefix)) {
			self::$prefix[$normalizedFile] = $prefix;
		}
	}

	/**
	 * Получить языковую фразу
	 *
	 * @param string $code Код языковой фразы
	 * @param string|null $prefix Префикс фразы
	 * @param array|null $replace Массив для перезаписи шаблонов внутри фразы
	 * @param string|null $language Язык
	 * @param string|null $fromFile Для какого файла подключается фраза
	 * @return string|null
	 * @throws Main\IO\InvalidPathException
	 */
	public static function message(string $code, ?string $prefix = null, ?array $replace = null, ?string $language = null, ?string $fromFile = null): ?string
	{
		$fullCode = '';
		if (!is_null($prefix)) {
			$fullCode = ($prefix ? $prefix . "_" : "") . $code;
		} elseif ((string)$fromFile !== '') {
			$currentFile = Main\IO\Path::normalize($fromFile);
			if (isset(self::$prefix[$currentFile])) {
				$prefix = self::$prefix[$currentFile];
				$fullCode = $prefix . "_" . $code;
			}
		} else {
			$trace = Main\Diag\Helper::getBackTrace(4, DEBUG_BACKTRACE_IGNORE_ARGS);
			$currentFile = null;
			for ($i = 3; $i >= 0; $i--) {
				if (mb_stripos($trace[$i]["function"], "message") === 0) {
					$currentFile = Main\IO\Path::normalize($trace[$i]["file"]);
					if (isset(self::$prefix[$currentFile])) {
						$prefix = self::$prefix[$currentFile];
						$fullCode = $prefix . "_" . $code;
					} else {
						$filePrefix = self::findFileForPrefix($currentFile);
						if (!is_null($filePrefix) && isset(self::$prefix[$filePrefix])) {
							$prefix = self::$prefix[$filePrefix];
							$fullCode = $prefix . "_" . $code;
						}
					}
					break;
				}
			}
		}
		$newReplace = [];
		if (is_array($replace)) {
			foreach ($replace as $k => $v) {
				$newReplace['#' . $k . '#'] = $v;
			}
		}
		return Main\Localization\Loc::getMessage($fullCode, $newReplace, $language);
	}

	/**
	 * Поиск префикса фраз по файлу
	 *
	 * @param string $file Полный путь к файлу
	 * @return string|null
	 */
	protected static function findFileForPrefix(string $file): ?string
	{
		$data = explode('/', $file);
		for ($i = 0; $i <= 5; $i++) {
			if (count($data) <= 0) {
				break;
			}
			array_pop($data);
			$data[] = 'include.php';
			if (isset(self::$prefix[implode('/', $data)])) {
				return implode('/', $data);
			}
			array_pop($data);
		}
		return null;
	}

}