<?php

namespace Omnipay\PaymentechOrbital\Message;

use function json_encode;
use SimpleXMLElement;
use Omnipay\Common\Message\AbstractResponse;

/**
 *  Paymentech Orbital Response
 */
class NewOrderResponse extends AbstractResponse
{
    public function getTransactionReference()
    {
        return [
            'trans_reference'   => $this->data->NewOrderResp->TxRefNum->__toString(),
            'order_id'          => $this->data->NewOrderResp->OrderID->__toString(),
            'auth_code'         => $this->data->NewOrderResp->AuthCode->__toString(),
            ];
    }

    public function isApproved()
    {
        return $this->data->NewOrderResp->ApprovalStatus == '1';
    }

    public function getCode()
    {
        return $this->data->NewOrderResp->RespCode->__toString();
    }

    public function getMessage()
    {
        return $this->data->NewOrderResp->StatusMsg->__toString();
    }

    public function isSuccessful()
    {
        return $this->data->NewOrderResp->ProcStatus == '0';
    }
}
