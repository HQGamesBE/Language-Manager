<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\LanguageManager;

/**
 * Class LangaugePermissions
 * @package HQGames\LanguageManager
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 15:04
 * @ide PhpStorm
 * @project Language-Manager
 */
class LangaugePermissions extends \HQGames\Permissions{
	const COMMAND_LANGUAGE_INFO = ["hqgames.command.language.info", "Allows to see infos about languages"];
	const COMMAND_LANGUAGE_TEST = ["hqgames.command.language.test", "Allows to test the language"];
	const COMMAND_LANGUAGE_RELOAD = ["hqgames.command.language.reload", "Allows to reload the language"];

	const SETTINGS_LANGUAGE_DEBUG = ["hqgames.player-settings.language.debug", "Allows 'See the raw messages' setting"];
}
