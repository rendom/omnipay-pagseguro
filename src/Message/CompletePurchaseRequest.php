<?php

namespace Omnipay\PagSeguro\Message;

class CompletePurchaseRequest extends AbstractRequest
{
    protected $resource = "transactions";
    
    public function getNotificationCode()
    {
        return $this->getParameter('notificationCode');
    }
    
    public function setNotificationCode($value)
    {
        return $this->setParameter('notificationCode', $value);
    }
    
    protected function createResponse($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
    
    public function sendData($data)
    {
        $url = sprintf('%s/%s/%s?%s', $this->getEndpoint(), 
                                      $this->getResource(),
                                      $this->getNotificationCode(), 
                                      http_build_query($data, '', '&'));
                                   
        $httpResponse = $this->httpClient->request('GET', $url);
        $xml = simplexml_load_string($httpResponse->getBody()->getContents());
        
        return $this->createResponse($this->xml2array($xml));
    }
}
