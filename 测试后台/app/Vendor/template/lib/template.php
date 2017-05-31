<?php

class template extends view {

    public $app,
        $name,
        $compile_dir,
        $compile_file,
        $compile_force = false,
        $compile_check = true;
    protected
        $funcs = null,
        $tags = null,
        $rules = array(
            // strip comment
            '/\<\!\-\-#.+?#\-\-\>/s' => '',
            '/\<\!\-\-\{(.+?)}\-\-\>/s' => '{$1}',
            // [class::]$inst[->prop|['prop']...]
            '/{((?:\w+\:{2})?\$\w+(?: *\-\>\w+| *\[[^\]\n]+\])*);? *}/se' => 'self::_addquote(\'<?php echo $1;?>\')',
            '/{template\s+(.+?)}/i' => '<?php $this->display($1); ?>',
            '/{(switch|if|while|for)\s+(.+?)}/is' => '<?php \1 (\2) { ?>',
            '/{elseif\s+(.+?)}/is' => '<?php } elseif (\1) { ?>',
            '/{else}/i' => '<?php } else { ?>',
            '/{(default)}/' => '<?php \1: ?>',
            '/{case\s+(.+?)}/is' => '<?php case \1: ?>',
            '/{\/(?:switch|for|while|if|loop|foreach)}/i' => '<?php } ?>',
            '/{(?:loop|foreach)\s+(\S+)\s+(\S+)}/is' => '<?php if(is_array($1)) foreach($1 as $2) { ?>',
            '/{(?:loop|foreach)\s+(\S+)\s+(\S+)\s+(\S+)}/is' => '<?php if(is_array($1)) foreach($1 as $2 => $3) { ?>',
            // {[class::]CONST}
            '/{((?:\w+\:{2})?[A-Z0-9\_]+)}/s' => '<?php echo $1;?>',
            // tablename(rownum)->key
            '/{(\w+)\((.+)\)->(\w+);? *}/' => '<?php echo table(\'$1\', $2, \'$3\');?>',
            // {[class::][$ins->][$]func([arg...])}
            // {(simple expression)}
            '/{((?:(?:\w+\:{2})?(?:\$\w+\-\>)?\$?\w+ *)?\(.*?\));? *}/' => '<?php echo $1;?>',
            '/\<\?=(.+?);? *\?\>/' => '<?php echo \1; ?>'
    );

    function __construct($config = null) {
        parent::__construct($config);
        if (!defined('TEMPLATE'))
            define('TEMPLATE', $this->name);
        $this->dir .= $this->name . DS;
        $this->set_rule('/{\/(' . $this->tags . ')}/', '<?php endforeach; unset(\$_$1); ?>');
        //$this->set_rule('/{('.$this->tags.')(\s+[^}]+?)(\/?)}/e', 'self::_tag_parse(\'$1\', \'$2\', \'$3\')');
        $this->set_rule('/{(' . $this->tags . ')(\s+[^}]+?)(\/?)}/e', 'self::_tag_parse(\'$1\', \'$2\', \'$3\')');
    }

    public function set_rule($pattern, $replacement) {
        $this->rules[$pattern] = $replacement;
    }

    public function set_view($view, $app = null) {
        if (is_null($app))
            $app = $this->app;
        if ($view{0} == '/' || preg_match('#^[A-Z]:[\\\/]#i', $view)) {
            $this->file = $view;
        } else {
            $this->file = $this->dir . $view;
        }
        $view = str_replace(array(':', '*', '?', '"', '<', '>', '|'), '-', $view);
        $this->compile_file = $this->compile_dir . str_replace(array('/', '\\'), ',', $view) . '.php';
        return $this;
    }

    public function set_app($app) {
        $this->app = $app;
        return $this;
    }

    public function dir_compile($dir = null) {
        if (is_null($dir))
            $dir = $this->dir;
        $files = glob($dir . '*');
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->dir_compile($file);
            } else {
                $this->_compile(substr($file, strlen($this->dir)));
            }
        }
    }

    public function clear_compile() {
        $files = glob($this->compile_dir . '*');
        foreach ($files as $file) {
            if (is_file($file))
                @unlink($file);
        }
    }

    protected function _file() {
        if ($this->compile_force || ($this->compile_check && (!file_exists($this->compile_file) || @filemtime($this->file) > @filemtime($this->compile_file)))) {
            $this->_compile();
        }
        return $this->compile_file;
    }

    protected function _compile($view = null) {
        if ($view)
            $this->set_view($view);
        $data = file_get_contents($this->file);
        if ($data === false)
            return false;
        $data = $this->_parse($data);

        if (false === write_file($this->compile_file, $data))
            throw new ct_Exception("$this->compile_file file is not writable");
        @chmod($this->compile_file, 0777);
        return true;
    }

    private function _parse($string) {
        $string = preg_replace(array_keys($this->rules), $this->rules, $string);
        return preg_replace('/\s+\?\>(\s*)\<\?php/is', '', $string);
    }

    private static function _addquote($var) {
        return preg_replace('/\[([\w\-\.\x7f-\xff]+)\]/s', '["\1"]', stripslashes($var));
    }

    private static function _tag_parse($tag, $str, $end) {
        $return = 'r';
        $array = '';
        preg_match_all('/\s+([a-z_]+)\s*\=\s*([\"\'])(.*?)\2/i', stripslashes($str), $matches, PREG_SET_ORDER);
        foreach ($matches as $k => $v) {
            if ($v[1] == 'return') {
                $return = $v[3];
                continue;
            }
            $array .= ($k ? ',' : '') . "'" . $v[1] . "'" . ' => ' . $v[2] . $v[3] . $v[2];
        }
        $string = '<?php' . "\n\$_$tag = tag_$tag(array($array));\n";
        $string .= $end ? ("$$return = & \$_$tag;\n" . '?>') : ("if (isset(\$_{$tag}['total'])): extract(\$_$tag); \$_$tag = \$data;endif;\nforeach(\$_$tag as \$i=>\$$return): \$i++;\n" . '?>');
        return $string;
    }

}
