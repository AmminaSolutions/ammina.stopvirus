<?php

namespace Ammina\StopVirus;

use Ammina\StopVirus\Db\RulesTable;
use Ammina\StopVirus\Db\SignaturesTable;
use Ammina\StopVirus\Orm\Attempts;

class Detector
{
	static protected $instance = null;

	protected $systemSignatures = [
		'base64',
		'file_put_contents',
		'file_get_contents',
		'fwrite',
		'fread',
		'action=getphpversion',
		'echo',
		'accesson',
		'wget ',
		'curl ',
		'shell_',
		'xxd ',
		'xxd%',
		'DOCUMENT_ROOT',
		'\'>/var/',
		'\'>>/var/',
		'\'>/home/',
		'\'>>/home/',
	];

	public static function getInstance(): Detector
	{
		if (!isset(static::$instance)) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	private function __clone()
	{
	}

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	private function __wakeup()
	{
		throw new \Exception("Cannot unserialize a singleton.");
	}

	public function run(): void
	{
		if (!Migrator::getInstance()->check()) {
			if ($this->allowRun()) {
				$this->runDefaultSimple();
			}
			return;
		}
		if (!$this->allowRun()) {
			return;
		}

		$ruleDetect = $this->getRuleDetect();
		if ($ruleDetect === false) {
			$this->runDefaultSimple(true);
		}
		if ($ruleDetect['memoryLimit'] > 0) {
			ini_set('memory_limit', $ruleDetect['memoryLimit'] . 'M');
		}
		if ($ruleDetect['action'] === 'N') {
			return;
		}
		$isDetected = $this->detectRequest($ruleDetect['signatures']);
		$detectSignatures = [];
		if ($isDetected !== false) {
			$detectSignatures = $isDetected;
			$isDetected = true;
		}
		if ($isDetected || in_array($ruleDetect['action'], ['E', 'A'])) {
			$this->storeToDatabse($isDetected, $ruleDetect['storePostBody'], $detectSignatures);
		}
		if ($isDetected && in_array($ruleDetect['action'], ['D', 'E'])) {
			die((Settings::getInstance())->optionMessage());
		}
	}

	protected function storeToDatabse($isDetected, $storePostBody = true, $detectSignatures = []): void
	{
		$host = null;
		if ((Settings::getInstance())->optionStoreHost()) {
			$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		}
		$headers = [];
		if (function_exists('getallheaders')) {
			$headers = getallheaders();
		}
		$attempt = new Attempts();
		$query = $_SERVER['QUERY_STRING'] ?? null;
		$body = null;
		if ($storePostBody) {
			$body = file_get_contents('php://input') ?? null;
		}
		if (!is_null($query)) {
			$query = ['content' => $query];
		}
		if (!is_null($body)) {
			$body = ['content' => $body];
		}
		$attempt
			->setRequestType($_SERVER['REQUEST_METHOD'] ?? 'cli')
			->setHttpHost($_SERVER['HTTP_HOST'] ?? null)
			->setRequestUri($_SERVER['REQUEST_URI'] ?? null)
			->setScriptFilename($_SERVER['SCRIPT_FILENAME'] ?? null)
			->setFromIp($_SERVER['REMOTE_ADDR'] ?? null)
			->setFromHost($host)
			->setFromUserAgent($_SERVER['HTTP_USER_AGENT'] ?? null)
			->setIsDetectVirus($isDetected)
			->setDataHeader($headers)
			->setDataQuery($query)
			->setDataBody($body)
			->setMatchSignatures($detectSignatures)
			->save();
	}

	protected function allowRun(): bool
	{
		global $APPLICATION;
		if (in_array($APPLICATION->GetCurPage(), [
			'/bitrix/admin/ammina.stopvirus.settings.php',
			'/bitrix/admin/ammina.stopvirus.signatures.php',
			'/bitrix/admin/ammina.stopvirus.signatures.edit.php',
		])) {
			return false;
		}
		return (Settings::getInstance())->optionActive();
	}

	protected function runDefaultSimple($storeDb = false): void
	{
		$isDetected = $this->detectRequest();
		$detectSignatures = [];
		if ($isDetected !== false) {
			$detectSignatures = $isDetected;
			$isDetected = true;
		}
		if ($isDetected && $storeDb) {
			$this->storeToDatabse($isDetected, true, $detectSignatures);
		}
		if ($isDetected) {
			die((Settings::getInstance())->optionMessage());
		}
	}

	public function detectRequest($signatures = null)
	{
		$result = false;
		if (is_null($signatures)) {
			$signatures = $this->systemSignatures;
		}
		$checkContent = mb_strtolower($_SERVER['QUERY_STRING']);
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$checkContent .= mb_strtolower(file_get_contents('php://input'));
		}
		if (function_exists('getallheaders')) {
			foreach (getallheaders() as $name => $value) {
				if (strtolower($name) == 'refered') {
					continue;
				}
				$checkContent .= mb_strtolower($name) . ':' . mb_strtolower($value) . "\n";
			}
		}
		if (strpos($checkContent, '%') !== false) {
			$checkContent2 = urldecode($checkContent);
			if (strpos($checkContent2, '%') !== false) {
				$checkContent2 .= urldecode($checkContent2);
			}
			$checkContent .= $checkContent2;
		}

		foreach ($signatures as $signature) {
			$signature = mb_strtolower($signature);
			if (strpos($checkContent, $signature) !== false) {
				$result[] = $signature;
			}
		}

		return $result;
	}

