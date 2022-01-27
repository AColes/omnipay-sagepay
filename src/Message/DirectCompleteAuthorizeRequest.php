<?php

namespace Omnipay\SagePay\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Sage Pay Direct Complete Authorize Request.
 */
class DirectCompleteAuthorizeRequest extends AbstractRequest
{
    public function getService()
    {
        return static::SERVICE_DIRECT3D;
    }

    public function getData()
    {
        // Inconsistent letter case is intentional.
        // The issuing bank will return PaRes, but the merchant
        // site must send this result as PARes to Sage Pay.

        // P4.00 start
        if($this->httpRequest->request->has('cres')){
            \Log::debug("DirectCompleteAuthorizeRequest:: ".print_r($this->httpRequest->request, true));
            $data = array(
                'CRes' => $this->httpRequest->request->get('cres'), // inconsistent caps are intentional
                'VPSTxId' => $this->httpRequest->request->get('threeDSSessionData'),
            );
    
            if (empty($data['CRes']) || empty($data['VPSTxId'])) {
                \Log::debug("DirectCompleteAuthorizeRequest:: S-H-O-U-L-D    F-A-I-L");
                // throw new InvalidResponseException;
            }
            
        } else {
            $data = array(
                'MD' => $this->httpRequest->request->get('MD'),
                'PARes' => $this->httpRequest->request->get('PaRes'), // inconsistent caps are intentional
            );
    
            if (empty($data['MD']) || empty($data['PARes'])) {
                throw new InvalidResponseException;
            }
        }
        // P4.00 end

        return $data;
    }

    /**
     * @return string
     */
    public function getMd()
    {
        return $this->getParameter('md');
    }

    /**
     * Override the MD passed into the current request.
     *
     * @param string $value
     * @return $this
     */
    public function setMd($value)
    {
        return $this->setParameter('md', $value);
    }

    /**
     * @return string
     */
    public function getPaRes()
    {
        return $this->getParameter('paRes');
    }

    /**
     * Override the PaRes passed into the current request.
     *
     * @param string $value
     * @return $this
     */
    public function setPaRes($value)
    {
        return $this->setParameter('paRes', $value);
    }
}
