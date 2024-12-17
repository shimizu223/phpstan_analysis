<?php

abstract class AnalysisFile {

	protected string $domain = "";
	protected string $file_path = "";

	// 一応domain特有の挙動取れるようにしとく
	abstract public function __construct(string $file_path);

	public function get_domain() {
		return $this->domain;
	}

	public function get_file_path() {
		return $this->file_path;
	}
}