<?php

namespace Omnipay\PaymentExpress\Message;

use SimpleXMLElement;
use Omnipay\Common\Message\AbstractRequest;

/**
 * PaymentExpress A2A Transaction Request
 * @link http://www.paymentexpress.com/developer-e-commerce-paymentexpress-hosted-account2account
 */
class A2APurchaseRequest extends AbstractRequest
{
    /**
     * Live Endpoint URL
     *
     * @var string URL
     */
    protected $liveEndpoint = 'https://sec.paymentexpress.com/pxaccess/pxa2a.aspx';

    /**
     * Test Endpoint URL
     *
     * @var string URL
     */
    protected $testEndpoint = 'https://sec.paymentexpress.com/pxaccess/pxa2a.aspx';

    /**
     * TxnType
     *
     * @var string TxnType
     */
    protected $action = 'Purchase';

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getTestMode() === true ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * Get the TxnData1
     *
     * Optional free text field that can be used to store information against a
     * transaction. Returned in the response and can be retrieved from DPS
     * reports.
     *
     * @return string
     */
    public function getTransactionData1()
    {
        return $this->getParameter('transactionData1');
    }

    /**
     * Set the TxnData1
     *
     * @param string $value Max 255 bytes
     * @return AbstractRequest
     */
    public function setTransactionData1($value)
    {
        return $this->setParameter('transactionData1', $value);
    }

    /**
     * Get the TxnData2
     *
     * Optional free text field that can be used to store information against a
     * transaction. Returned in the response and can be retrieved from DPS
     * reports.
     *
     * @return string
     */
    public function getTransactionData2()
    {
        return $this->getParameter('transactionData2');
    }

    /**
     * Set the TxnData2
     *
     * @param string $value Max 255 bytes
     * @return AbstractRequest
     */
    public function setTransactionData2($value)
    {
        return $this->setParameter('transactionData2', $value);
    }

    /**
     * Get the TxnData3
     *
     * Optional free text field that can be used to store information against a
     * transaction. Returned in the response and can be retrieved from DPS
     * reports.
     *
     * @return string
     */
    public function getTransactionData3()
    {
        return $this->getParameter('transactionData3');
    }

    /**
     * Set the TxnData3 field on the request
     *
     * @param string $value Max 255 bytes
     * @return AbstractRequest
     */
    public function setTransactionData3($value)
    {
        return $this->setParameter('transactionData3', $value);
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('amount', 'returnUrl');

        $data = new SimpleXMLElement('<GenerateRequest/>');
        $data->PxPayUserId = $this->getUsername();
        $data->PxPayKey = $this->getPassword();
        $data->TxnType = $this->action;
        $data->TxnId = $this->getTransactionId();
        $data->AmountInput = $this->getAmount();
        $data->CurrencyInput = $this->getCurrency();
        $data->MerchantReference = $this->getDescription();
        $data->TxnData1 = $this->getTransactionData1();
        $data->TxnData2 = $this->getTransactionData2();
        $data->TxnData3 = $this->getTransactionData3();
        $data->UrlSuccess = $this->getReturnUrl();
        $data->UrlFail = $this->getReturnUrl();

        if ($this->getCardReference()) {
            $data->DpsBillingId = $this->getCardReference();
        }

        return $data;
    }

    /**
     * Send request
     *
     * @param  SimpleXMLElement $data
     * @return A2APurchaseResponse
     */
    public function sendData($data)
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $data->asXML())->send();

        return $this->createResponse($httpResponse->xml());
    }

    /**
     * Create a response
     *
     * @param  SimpleXMLElement $data
     * @return A2APurchaseResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new A2APurchaseResponse($this, $data);
    }
}
