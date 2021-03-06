<?php
/**
 *    HZF:配置模块
 */
namespace HZF\Config;

use HZF\Contract\HzfObject;

class Config extends HzfObject
{
    public $configs = array();
    public function loadConfig($folder, $config_file_name = '*', $merger = false)
    {
        if (!file_exists($folder)) {
            return false;
        }

        $all_files = glob($folder . $config_file_name . '.php');
        if (!empty($all_files)) {
            foreach ($all_files as $file) {
                $filename = explode('.', basename($file))[0];
                $val      = include $file;
                $this->push($filename, $val, $merger);
            }

            return $all_files;
        } else {
            return array();
        }
    }

    public function push($key, $val = '', $merger = false)
    {
        $cover               = isset($this->configs[$key]) && !$merger;
        $this->configs[$key] = $merger ? array_merge($this->configs[$key], $val) : $val;
        return $cover;
    }

    public function get($keys = array())
    {
        if (empty($keys)) {
            return $this->configs;
        }

        $keys    = is_array($keys) ? $keys : array($keys);
        $configs = array();
        foreach ($keys as $k) {
            $configs[$k] = $this->configs[$k];
        }
        return count($configs) == 1 ? end($configs) : $configs;
    }

}
