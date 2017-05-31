<?php

// define cronst
defined('DS') or define('DS', '/');
define('BASE_URL', Yii::app()->request->baseUrl);
defined('CURRENT_PATH') or define('CURRENT_PATH', dirname(__FILE__));

// load file
//require_once(CURRENT_PATH . '/lib/factory.php');
require_once(CURRENT_PATH . '/lib/function.php');
require_once(CURRENT_PATH . '/lib/tag.php');
require_once(CURRENT_PATH . '/lib/object.php');
//require_once(CURRENT_PATH . '/lib/router.php');
require_once(CURRENT_PATH . '/lib/view.php');
require_once(CURRENT_PATH . '/lib/template.php');
require_once(CURRENT_PATH . '/lib/folder.php');

/**
 * template class
 */
class CTemplate {

    // template file of directory
    public $dir;
    // cache file of directory
    public $cacheDir;
    // html file of directory
    public $htmlDir;
    // html file name
    public $htmlFile;
    // template object
    private $_template;
    // ���������ļ���Ϣ��ֻ����һ��
    public static $configure;

    function __construct($dir = null, $cacheDir = null, $htmlDir = null) {
        $this->dir = $dir;
        $this->cacheDir = $cacheDir;
        $this->htmlDir = $htmlDir;
        $this->init();
        $this->instance();
    }

    public function init() {
        if (null == $this->dir)
            $this->dir = Yii::app()->basePath . '/template';
        if (null == $this->cacheDir)
            $this->cacheDir = Yii::app()->basePath . '/runtime/template_c';
        if (null == $this->htmlDir)
            $this->htmlDir = dirname(Yii::app()->basePath);
        if (null == self::$configure)
            self::$configure = require_once(CURRENT_PATH . '/lib/config.php');
    }

    /**
     * instantiate template class
     */
    public function instance() {
        $config = array_merge(self::$configure, array(
            'dir' => $this->dir,
            'file' => ''
        ));
        $this->_template = new template($config);
        if (null != $this->cacheDir)
            $this->_template->compile_dir = $this->cacheDir . DS;
    }

    /**
     * set cache path
     */
    public function setCachePath($cacheDir = null) {
        $this->cacheDir = $cacheDir . DS;
        $this->_template->compile_dir = $this->cacheDir;
    }

    /**
     * set cache path
     */
    public function setHtmlPath($htmlFile = null) {
        $this->htmlFile = $htmlFile;
    }

    /**
     * make temporary cache file
     */
    public function makeCache($file) {
        $this->_template->dir_compile($this->dir . DS . $file);
    }

    /**
     * make temporary cache file
     */
    public function makeHtml($file) {
        $content = $this->fetch($file);
        $htmlFile = $this->htmlDir . $this->htmlFile;
        $this->mkdirs(dirname($htmlFile));
        if (false === write_file($htmlFile, $content))
            throw new ct_Exception("$htmlFile file is not writable");
        @chmod($this->$htmlFile, 0777);
    }

    /**
     * make temporary file and get template content
     */
    public function fetch($file = null) {
        return $this->_template->fetch($file);
    }

    /**
     * display template content
     */
    public function view($file = null) {
        $this->_template->display($file);
    }

    /**
     * assign the value to template cache file
     */
    public function assign($key, $data = null) {
        return $this->_template->assign($key, $data);
    }

    public function set($data = array()) {
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                $this->assign($key, $val);
            }
        }
    }

    function mkdirs($dir, $mode = 0777) {
        if (!is_dir($dir)) {
            $this->mkdirs(dirname($dir), $mode);
            return mkdir($dir, $mode);
        }
        return true;
    }

}