	public function formatContent(string $content, $signatures = null): string
	{
		if (is_null($signatures) || empty($signatures)) {
			$signatures = $this->systemSignatures;
		}
		foreach ($signatures as $k => $signature) {
			$signatures[$k] = trim(mb_strtolower($signature));
		}
		foreach ($signatures as $substring) {
			$content = $this->markCode($substring, '#VIRUS#', '#/VIRUS#', $content);
		}
		$content = nl2br(htmlspecialchars($content));
		$content = str_replace(['#VIRUS#', '#/VIRUS#'], ['<span class="virus-code">', '</span>'], $content);
		return $content;
	}

	protected function markCode($needle, $preString, $postString, $haystack)
	{
		$offset = 0;
		$needleCount = mb_strlen($needle);
		while (($end = mb_stripos($haystack, $needle, $offset)) !== false) {
			$startHaystack = mb_substr($haystack, 0, $end);
			$endHaystack = mb_substr($haystack, $end + $needleCount, mb_strlen($haystack));
			$oldContent = mb_substr($haystack, $end, $needleCount);
			$replace = $preString . $oldContent . $postString;
			$offset = mb_strlen($startHaystack . $replace);
			$haystack = $startHaystack . $replace . $endHaystack;
		}
		return $haystack;
	}

	public function getSystemSignatures(): array
	{
		return $this->systemSignatures;
	}

	protected function getRuleDetect()
	{
		global $APPLICATION;
		$result = false;
		$ip = ip2long($_SERVER['REMOTE_ADDR']);
		$page = $APPLICATION->GetCurPage(true);
		$rules = RulesTable::getList([
			'filter' => [
				'ACTIVE' => 'Y',
			],
			'order' => [
				'SORT' => 'ASC',
				'ID' => 'ASC',
			],
			'cache' => [
				'ttl' => 3600,
			],
		]);
		while ($rule = $rules->fetchObject()) {
			if (!empty($rule->getPageRules())) {
				$completePageRule = false;
				foreach ($rule->getPageRules() as $pageRule) {
					if (strlen($pageRule) > 0) {
						if ($this->is($pageRule, $page)) {
							$completePageRule = true;
							break;
						}
					}
				}
				if (!$completePageRule) {
					continue;
				}
			}
			if (!empty($rule->getIpRulesValues())) {
				$completeIpRule = false;
				foreach ($rule->getIpRulesValues() as $ipRule) {
					if ($ip >= $ipRule['from'] && $ip <= $ipRule['to']) {
						$completeIpRule = true;
						break;
					}
				}
				if (!$completeIpRule) {
					continue;
				}
			}
			$result = [
				'action' => $rule->getAction(),
				'storePostBody' => $rule->getStorePostBody(),
				'memoryLimit' => $rule->getMemoryLimit(),
				'signatures' => $this->systemSignatures,
			];
			if ($rule->getUseDefaultSignature()) {
				$defaultSignature = SignaturesTable::getList([
					'filter' => [
						'IS_DEFAULT' => 'Y',
					],
					'cache' => [
						'ttl' => 3600,
					],
				])->fetchObject();
				if ($defaultSignature) {
					$result['signatures'] = $defaultSignature->getSignatures();
				}
			} elseif ($rule->getSignatureId() > 0) {
				$signature = SignaturesTable::getList([
					'filter' => [
						'ID' => $rule->getSignatureId(),
					],
					'cache' => [
						'ttl' => 3600,
					],
				])->fetchObject();;
				if ($signature && $signature->getId() > 0) {
					$result['signatures'] = $signature->getSignatures();
				}
			}
			break;
		}
		if (is_array($result['signatures'])) {
			foreach ($result['signatures'] as $k => $signature) {
				$result['signatures'][$k] = rtrim(ltrim($signature), "\n\r\t\v\0");
			}
		}
		return $result;
	}

	protected function is($patterns, $value, $ignoreCase = false)
	{
		$value = (string)$value;

		if (!is_iterable($patterns)) {
			$patterns = [$patterns];
		}

		foreach ($patterns as $pattern) {
			$pattern = (string)$pattern;
			if ($pattern === '*' || $pattern === $value) {
				return true;
			}
			if ($ignoreCase && mb_strtolower($pattern) === mb_strtolower($value)) {
				return true;
			}
			$pattern = preg_quote($pattern, '#');
			$pattern = str_replace('\*', '.*', $pattern);
			if (preg_match('#^' . $pattern . '\z#' . ($ignoreCase ? 'isu' : 'su'), $value) === 1) {
				return true;
			}
		}

		return false;
	}
}