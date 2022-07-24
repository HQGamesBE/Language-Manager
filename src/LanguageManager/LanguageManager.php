<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\LanguageManager;
use HQGames\Bridge\Bridge;
use HQGames\Core\commands\commando\SoftEnumCache;
use HQGames\forms\elements\Dropdown;
use HQGames\forms\elements\Toggle;
use HQGames\LanguageManager\commands\LanguageCommand;
use HQGames\LanguageManager\scheduler\FetchLanguageAsyncTask;
use HQGames\player\settings\PlayerSettingsEntry;
use HQGames\player\settings\PlayerSettingsManager;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginDescription;
use pocketmine\plugin\PluginLoader;
use pocketmine\plugin\ResourceProvider;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;


/**
 * Class LanguageManager
 * @package HQGames\LanguageManager
 * @author Jan Sohn / xxAROX
 * @date 22. July, 2022 - 20:40
 * @ide PhpStorm
 * @project Plugin-Template
 */
class LanguageManager extends PluginBase{
	static string $FALLBACK = "eng";
	/** @var Language[] */
	protected array $languages = [];
	use SingletonTrait{
		setInstance as private static;
		reset as private;
	}


	/**
	 * LanguageManager constructor.
	 * @param PluginLoader $loader
	 * @param Server $server
	 * @param PluginDescription $description
	 * @param string $dataFolder
	 * @param string $file
	 * @param ResourceProvider $resourceProvider
	 */
	public function __construct(PluginLoader $loader, Server $server, PluginDescription $description, string $dataFolder, string $file, ResourceProvider $resourceProvider){
		self::setInstance($this);
		self::$FALLBACK = getenv("FALLBACK_LANGUAGE") ?? "eng";
		parent::__construct($loader, $server, $description, $dataFolder, $file, $resourceProvider);
	}

	/**
	 * Function onLoad
	 * @return void
	 */
	public function onLoad(): void{
		$this->getLogger()->info("Loading...");
	}

	/**
	 * Function getLanguages
	 * @return array
	 */
	public function getLanguages(): array{
		return $this->languages;
	}

	public function getLanguage(string $locale = "eng"): Language{
		return $this->languages[$locale] ?? $this->languages[self::$FALLBACK];
	}

	/**
	 * Function setLanguages
	 * @param Language[] $languages
	 * @return void
	 */
	public function setLanguages(array $languages): void{
		$this->languages = $languages;
	}

	public function reloadLanguages(): void{
		$onComplete = function (array $languages): void{
			LanguageManager::getInstance()->setLanguages($languages);
			SoftEnumCache::updateEnum("language_codes", array_keys($languages));
		};
		Server::getInstance()->getAsyncPool()->submitTask(new FetchLanguageAsyncTask($onComplete));
	}

	/**
	 * Function onEnable
	 * @return void
	 */
	public function onEnable(): void{
		LangaugePermissions::register();

		SoftEnumCache::addEnum(new CommandEnum("language_codes", [self::$FALLBACK]));

		PlayerSettingsManager::registerSettingsEntry(PlayerSettingsEntry::create(
			"%player-settings.language-debug",
			new Toggle("", false),
			false,
			LangaugePermissions::SETTINGS_LANGUAGE_DEBUG[]
		));
		/*$map = array_map(fn (Language $language) => $language->getName(), $this->getLanguages());
		PlayerSettingsManager::registerSettingsEntry(PlayerSettingsEntry::create(
			"%player-settings.language",
			new Dropdown("", $map), // TODO: check on https://discord.com/channels/523663022053392405/868073903703093259/1000835526800195694
			false,
			null
		));*/

		$this->registerCommands();
		$this->registerListeners();

		$this->getLogger()->info("Enabled");
	}

	/**
	 * Function onDisable
	 * @return void
	 */
	public function onDisable(): void{
		$this->getLogger()->info("Disabled");
	}

	/**
	 * Function registerCommands
	 * @return void
	 */
	private function registerCommands(): void{
		/** @var Command[] $commands */
		$commands = [
			new LanguageCommand(),
		];
		foreach ($commands as $command) $this->getServer()->getCommandMap()->register(mb_strtolower($this->getDescription()->getName()), $command);
	}

	/**
	 * Function registerListeners
	 * @return void
	 */
	private function registerListeners(): void{
		/** @var Listener[] $listeners */
		$listeners = [
		];
		foreach ($listeners as $listener) $this->getServer()->getPluginManager()->registerEvents($listener, $this);
	}
}
