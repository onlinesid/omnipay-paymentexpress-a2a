<?php

namespace Omnipay\PaymentExpress\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * PaymentExpress A2A Transaction Response
 */
class A2APurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return 1 === (int) $this->data['valid'];
    }

    public function getTransactionReference()
    {
        return null;
    }

    public function getMessage()
    {
        if (!$this->isRedirect()) {
            return (string) $this->data->URI;
        }
    }

    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return (string) $this->data->URI;
        }
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }
}
