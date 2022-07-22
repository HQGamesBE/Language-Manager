<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\plugin_name;
use HQGames\Core\addons\AddonManager;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginDescription;
use pocketmine\plugin\PluginLoader;
use pocketmine\plugin\ResourceProvider;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;


/**
 * Class pluginname
 * @package HQGames\plugin_name
 * @author Jan Sohn / xxAROX
 * @date 22. July, 2022 - 20:40
 * @ide PhpStorm
 * @project Plugin-Template
 */
class plugin_name extends PluginBase{
	use SingletonTrait{
		setInstance as private static;
		reset as private;
	}


	/**
	 * plugin_name constructor.
	 * @param PluginLoader $loader
	 * @param Server $server
	 * @param PluginDescription $description
	 * @param string $dataFolder
	 * @param string $file
	 * @param ResourceProvider $resourceProvider
	 */
	public function __construct(PluginLoader $loader, Server $server, PluginDescription $description, string $dataFolder, string $file, ResourceProvider $resourceProvider){
		self::setInstance($this);
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
	 * Function onEnable
	 * @return void
	 */
	public function onEnable(): void{
		$this->registerAddons();
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
	 * Function registerAddons
	 * @return void
	 */
	private function registerAddons(): void{
		new AddonManager($this);
		$addons = [
		];
		foreach ($addons as $addon) {
			if (!class_exists($addon)) {
				$this->getLogger()->error("Addon '{$addon}' is not found!");
				continue;
			}
			AddonManager::getInstance()->registerAddon($addon);
			$this->getLogger()->debug("Addon '{$addon}' is registered!");
		}
	}

	/**
	 * Function registerCommands
	 * @return void
	 */
	private function registerCommands(): void{
		/** @var Command[] $commands */
		$commands = [
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
