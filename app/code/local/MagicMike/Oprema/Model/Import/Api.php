<?php

class MagicMike_Oprema_Model_Import_Api
{
    protected $helper;

    public function __construct()
    {
        $this->helper = Mage::helper('magicmike_oprema');
    }

    /**
     * @param $url
     * @param null $arguments
     * @param string $method
     * @param null $data
     * @return mixed
     * @throws Exception
     */
    public function call($url, $arguments = null, $method = 'GET', $data = null)
    {
        $arguments = $this->processArgs($arguments);

        $url = $this->helper->createUrl($url . '/' . $arguments);
        $method = strtoupper($method);


        $client = new Varien_Http_Client($url);
        $client->setConfig(array('timeout' => $this->timeout))
            ->setMethod($method)
            ->setHeaders('accept-encoding', '')
           /* ->setAuth($username, $password)*/;

        if ($method === 'POST' || $method === 'PUT') {
            $json = json_encode($data, JSON_PRETTY_PRINT);
            $client->setRawData($json, 'application/json;charset=UTF-8');
        }

        try {
            $response = $client->request();
        } catch (Exception $e) {

            throw $e;
        }

        $body = json_decode($response->getBody(), true);
        if ($response->isError()) {
           Mage::log(
               time() . ': greska komunikacije',
               'oprema.log');
        }

        return $body['result'];
    }

    public function setTimeout($out){
        $this->timeout = $out;
    }

    /**
     * @param $args
     * @return string
     */
    protected function processArgs($args){
        $data = '';

        if(is_array($data)){
            $dataArray = [];
            foreach($args as $key => $value)
            {
                if(is_string($key)){
                    $key = rawurlencode($key);
                }
                else{
                    $key = '';
                }

                if(is_array($value)){
                    $value = '[' . implode(',', array_map('rawurlencode', $value)) . ']';
                }
                else{
                    $value = rawurlencode($value);
                }

                $dataArray[] = $key . $value;
            }

            $data = implode('/', $dataArray) . '/';
        }
        else{
            $data = rawurlencode((string)$args) . '/';
        }

        return $data;
    }
}