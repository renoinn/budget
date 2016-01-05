<?php

/**
 * テンプレートを操作するクラス
 * created : 2008-12-06T22:46:26
 * auther : oomori
 */
define('LAYOUT_DIR', dirname(dirname(__FILE__)).'/templates/layout/');
define('TEMPLATE_DIR', dirname(dirname(__FILE__)).'/templates/');

class View {

	private $layout = 'layout';

	public $template = '';
	public $data = array();
	public $options = array();
	public $callback = '';
	public $output = '';

	function __construct($output = null, $callback = null) {
		if($output != null) {
			$this->output = $output;
		} else {
			$this->output = 'text';
		}

		if($callback != null) {
			$this->callback = $callback;
		} else {
			$this->callback = 'callback';
		}
	}

	public function render($value, $layout = null) {
		$this->template = $value;
		if ($layout == null) {
			require_once LAYOUT_DIR.$this->layout.'.html';
			exit;
		}
		require_once LAYOUT_DIR.$layout.'html';
	}

	public function fetch($value, $debug = false, $params = null) {
		$this->template = $value;

		ob_start();
		require_once TEMPLATE_DIR.$this->template.'.html';
		$fetched = ob_get_contents();
		ob_end_clean();

		if($this->output === 'json') {
			$object = array('template' => $fetched);
			if(0 < count($this->options)) {
				foreach($this->options as $key => $value) {
					$object[$key] = $value;
				}
			}

			$json = json_encode($object);
			$fetched = $this->callback.'('.$json.')';
		}

		return $fetched;
	}

	public function getLayout() {
		return $this->layout;
	}
	public function setLayout($value) {
		$this->layout = $value;
	}

	public function getData($key) {
		return $this->data[$key];
	}
	public function setData($key, $value) {
		$this->data[$key] = $value;
	}
}
?>
