<?php

require_once "/usr/local/share/phpstan_autoload/analysis_file/AnalysisFileFactory.php";
require_once "/usr/local/share/phpstan_autoload/analysis_file/AnalysisFileList.php";
require_once "/usr/local/share/phpstan_autoload/analysis_file/AnalysisFile.php";
require_once "/usr/local/share/phpstan_autoload/setting.php";

$files = $argv[1];
$files = explode(",", trim($files, ","));
$files = change_conteiner_file_path($files);

// ドメインのリスト
$domain_list = [
];

$analysis_file_factory = new AnalysisFileFactory($domain_list);
$analysis_file_list    = new AnalysisFileList($domain_list);

foreach ($files as $file) {
	$analysis_file = $analysis_file_factory->create_analysis_file_object($file);
	$analysis_file_list->add_analysis_file_list($analysis_file);
}

foreach ($domain_list as $domain) {
	$analysis_file_list_by_domain = $analysis_file_list->get_analysis_file_list_by_domain($domain);
	$lib_path                     = $analysis_file_list->get_lib_path();
	$acd                          = $analysis_file_list->get_acd();

	if ($analysis_file_list_by_domain->is_set_analysis_file_list()) {
		set_confgure_file($domain, $lib_path, $acd);
		set_phpstan_config($analysis_file_list_by_domain);

		chdir(conteiner_project_absolute_path);
		system("composer/vendor/bin/phpstan analyze -c phpstan.neon");
	}
}

function change_conteiner_file_path(array $files) {

	foreach ($files as &$file) {
		$file = str_replace("\\", DIRECTORY_SEPARATOR, str_replace(local_project_absolute_path, conteiner_project_absolute_path, $file));
	}
	return $files;
}

function set_confgure_file(string $domain, string $lib_path, string $acd) {

	$confgure_file = file_get_contents(auto_load_path. "PhpstanConfigureTemplate.txt");

	$confgure_file = str_replace("%%%lib_path%%%", $lib_path, $confgure_file);
	$confgure_file = str_replace("%%%domain%%%",   $domain, $confgure_file);
	$confgure_file = str_replace("%%%acd%%%",      $acd, $confgure_file);

	file_put_contents(auto_load_path. "PhpstanConfigure.php", $confgure_file);
}

function set_phpstan_config(AnalysisFileList $analysis_file_list) {

	$file_path_list = $analysis_file_list->get_file_path_list();
	$paths          = get_paths($file_path_list);

	$phpstan_neon = file_get_contents(conteiner_project_absolute_path. "phpstan_neon_template.txt");

	$phpstan_neon = str_replace("%%%paths%%%", $paths, $phpstan_neon);
	$phpstan_neon = str_replace("%%%auto_load_path%%%", auto_load_path. "phpstan_autoload.php", $phpstan_neon);

	file_put_contents(conteiner_project_absolute_path. "phpstan.neon", $phpstan_neon);
}

function get_paths(array $file_path_list):string {
	$paths = "";
	foreach ($file_path_list as $file_path) {
		$paths .= "        - ". $file_path. "\n";
	}
	return $paths;
}