<?php
/**
* No modify this file !!!
*/
namespace INJQEW;
use INJQEW\Config;

class Request
{
    public $post_data=array();
    public $get_data=array();

    public function __construct()
    {
        $config=new Config();
        $this->process($config->post,'post');
        $this->process($config->get,'get');
    }


    public function  process($datas,$from)
    {
       foreach ($datas as $key => $data)
       {
           if (isset($_REQUEST[$key]))
           {
            call_user_func_array($data,$_REQUEST);
           }
           else
           {
            continue;
           }
       }
    }


}
