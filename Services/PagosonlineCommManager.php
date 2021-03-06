<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PagosOnlineCommBundle\Services;

use DOMDocument;
use SoapClient;

use PaymentSuite\PagosonlineCommBundle\Lib\WSSESoap;

class PagosonlineCommManager extends SoapClient
{
    /**
     * @var string
     *
     * user pagosonline
     */
    private $userId;

    /**
     * @var string
     *
     * wsdl pagosonline
     */
    private $wsdl;

    /**
     * @var string
     *
     * password pagosonline
     */
    private $password;

    public function __construct($userId, $password, $wsdl)
    {
        $this->userId = $userId;
        $this->password = $password;
        $this->wsdl = $wsdl;
        parent::__construct($this->wsdl);
    }

    public function __doRequest($request, $location, $action, $version, $one_way = null)
    {
        //var_dump($request);
        $doc = new DOMDocument('1.0');
        $doc->loadXML($request);

        $objWSSE = new WSSESoap($doc);

        $objWSSE->addUserToken($this->userId, $this->password, false);

        //var_dump($objWSSE->saveXML());
        return parent::__doRequest($objWSSE->saveXML(), $location, $action, $version);
    }
}
