<?php

require_once "AnalysisFile.php";
require_once "CbtsAnalysisFile.php";
require_once "AdminAnalysisFile.php";
require_once "GroupAnalysisFile.php";
require_once "UserAnalysisFile.php";
require_once "BatchAnalysisFile.php";
require_once "ApiAnalysisFile.php";
require_once "NoDomainAnalysisFile.php";

class AnalysisFileFactory {

	private $domain_list = array();

	public function __construct(array $domain_list) {
	
		$this->domain_list = $domain_list;
	}

	public function create_analysis_file_object(string $file_path): AnalysisFile{

		foreach ($this->domain_list as $domain) {

			if (strpos($file_path, $domain) !== false) {
				$class_name = ucfirst($domain). "AnalysisFile";
				return new $class_name($file_path);
			}
		}
		
		return new NoDomainAnalysisFile($file_path);
	}
}