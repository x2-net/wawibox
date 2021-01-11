<?php
use PHPUnit\Framework\TestCase;
require 'WawiboxFax.php';
class WawiboxFaxTest extends TestCase
{
    /**
     * Please switch the target with desired = SERVICE_TYPE_NEW -> SERVICE_TYPE_OLD
     * @var int
     */
    private $_faxServiceType = WawiboxFax::SERVICE_TYPE_OLD;

    /**
     * Passed test without error
     */
    function testNewFaxServiceSuccess(){
        $fax = new WawiboxFax( $this->_faxServiceType );
        $fax->setFaxData(['06221707075', 'hello world']);
        $response = $fax->send();
        print_r($response);
        $this->assertSame(200, intval($response["code"]));
    }

    /**
     * Passed test with error
     */
    function testNewFaxServiceErrorWithNumber(){
        $fax = new WawiboxFax( $this->_faxServiceType );
        $fax->setFaxData(['06221707075s', 'hello world']);
        $response = $fax->send();
        print_r($response);
        $this->assertSame(344, intval($response["code"]));
    }
    /**
     * Passed test with error
     */
    function testNewFaxServiceErrorWithMissingMessage(){
        $fax = new WawiboxFax( $this->_faxServiceType );
        $fax->setFaxData(['06221707075', '']);
        $response = $fax->send();
        print_r($response);
        $this->assertSame(344, intval($response["code"]));
    }
    /**
     * Passed test with error
     */
    function testNewFaxServiceErrorWithNumberAndMissingMessage(){
        $fax = new WawiboxFax( $this->_faxServiceType );
        $fax->setFaxData(['06221707075s', '']);
        $response = $fax->send();
        print_r($response);
        $this->assertSame(344, intval($response["code"]));
    }

}
