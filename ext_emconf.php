<?php

########################################################################
# Extension Manager/Repository config file for ext "autositemap".
#
# Auto generated 02-10-2012 21:19
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Auto grouping sitemap and menu',
	'description' => 'Plugin for a sitemap and menu which groups the first four menus in first four columns and all others in a fifth column. Great menus will get a linebreak. Configuration is based on TypoScript HMENU.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '1.1.0',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Dirk Wildt (Die Netzmacher)',
	'author_email' => 'http://wildt.at.die-netzmacher.de',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.0-6.1.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'devlog' => '',
		),
	),
	'suggests' => array(
		'0' => 'devlog',
	),
	'_md5_values_when_last_written' => 'a:21:{s:9:"ChangeLog";s:4:"a78b";s:21:"ext_conf_template.txt";s:4:"883d";s:12:"ext_icon.gif";s:4:"c888";s:17:"ext_localconf.php";s:4:"b001";s:14:"ext_tables.php";s:4:"e776";s:16:"locallang_db.xml";s:4:"73e4";s:14:"doc/manual.pdf";s:4:"838b";s:14:"doc/manual.sxw";s:4:"6880";s:17:"lib/locallang.xml";s:4:"8ba9";s:50:"lib/extmanager/class.tx_autositemap_extmanager.php";s:4:"d509";s:37:"lib/icons/die-netzmacher.de_200px.gif";s:4:"48b3";s:32:"pi1/class.tx_autositemap_pi1.php";s:4:"8f49";s:16:"pi1/flexform.xml";s:4:"063f";s:27:"pi1/flexform_sheet_sDEF.xml";s:4:"682d";s:17:"pi1/locallang.xml";s:4:"a480";s:26:"pi1/locallang_flexform.xml";s:4:"6b9e";s:33:"res/pi1/yaml/autositemap_yaml.css";s:4:"e9f7";s:24:"static/pi1/constants.txt";s:4:"4dec";s:20:"static/pi1/setup.txt";s:4:"af4b";s:29:"static/pi1/yaml/constants.txt";s:4:"d41d";s:25:"static/pi1/yaml/setup.txt";s:4:"7987";}',
);

?>