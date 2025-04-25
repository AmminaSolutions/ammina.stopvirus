<?php

namespace Ammina\StopVirus\Db;

use Ammina\StopVirus;
use Bitrix\Main;

class RulesTable extends Main\ORM\Data\DataManager
{
	protected static $stopUpdate = false;

	public static function getTableName(): string
	{
		return 'am_stopvirus_rules';
	}

	public static function getObjectClass(): string
	{
		return StopVirus\Orm\Rules::class;
	}

	public static function getCollectionClass(): string
	{
		return StopVirus\Orm\RulesCollection::class;
	}

	/**
	 * @return array
	 * @throws Main\SystemException
	 */
	public static function getMap(): array
	{
		return [
			new Main\Orm\Fields\IntegerField('ID', [
				'primary' => true,
				'autocomplete' => true,
			]),

			new Main\Orm\Fields\StringField('NAME', [
				'nullable' => true,
			]),
			new Main\Orm\Fields\BooleanField('ACTIVE', [
				'nullable' => false,
				'values' => ['N', 'Y'],
				'default_value' => 'Y',
			]),
			new Main\Orm\Fields\IntegerField('SORT', [
				'nullable' => false,
				'default_value' => 1000,
			]),
			new Main\Orm\Fields\EnumField('ACTION', [
				'nullable' => false,
				'values' => ['D', 'L', 'E', 'A', 'N'],
				'default_value' => 'D',
			]),
			new Main\Orm\Fields\BooleanField('USE_DEFAULT_SIGNATURE', [
				'nullable' => false,
				'values' => ['N', 'Y'],
				'default_value' => 'Y',
			]),

			new Main\Orm\Fields\IntegerField('SIGNATURE_ID', [
				'nullable' => true,
			]),
			new Main\ORM\Fields\Relations\Reference(
				'SIGNATURE',
				SignaturesTable::class,
				Main\ORM\Query\Join::on('this.SIGNATURE_ID', 'ref.ID'),
				[
					'join_type' => Main\ORM\Query\Join::TYPE_LEFT,
				]
			),
			new Main\Orm\Fields\BooleanField('STORE_POST_BODY', [
				'nullable' => false,
				'values' => ['N', 'Y'],
				'default_value' => 'Y',
			]),

			new Main\Orm\Fields\IntegerField('MEMORY_LIMIT', [
				'nullable' => true,
			]),

			(new Main\Orm\Fields\ArrayField('PAGE_RULES', [
				'nullable' => true,
			]))
				->configureSerializationJson(),
			(new Main\Orm\Fields\ArrayField('IP_RULES', [
				'nullable' => true,
			]))
				->configureSerializationJson(),
			(new Main\Orm\Fields\ArrayField('IP_RULES_VALUES', [
				'nullable' => true,
			]))
				->configureSerializationJson(),

		];
	}

	public static function onAfterAdd(Main\ORM\Event $event)
	{
		$id = $event->getParameter("id");
		if (is_array($id)) {
			$id = $id['ID'];
		}
		static::cleanCache();
		self::generateIpRules($id);
	}

	public static function onAfterUpdate(Main\ORM\Event $event)
	{
		$id = $event->getParameter("id");
		if (is_array($id)) {
			$id = $id['ID'];
		}
		static::cleanCache();
		self::generateIpRules($id);
	}

	protected static function generateIpRules($id): void
	{
		if (static::$stopUpdate) {
			return;
		}
		static::$stopUpdate = true;
		$item = static::getByID($id)->fetchObject();
		if ($item) {
			$result = [];
			foreach ($item->getIpRules() ?? [] as $rule) {
				if (strpos($rule, '/') !== false) {
					[$range, $netmask] = explode('/', $rule, 2);
					if (strpos($netmask, '.') === false) {
						$netmask = intval($netmask);
						if ($netmask < 1 || $netmask > 32) {
							$netmask = 32;
						}
						$netmask_dec = str_pad(str_pad('', $netmask, '1'), 32, '0');
						$netmask = '';
						foreach (array_map('bindec', str_split($netmask_dec, 8)) as $b) $netmask .= pack('C*', $b);
						$netmask = inet_ntop($netmask);
					}
					if (strpos($netmask, '.') !== false) {
						$netmask = str_replace('*', '0', $netmask);
						$ar = explode('.', $netmask);
						$hasNo255 = false;
						foreach ($ar as $k => $v) {
							if ($hasNo255) {
								$ar[$k] = 0;
							}
							if ($v != 255) {
								$hasNo255 = true;
							}
						}
						$netmask = implode('.', $ar);
						$netmask_dec = ip2long($netmask);
						if ($netmask_dec === false) {
							continue;
						}
						$startIp = ip2long($range) & $netmask_dec;
						$endIp = ip2long($range) | ~$netmask_dec;
						if ($startIp !== false && $endIp !== false) {
							$result[] = [
								'from' => ip2long(long2ip($startIp)),
								'to' => ip2long(long2ip($endIp)),
							];
						}
					}
				} elseif (strpos($rule, '-') !== false) {
					$ar = explode('-', $rule, 2);
					$startIp = ip2long($ar[0]);
					$endIp = ip2long($ar[1]);
					if ($startIp !== false && $endIp !== false) {
						$result[] = [
							'from' => $startIp,
							'to' => $endIp,
						];
					}
				} elseif (strpos($rule, '*') !== false) {
					$startIp = ip2long(str_replace('*', '0', $rule));
					$endIp = ip2long(str_replace('*', '255', $rule));
					if ($startIp !== false && $endIp !== false) {
						$result[] = [
							'from' => $startIp,
							'to' => $endIp,
						];
					}
				} else {
					$ip = ip2long($rule);
					if ($ip !== false) {
						$result[] = [
							'from' => $ip,
							'to' => $ip,
						];
					}
				}
			}
			$item
				->setIpRulesValues($result)
				->save();
		}
		static::$stopUpdate = false;
	}
}