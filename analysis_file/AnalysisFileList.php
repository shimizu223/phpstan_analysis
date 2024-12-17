<?php

require_once "AnalysisFile.php";
require_once "/usr/local/share/phpstan_autoload/setting.php";

define("__DS", "/");

class AnalysisFileList {

	private $analysis_file_list = array();
	private $domain_list = array();

	public function __construct(array $domain_list) {
	
		$this->domain_list = $domain_list;
	}

	/**
	 * 要素追加
	 *
	 * @param AnalysisFile $AnalysisFile
	 * @return void
	 */
	public function add_analysis_file_list(AnalysisFile $AnalysisFile) {
		
		$this->analysis_file_list[] = $AnalysisFile;
	}

	/**
	 * ドメイン単位でコレクション取得
	 *
	 * @param string $domain
	 * @return AnalysisFileList
	 */
	public function get_analysis_file_list_by_domain(string $domain): AnalysisFileList {

		$analysis_file_list_by_domain = new AnalysisFileList($this->domain_list);

		foreach ($this->analysis_file_list as $analysis_file) {
			if ($analysis_file->get_domain() == $domain) {
				$analysis_file_list_by_domain->add_analysis_file_list($analysis_file);
			}
		}

		return $analysis_file_list_by_domain;
	}

	/**
	 * 存在チェック
	 *
	 * @return integer
	 */
	public function is_set_analysis_file_list(): int {
		return count($this->analysis_file_list);
	}

	/**
	 * コレクションのファイルパスのリスト
	 *
	 * @return array
	 */
	public function get_file_path_list(): array {

		$file_path_list = array();

		foreach ($this->analysis_file_list as $analysis_file) {
			$file_path_list[] = str_replace(conteiner_project_absolute_path, ".". __DS, $analysis_file->get_file_path());
		}

		return $file_path_list;
	}

	/**
	 * libのパス
	 *
	 * @return string
	 */
	public function get_lib_path(): string {

		foreach ($this->analysis_file_list as $analysis_file) {
			$path_infos = explode(DIRECTORY_SEPARATOR, trim($analysis_file->get_file_path(), DIRECTORY_SEPARATOR));
			foreach ($path_infos as $key => $path_info) {
				if ($path_info == "lib") {
					return ".". __DS. $path_infos[$key - 1] . "/lib/";
				}
			}
		}

		return "";
	}

	/**
	 * admin_cd
	 *
	 * @return string
	 */
	public function get_acd(): string {

		foreach ($this->analysis_file_list as $analysis_file) {
			$path_infos = explode(__DS, trim($analysis_file->get_file_path(), __DS));
			foreach ($path_infos as $key => $path_info) {
				if (in_array($path_info, $this->domain_list)) {
					if (strpos($path_infos[$key + 1],'.') === false) {
						return $path_infos[$key + 1];
					} else {
						break;
					}
				}
			}
		}

		return "";
	}
}