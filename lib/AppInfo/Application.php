<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2022, Vitor Mattos <vitor@php.rio>
 *
 * @author Vitor Mattos <vitor@php.rio>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCA\ForceDownload\AppInfo;

use Closure;
use OC\EventDispatcher\EventDispatcher;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

class Application extends App implements IBootstrap {
	const APPNAME = 'force_download';

	public function __construct(array $urlParams = array()) {
		parent::__construct(self::APPNAME, $urlParams);
	}

	public function register(IRegistrationContext $context): void {
	}

	public function boot(IBootContext $context): void {
		$context->injectFn(Closure::fromCallable([$this, 'registerEventListeners']));
	}

	/**
	 * @todo move the OCP events and then move the registration to `register`
	 */
	private function registerEventListeners(EventDispatcher $dispatcher): void {
		$dispatcher->addListener('OCA\Files::loadAdditionalScripts', function () {
			\OCP\Util::addScript('force_download', 'download');
		});
	}
}
