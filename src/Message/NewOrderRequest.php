<?php

namespace Omnipay\PaymentechOrbital\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use SimpleXMLElement;

/**
 *  Paymentech Orbital New Order Request
 */
abstract class NewOrderRequest extends AbstractRequest
{
    abstract protected function getMessageType();

    protected function xmlData()
    {
        $data = new SimpleXMLElement('<Request><NewOrder></NewOrder></Request>');
        $newOrder = $data->NewOrder;
        $newOrder->OrbitalConnectionUsername = $this->getUsername();
        $newOrder->OrbitalConnectionPassword = $this->getPassword();

        $newOrder->IndustryType = $this->getIndustryType();
        $newOrder->MessageType = $this->getMessageType();
        $newOrder->BIN = $this->getBin();
        $newOrder->MerchantID = $this->getMerchantId();
        $newOrder->TerminalID = $this->getTerminalId();

        if ($brand = $this->getCardBrand()) {
            $newOrder->CardBrand = $this->getCardBrand();
        }



        /** echeck */
        if ( $this->getBCRtNum() ) {
            $newOrder->CurrencyCode = $this->getCurrencyCode();
            $newOrder->CurrencyExponent = $this->getCurrencyExponent();
            $newOrder->BCRtNum = $this->getBCRtNum();
            $newOrder->CheckDDA = $this->getCheckDDA();

            if ( $card = $this->getCard() ) {
                $newOrder->AVSzip = $card->getBillingPostcode();
                $newOrder->AVSaddress1 = $card->getBillingAddress1();
                $newOrder->AVSaddress2 = $card->getBillingAddress2();
                $newOrder->AVScity = $card->getBillingCity();
                $newOrder->AVSstate = $card->getBillingState();
                $newOrder->AVSphoneNum = $card->getBillingPhone();
                $newOrder->AVSname = $card->getBillingName();
                $newOrder->AVScountryCode = $card->getBillingCountry();

                $newOrder->AVSDestzip = $card->getShippingPostcode();
                $newOrder->AVSDestaddress1 = $card->getShippingAddress1();
                $newOrder->AVSDestaddress2 = $card->getShippingAddress2();
                $newOrder->AVSDestcity = $card->getShippingCity();
                $newOrder->AVSDeststate = $card->getShippingState();
                $newOrder->AVSDestphoneNum = $card->getShippingPhone();
                $newOrder->AVSDestname = $card->getShippingName();
                $newOrder->AVSDestcountryCode = $card->getShippingCountry();
            }
            unset($newOrder->AccountNum);
            unset($newOrder->Exp);
            unset($newOrder->CardSecVal);
        } elseif ($card = $this->getCard()) {
            $newOrder->AccountNum = $card->getNumber();
            $newOrder->Exp = $card->getExpiryDate('my');
            $newOrder->CurrencyCode = $this->getCurrencyCode();
            $newOrder->CurrencyExponent = $this->getCurrencyExponent();
            $newOrder->CardSecVal = $card->getCvv();

            $newOrder->AVSzip = $card->getBillingPostcode();
            $newOrder->AVSaddress1 = $card->getBillingAddress1();
            $newOrder->AVSaddress2 = $card->getBillingAddress2();
            $newOrder->AVScity = $card->getBillingCity();
            $newOrder->AVSstate = $card->getBillingState();
            $newOrder->AVSphoneNum = $card->getBillingPhone();
            $newOrder->AVSname = $card->getBillingName();
            $newOrder->AVScountryCode = $card->getBillingCountry();

            $newOrder->AVSDestzip = $card->getShippingPostcode();
            $newOrder->AVSDestaddress1 = $card->getShippingAddress1();
            $newOrder->AVSDestaddress2 = $card->getShippingAddress2();
            $newOrder->AVSDestcity = $card->getShippingCity();
            $newOrder->AVSDeststate = $card->getShippingState();
            $newOrder->AVSDestphoneNum = $card->getShippingPhone();
            $newOrder->AVSDestname = $card->getShippingName();
            $newOrder->AVSDestcountryCode = $card->getShippingCountry();

        }




        $newOrder->OrderID = $this->getOrderId();
        $newOrder->Amount = $this->getAmountInteger();
        $newOrder->Comments = $this->getComments();
        $newOrder->TxRefNum = $this->getTxRefNum();

        return $data;
    }

    public function getData()
    {
        return $this->xmlData()->asXML();
    }

    protected function createResponse($data)
    {
        if ($data->NewOrderResp) {
            return $this->response = new NewOrderResponse($this, $data);
        } elseif ($data->QuickResp) {
            return $this->response = new QuickResponse($this, $data);
        } else {
            throw new InvalidResponseException();
        }
    }
}
