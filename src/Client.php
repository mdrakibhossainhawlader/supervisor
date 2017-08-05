<?php

namespace Supervisor;

use Zend\Http\Client as HttpClient;
use Zend\XmlRpc\Client as XmlRpcClient;

/**
 * Class Client
 * @package Supervisor
 *
 * @method string getAPIVersion() Return the version of the RPC API used by supervisord
 * @method string getSupervisorVersion() Return the version of the supervisor package in use by supervisord
 * @method string getIdentification() Return identifying string of supervisord
 * @method array getState() Return current state of supervisord as a struct
 * @method int getPID() Return the PID of supervisord
 * @method string readLog($offset, $length) Read length bytes from the main log starting at offset
 * @method bool clearLog() Clear the main log.
 * @method bool shutdown() Shut down the supervisor process
 * @method bool restart() Restart the supervisor process
 *
 * @method array getProcessInfo($name) Get info about a process named name
 * @method array getAllProcessInfo() Get info about all processes
 * @method bool startProcess($name, $wait = true) Start a process
 * @method array startAllProcesses($wait = true) Start all processes listed in the configuration file
 * @method array startProcessGroup($name, $wait = true) Start all processes in the group named ‘name’
 * @method bool stopProcess($name, $wait = true) Stop a process named by name
 * @method array stopProcessGroup($name, $wait = true) Stop all processes in the process group named ‘name’
 * @method array stopAllProcesses($wait = true) Stop all processes in the process list
 * @method bool signalProcess($name, $signal) Send an arbitrary UNIX signal to the process named by name
 * @method array signalProcessGroup($name, $signal) Send a signal to all processes in the group named ‘name’
 * @method array signalAllProcesses($signal) Send a signal to all processes in the process list
 * @method bool sendProcessStdin($name, $chars) Send a string of chars to the stdin of the process name
 * @method bool sendRemoteCommEvent($type, $data) Send an event that will be received by event listener subprocesses subscribing to the RemoteCommunicationEvent.
 * @method array reloadConfig() Reload the configuration
 * @method bool addProcessGroup($name) Update the config for a running process from config file
 * @method bool removeProcessGroup($name) Remove a stopped process from the active configuration
 *
 * @method string readProcessStdoutLog($name, $offset, $length) Read length bytes from name’s stdout log starting at offset
 * @method string readProcessStderrLog($name, $offset, $length) Read length bytes from name’s stderr log starting at offset
 * @method array tailProcessStdoutLog($name, $offset, $length) Provides a more efficient way to tail the (stdout) log than readProcessStdoutLog()
 * @method array tailProcessStderrLog($name, $offset, $length) Provides a more efficient way to tail the (stderr) log than readProcessStderrLog()
 * @method bool clearProcessLogs($name) Clear the stdout and stderr logs for the named process and reopen them
 * @method array clearAllProcessLogs() Clear all process log files
 *
 * @method array listMethods() Return an array listing the available method names
 * @method string methodHelp($name) Return a string showing the method’s documentation
 * @method array methodSignature($name) Return an array describing the method signature in the form
 * @method array multicall($calls) Process an array of calls, and return an array of results
 */
class Client
{
    /**
     * @var null|XmlRpcClient
     */
    private $rpcClient = null;


    /**
     * @const array All methods in supervisor xml-rpc.
     */
    const METHOD_MAP = [
        'supervisor' => [
            'getAPIVersion',
            'getSupervisorVersion',
            'getIdentification',
            'getState',
            'getPID',
            'readLog',
            'clearLog',
            'shutdown',
            'restart',
            'getProcessInfo',
            'getAllProcessInfo',
            'startProcess',
            'startAllProcesses',
            'startProcessGroup',
            'stopProcess',
            'stopProcessGroup',
            'stopAllProcesses',
            'signalProcess',
            'signalProcessGroup',
            'signalAllProcesses',
            'sendProcessStdin',
            'sendRemoteCommEvent',
            'reloadConfig',
            'addProcessGroup',
            'removeProcessGroup',
            'readProcessStdoutLog',
            'readProcessStderrLog',
            'tailProcessStdoutLog',
            'tailProcessStderrLog',
            'clearProcessLogs',
            'clearAllProcessLogs',
        ],
        'system' => [
            'listMethods',
            'methodHelp',
            'methodSignature',
            'multicall'
        ]
    ];

    /**
     * @param string $uri The xml-rpc server of supervisor
     * @param string $username The username of supervisor
     * @param string $password The password of supervisor
     * @param null | array $httpOptions  The options of Zend\Http\Client
     */
    public function __construct($uri, $username = '', $password = '', $httpOptions = ['timeout' => 60])
    {
        $httpClient = new HttpClient();
        if (!empty($username)) {
            $httpClient->setAuth($username, $password);
        }
        $this->rpcClient = new XmlRpcClient($uri, $httpClient);
    }

    /**
     * @param $name
     * @param $arguments
     */
    function __call($name, $arguments)
    {
        if (in_array($name, self::METHOD_MAP['system'])) {
            $method = 'system.' . $name;
        } else {
            $method = 'supervisor.' . $name;
        }
        $response = $this->rpcClient->call($method, $arguments);
        return $response;
    }
}
