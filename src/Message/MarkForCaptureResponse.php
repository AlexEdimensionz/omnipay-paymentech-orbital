<?php

namespace Omnipay\PaymentechOrbital\Message;

use SimpleXMLElement;
use Omnipay\Common\Message\AbstractResponse;

/**
 *  Paymentech Orbital Mark for Capture Response
 */
class MarkForCaptureResponse extends AbstractResponse
{
    public function getTransactionReference()
    {
        return [
            'trans_reference'   => $this->data->MarkForCaptureResp->TxRefNum->__toString(),
            'order_id'          => $this->data->MarkForCaptureResp->OrderID->__toString()
        ];
    }

    public function isApproved()
    {
        return $this->data->MarkForCaptureResp->ApprovalStatus == '1';
    }

    public function getCode()
    {
        return $this->data->MarkForCaptureResp->RespCode->__toString();
    }

    public function getMessage()
    {
        return $this->data->MarkForCaptureResp->StatusMsg->__toString();
    }

    public function getAmount()
    {
        return $this->data->MarkForCaptureResp->Amount->__toString();
    }

    public function isSuccessful()
    {
        return $this->data->MarkForCaptureResp->ProcStatus == '0';
    }
}
