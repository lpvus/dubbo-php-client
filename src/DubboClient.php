<?php
namespace Dubbo;
require_once "Register.php";
require_once "Invok/InvokerDesc.php";
require_once "Invok/Protocols/jsonrpc.php";
use \Dubbo\Invok\InvokerDesc;

/**
 * Class dubboClient
 * @package dubbo
 */
class DubboClient{
    protected $register;
    static $protocols = array();

    public function __construct($options=array())
    {
        $this->register = new Register($options);
    }

    /**
     * @param $serviceName  (service name e.g. com.xx.serviceName)
     * @param $version  (service version e.g. 1.0)
     * @param $group    (service group)
     * @param string $protocol (service protocol e.g. jsonrpc dubbo hessian)
     * @return get| specific dubbo service with your params
     */
    public function getService($serviceName, $version = '0.0.0', $group = null, $protocol = "jsonrpc", $forceVgp = false){
        $serviceVersion = !$forceVgp ? $this->register->getServiceVersion() : $version;
        $serviceGroup = !$forceVgp ? $this->register->getServiceGroup() : $group;
        $serviceProtocol = !$forceVgp ? $this->register->getServiceProtocol() : $protocol;

        $invokerDesc = new InvokerDesc($serviceName, $serviceVersion, $serviceGroup);
        $invoker = $this->register->getInvoker($invokerDesc);
        if(!$invoker){
            $invoker = $this->makeInvokerByProtocol($serviceProtocol);
            $this->register->register($invokerDesc,$invoker);
        }
        return $invoker;
    }


    /**
     * @param $protocol
     * @return get instance of specific protocol
     */
    private function makeInvokerByProtocol($protocol){

        if(array_key_exists($protocol,self::$protocols)){
            return self::$protocols[$protocol];
        }

        foreach( glob( dirname(__FILE__)."/Invok/Protocols/*.php" ) as $filename ){
            $protoName = basename($filename,".php");
            require_once $filename;
            if(class_exists("Dubbo\Invok\Protocols\\$protoName")){
                $class =  new \ReflectionClass("Dubbo\Invok\Protocols\\$protoName");
                $invoker = $class->newInstanceArgs(array());
                self::$protocols[$protoName] = $invoker;
            }
        }

        return self::$protocols[$protocol];

    }

}


?>