<?php

namespace Omnipay\PagSeguro\Message;
/**
 * PagSeguro Refund Request
 *
 * https://dev.pagseguro.uol.com.br/docs/checkout-web-cancelamento-e-estorno
 *
 * <code>
 *   // Do a refund transaction on the gateway
 *   $transaction = $gateway->refund(array(
 *       'amount'                   => '10.00',
 *       'transactionReference'     => $transactionCode,
 *   ));
 *
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *   }
 * </code>
*/

class RefundRequest extends AbstractRequest
{
    protected $resource = "transactions/refunds";

    public function getData()
    {
        $this->validate('transactionReference');

        $data = [
            'transactionCode' => $this->getTransactionReference(),
        ];

        if ($this->getAmount()) {
            $data['refundValue'] = $this->getAmount();
        }

        return array_merge(parent::getData(), $data);
    }

    public function sendData($data)
    {
        $url = sprintf('%s/%s?%s', $this->getEndpoint(),
                                      $this->getResource(),
                                      http_build_query($data, '', '&'));

        $httpResponse = $this->httpClient->request('POST', $url);
        $xml = simplexml_load_string($httpResponse->getBody()->getContents());

        return $this->createResponse($this->xml2array($xml));
    }

    protected function createResponse($data)
    {
        return $this->response = new RefundResponse($this, $data);
    }
}
