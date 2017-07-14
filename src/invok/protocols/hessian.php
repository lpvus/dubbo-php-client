<?php
namespace dubbo\invok\protocols;
require_once dirname(dirname(__FILE__))."/invoker.php";

use \dubbo\invok\Invoker;

class hessian extends Invoker{


    public function __call($method, $arguments)
    {

        if (!is_scalar($method)) {
            throw new \Exception('Method name has no scalar value');
        }

        // check
        if (is_array($arguments)) {
            // no keys
            $params = array_values($arguments);
        } else {
            throw new \Exception('Params must be given as array');
        }

        $fd = $this->connect($this->url);
        $data = $this->invoke($fd,$this->service,$method,$arguments);
        return $data;

    }

    public function connect($remote_socket, &$err = null) {
        $fd = stream_socket_client("tcp://$remote_socket", $errno, $errstr, 5);
        stream_set_timeout($fd, 5, 0);
        if($fd === false) {
            $err = "$errstr ($errno)";
        }
        return $fd;

    }

    public  function invoke($fd, $serv, $method, array $args = [], $pretty_print = true) {
        if(!is_resource($fd)) {
            return "无效连接~";
        }

        $arg_str = implode(", ", $args);
        $receive = $this->_exec_cmd($fd, "invoke $serv.$method($arg_str)");
        if($pretty_print) {
            $receive = str_replace("dubbo>", "", $receive);
            $receive = rtrim(preg_replace('/elapsed: \d+.? ms./',"",$receive));
            $receive = json_decode($receive, true);
            return $receive;
        } else {
            return $receive;
        }
    }

    public function _exec_cmd($fd, $cmd) {
        if(!is_resource($fd)) {
            return false;
        }
        fwrite($fd, trim($cmd) . "\n");

        $receive = "";
        // 以dubbo>结尾判断数据包完整
        while(!$this->endsWith($receive, "dubbo>")) {
            $receive .= fread($fd, 1 << 13);
        }
        //fclose($fd);
        return $receive;

    }

    protected function endsWith($haystack, $needle) {
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }


}



?>