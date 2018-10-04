<?php
/**
 * Do not edit this file if you want to update this module for future new versions.
 *
 * @copyright Copyright (c) 2018 MagedIn. (http://www.magedin.com)
 *
 * @author    Alexander Campos <alexander@frenet.com.br>
 */

namespace MagedIn\Frenet\Model\Shipping;

class FrenetApi
{

    protected $logger;

    /**
     * @var string
     */
    const API_BASE_URI = 'http://api-hml.frenet.com.br/';

    /**
     * @var string
     */
    const API_SHIPPING_QUOTE_URN = 'shipping/quote';

    /**
     * ServiceRepository constructor.
     *
     * @param Context $context
     */
    public function __construct( \Psr\Log\LoggerInterface $logger ) {
        $this->logger = $logger;
        $this->logger->debug("Frenet iniciado");
    }

    /**
     * @param string $token
     * @param array $data
     *
     * @return array
     * @throws \Zend_Http_Client_Exception
     */
    public function call( $token, $data, \Magento\Framework\HTTP\ZendClientFactory $zendClientFactory )
    {   

        $method = \Zend_Http_Client::POST;     
        $client = $zendClientFactory->create();

        $apiUrl = self::API_BASE_URI.self::API_SHIPPING_QUOTE_URN;
        $client->setUri($apiUrl);
        $client->setMethod($method);

        $jsonBody = json_encode($data);
        $client->setRawData($jsonBody, 'application/json');
        $client->setHeaders( [ 'Content-Type' => 'application/json', 'token' => $token ] );
        $client->setUrlEncodeBody(false);

        $this->logger->debug("API Url: ".$apiUrl);
        $this->logger->debug("token: ".$token);
        $this->logger->debug("Request: ".$jsonBody);

        $response = $client->request();

        if (!$response->isSuccessful()) {
            $rsObj = \serialize($response);
            $this->logger->critical("Response: ".$rsObj);
        }
        else {
            $bodyText = $response->getBody();
            $this->logger->debug("Body Response: ".$bodyText);

            $bodyResponse = json_decode($bodyText, true);
            
            if (isset($bodyResponse['ShippingSevicesArray'])) {
                return $bodyResponse['ShippingSevicesArray'];                
            }            

        }

        return null;
    }
}