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
namespace OCA\ForceDownload\Migration;

use OCP\Migration\IOutput;
use OCP\Migration\IRepairStep;
use OC\Core\Command\Maintenance\Mimetype\UpdateJS;
use OCA\ForceDownload\AppInfo\Application;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class UninstallStep implements IRepairStep {

	/** @var LoggerInterface */
	protected $logger;
	protected $updateJS;

	public function __construct(LoggerInterface $logger, UpdateJS $updateJS) {
			$this->logger = $logger;
			$this->updateJS = $updateJS;
	}

	/**
	* Returns the step's name
	*/
	public function getName() {
			return 'Uninstall Force Download';
	}

	/**
	* @param IOutput $output
	*/
	public function run(IOutput $output) {
		$currentVersion = implode('.', \OC_Util::getVersion());
		$configDir = \OC::$configDir;
		$mimetypealiasesFile = $configDir . 'mimetypealiases.json';
		$mimetypemappingFile = $configDir . 'mimetypemapping.json';

		$this->removeFromFile($mimetypealiasesFile, ['force-download' => '*']);
		$this->removeFromFile($mimetypemappingFile, ['*' => ['force-download']]);
		$this->logger->info('Remove force-download from mimetype list.', ['app' => Application::APPNAME]);
		$this->updateJS->run(new StringInput(''), new ConsoleOutput());
	}

	private function removeFromFile(string $filename, array $data) {
		$obj = [];
		if (file_exists($filename)) {
			$content = file_get_contents($filename);
			$obj = json_decode($content, true);
		}
		foreach ($data as $key => $value) {
			if ($value === '*' || $key === '*') {
				unset($obj[$key]);
			}
		}
		file_put_contents($filename, json_encode($obj,  JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
	}
}
