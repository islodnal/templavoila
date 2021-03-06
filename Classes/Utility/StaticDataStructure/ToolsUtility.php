<?php
namespace Extension\Templavoila\Utility\StaticDataStructure;

/***************************************************************
 * Copyright notice
 *
 * (c) 2009 Steffen Kamper (info@sk-typo3.de)
 *  All rights reserved
 *
 *  This script is part of the Typo3 project. The Typo3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class for userFuncs within the Extension Manager.
 *
 * @author    Steffen Kamper  <info@sk-typo3.de>
 */
class ToolsUtility {

	/**
	 *
	 * @param unknown_type $conf
	 */
	public static function readStaticDsFilesIntoArray($conf) {
		$paths = array_unique(array('fce' => $conf['staticDS.']['path_fce'], 'page' => $conf['staticDS.']['path_page']));
		foreach ($paths as $type => $path) {
			$absolutePath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($path);
			$files = \TYPO3\CMS\Core\Utility\GeneralUtility::getFilesInDir($absolutePath, 'xml', TRUE);
			// if all files are in the same folder, don't resolve the scope by path type
			if (count($paths) == 1) {
				$type = FALSE;
			}
			foreach ($files as $filePath) {
				$staticDataStructure = array();
				$pathInfo = pathinfo($filePath);

				$staticDataStructure['title'] = $pathInfo['filename'];
				$staticDataStructure['path'] = substr($filePath, strlen(PATH_site));
				$iconPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.gif';
				if (file_exists($iconPath)) {
					$staticDataStructure['icon'] = substr($iconPath, strlen(PATH_site));
				}

				if (($type !== FALSE && $type === 'fce') || strpos($pathInfo['filename'], '(fce)') !== FALSE) {
					$staticDataStructure['scope'] = \Extension\Templavoila\Domain\Model\AbstractDataStructure::SCOPE_FCE;
				} else {
					$staticDataStructure['scope'] = \Extension\Templavoila\Domain\Model\AbstractDataStructure::SCOPE_PAGE;
				}

				$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['templavoila']['staticDataStructures'][] = $staticDataStructure;
			}
		}
	}
}
