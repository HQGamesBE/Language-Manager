<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames\LanguageManager;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;


/**
 * Class Language
 * @package HQGames\LanguageManager
 * @author Jan Sohn / xxAROX
 * @date 22. July, 2022 - 22:38
 * @ide PhpStorm
 * @project Language-Manager
 */
class Language implements JsonSerializable{
	private string $name;
	private string $colored_name;
	private string $locale;
	private string $prefix;
	private array $contributors;
	private array $cache;

	/**
	 * Language constructor.
	 * @param array $values
	 */
	public function __construct(array $values){
		$this->name = (string)$values["name"];
		$this->colored_name = (string)($values["colored_name"] ?? $values["name"]);
		$this->locale = strtolower($values["locale"]);
		$this->prefix = (string)$values["prefix"];
		$this->contributors = (array)($values["contributors"] ?? [ "No contributors provided" ]);
		$this->cache = $values;
	}

	/**
	 * Function getName
	 * @return string
	 */
	public function getName(): string{
		return $this->name;
	}

	/**
	 * Function getColoredName
	 * @return string
	 */
	public function getColoredName(): string{
		return $this->colored_name;
	}

	/**
	 * Function getPrefix
	 * @return string
	 */
	public function getPrefix(): string{
		return $this->prefix;
	}

	/**
	 * Function getLocale
	 * @return string
	 */
	public function getLocale(): string{
		return $this->locale;
	}

	/**
	 * Function getContributors
	 * @return string[]
	 */
	public function getContributors(): array{
		return $this->contributors;
	}

	/**
	 * Function getValues
	 * @return array
	 */
	public function getValues(): array{
		return $this->cache["values"];
	}

	/**
	 * Function getValues
	 * @return array
	 */
	public function getCache(): array{
		return $this->cache;
	}

	/**
	 * Function translate
	 * @param string $key
	 * @param array|null $values
	 * @return string
	 */
	public function translate(string $key, ?array $values = []): string{
		$key = str_replace("%", "", $key);
		if (!isset($this->cache["values"][$key])) {
			if (LanguageManager::getInstance()->getLanguage()->isKey($key)) {
				return LanguageManager::getInstance()->getLanguage()->translate($key, $values);
			}
			return $key;
		}
		$res = $this->cache["values"][$key];
		if (!empty($values)) {
			preg_match_all("/{(\d+)}/", $res, $matches, PREG_OFFSET_CAPTURE);
			foreach ($matches[1] as $_ => $match) {
				$rkey = ((int)$match[0]);
				$res = str_replace("{" . $rkey . "}", strval($values[$rkey]) ?? "\${$rkey}", $res);
			}
		}
		$res = str_replace("{PREFIX}", $this->prefix, $res);
		$res = str_replace("{ERROR}", "§8[§4Error§8] §7", $res);
		$res = str_replace("{SUCCESS}", "§a§l»§r§7", $res);
		$res = str_replace("{WARNING}", "§e§l»§r§7", $res);
		$res = str_replace("{ANNOUNCE}", "{ANNOUNCEMENT}", $res);
		$res = str_replace("{ANNOUNCEMENT}", "§9§l»§r§7", $res);
		$res = str_replace("{ARROW_GREEN}", "§a§l» §r§7", $res);
		$res = str_replace("{>>_GREEN}", "§a§l» §r§7", $res);
		$res = str_replace("{ARROW_AQUA}", "§b§l» §r§7", $res);
		$res = str_replace("{>>_AQUA}", "§b§l» §r§7", $res);
		$res = str_replace("{ARROW_RED}", "§c§l» §r§7", $res);
		$res = str_replace("{>>_RED}", "§c§l» §r§7", $res);
		$res = str_replace("{ARROW_LIGHT_PURPLE}", "§d§l» §r§7", $res);
		$res = str_replace("{>>_LIGHT_PURPLE}", "§d§l» §r§7", $res);
		$res = str_replace("{ARROW_YELLOW}", "§e§l» §r§7", $res);
		$res = str_replace("{>>_YELLOW}", "§e§l» §r§7", $res);
		$res = str_replace("{ARROW_WHITE}", "§f§l» §r§7", $res);
		$res = str_replace("{>>_WHITE}", "§f§l» §r§7", $res);
		$res = str_replace("{ARROW_GOLD}", "§g§l» §r§7", $res);
		$res = str_replace("{>>_GOLD}", "§g§l» §r§7", $res);
		$res = str_replace("{ARROW_DARK_BLUE}", "§1§l» §r§7", $res);
		$res = str_replace("{>>_DARK_BLUE}", "§1§l» §r§7", $res);
		$res = str_replace("{ARROW_DARK_GREEN}", "§2§l» §r§7", $res);
		$res = str_replace("{>>_DARK_GREEN}", "§2§l» §r§7", $res);
		$res = str_replace("{ARROW_DARK_AQUA}", "§3§l» §r§7", $res);
		$res = str_replace("{>>_DARK_AQUA}", "§3§l» §r§7", $res);
		$res = str_replace("{ARROW_DARK_RED}", "§4§l» §r§7", $res);
		$res = str_replace("{>>_DARK_RED}", "§4§l» §r§7", $res);
		$res = str_replace("{ARROW_DARK_PURPLE}", "§5§l» §r§7", $res);
		$res = str_replace("{>>_DARK_PURPLE}", "§5§l» §r§7", $res);
		$res = str_replace("{ARROW_ORANGE}", "§6§l» §r§7", $res);
		$res = str_replace("{>>_ORANGE}", "§6§l» §r§7", $res);
		$res = str_replace("{ARROW_GRAY}", "§7§l» §r§7", $res);
		$res = str_replace("{>>_GRAY}", "§7§l» §r§7", $res);
		$res = str_replace("{ARROW_DARK_GRAY}", "§8§l» §r§7", $res);
		$res = str_replace("{>>_DARK_GRAY}", "§8§l» §r§7", $res);
		$res = str_replace("{ARROW_BLUE}", "§9§l» §r§7", $res);
		$res = str_replace("{>>_BLUE}", "§9§l» §r§7", $res);
		$res = str_replace("{ARROW_BLACK}", "§0§l» §r§7", $res);
		return str_replace("{>>_BLACK}", "§0§l» §r§7", $res);
	}

	/**
	 * Function isKey
	 * @param string $key
	 * @return bool
	 */
	public function isKey(string $key): bool{
		return isset($this->cache["values"][str_replace("%", "", $key)]);
	}

	public function getLoadedTranslationCount(): int{
		return count($this->cache["values"] ?? []);
	}

	/**
	 * Function jsonSerialize
	 * @return array
	 */
	#[ArrayShape([
		"name"         => "string",
		"colored_name" => "string",
		"locale"       => "string",
		"prefix"       => "string",
		"contributors" => "array",
		"values"       => "array",
	])] public function jsonSerialize(): array{
		return [
			"name"         => $this->name,
			"colored_name" => $this->colored_name,
			"locale"       => $this->locale,
			"prefix"       => $this->prefix,
			"contributors" => $this->contributors,
			"values"       => $this->cache,
		];
	}
}
