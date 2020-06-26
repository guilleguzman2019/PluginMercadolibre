<?php
namespace INJQEW\Admin;

use INJQEW\Config;

class Admin
{
    var $plugin_prefix;
    var $plugin_menu;

    public function __construct()
    {
        $config= new Config();
        $this->plugin_prefix= $config->plugin_prefix;
        $this->plugin_menu= $config->plugin_menu;
    }
    /*
    * Admin Menu Page
    *
    */
    public static function menu()
    {
        $admin = new Admin;
        $admin->menu_generator($admin->plugin_menu);
    }

    /**
    * Backend Menu Generator
    * @params Config::config_menu
    * @ver 1.0
    */
    public function menu_generator($params)
    {
        foreach ($params as $param)
        {
             if($param['path'][0]=="page")
            {
                $icon       = $param['icon']?$param['icon']:"antonella-icon.png";

                $capability = $param['capability']?$param['capability']:"manage_options";
                $position   = $param['position']?$param['position']:__FILE__;

                add_menu_page($param['name'], $param['name'], $capability, $param['slug'],$param['function'], plugins_url('../../assets/img/'.$icon,__FILE__), $position);
                if(isset($param['subpages']))
                {
                    foreach($param['subpages'] as $subpage)
                    {
                        add_submenu_page( $param['slug'], $subpage['name'], $subpage['name'],
            $capability, $subpage['slug'], $subpage['function']);
                    }
                }

            }
            elseif($param['path'][0]=="subpage")
            {
                add_submenu_page( $param['path'][1], $param['name'], $param['name'],
            $capability, $param['slug'], $param['function']);
            }
            elseif($param['path'][0]=="option")
            {
                add_options_page( $param['name'], $param['name'], $capability, $param['slug'], $param['function'] );
            }
        }

    }

    public function option_page()
    {
        return('Hello World !!');
    }
}
