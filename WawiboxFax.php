<?php

// How could the old implementation be replaced by a new implementation?
// How to make it switchable, to make it possible to use the old service API if
// required? Maybe by using a design pattern?

abstract class WawiboxFaxService {
    /**
     * Fax set successfully code
     */
    protected const FAX_SENT_SUCCESSFULLY   = 200;

    /**
     * Fax Error code
     */
    protected const FAX_SENT_ERROR  = 344; // <- HP Error code

    /**
     * Merge error messages
     * @var array
     */
    private $_message = [];

    /**
     * Abstract function send
     * @return array
     */
    abstract function send(): array;

    /**
     * Check and Confirm Fax data
     * @param array $data
     * @return bool
     */
    protected function confirmedData(array $data = []) :bool
    {

        $_error = false;

        if( count($data) ){
            if( !is_numeric($data[0]) ){
                $_error = true;
                $this->setErrorMessage('Error with fax number');
            }

            if( empty($data[1]) ){
                $_error = true;
                $this->setErrorMessage('Missing fax message');
            }
        }
        else {
            $_error = true;
            $this->setErrorMessage('Missing fax data!');
        }

        return !$_error;

    }

    private function setErrorMessage( string $message ) :void
    {
        array_push($this->_message, $message);
    }

    protected function getErrorMessage(): array
    {
        return $this->_message;
    }

}


class FaxOld extends WawiboxFaxService {

    private $_faxData = [];

    function __construct( array $faxData )
    {
        $this->_faxData = $faxData;
    }

    /**
     * Send fax if fax data confirmed
     */
    function send(): array
    {
        if( !self::confirmedData( $this->_faxData) ){
            return ["code" => self::FAX_SENT_ERROR, "message" => $this->getErrorMessage() ];
        }

        // TODO: send Fax .
        return ["code"=>self::FAX_SENT_SUCCESSFULLY, "message"=>"Fax sent successfully" ];

    }
}

class FaxNew extends WawiboxFaxService {

    private $_faxData = [];

    function __construct( array $faxData = [] )
    {
        $this->_faxData = $faxData;
    }

    /**
     * Send fax if fax data confirmed
     */
    function send(): array
    {

        if( !self::confirmedData( $this->_faxData ) ){
            return ["code" => self::FAX_SENT_ERROR, "message" => $this->getErrorMessage() ];
        }

        // TODO: send Fax .
        return ["code"=>self::FAX_SENT_SUCCESSFULLY, "message"=>"Fax sent successfully" ];

    }
}

class WawiboxFax {

    public const SERVICE_TYPE_OLD  = 0;
    public const SERVICE_TYPE_NEW  = 1;
    private $_serviceType = self::SERVICE_TYPE_OLD;
    private $_faxData = [];

    function __construct( int $serviceType = self::SERVICE_TYPE_OLD )
    {
        $this->_serviceType = $serviceType;
    }

    /**
     * First revision return type was an array and this is 'Incorrect!!!'
     * Method should be not return type
     * @param array $data
     */
    function setFaxData( array $data = [] ) :void
    {
        $this->_faxData = $data;
    }

    /**
     * Method little big simplified
     */
    function getService(): WawiboxFaxService
    {
        if( $this->_serviceType === self::SERVICE_TYPE_OLD ){
            return new FaxOld( $this->_faxData );
        }
        return new FaxNew( $this->_faxData );
    }

    /**
     * Send with response
     */
    function send(): array
    {
        return $this->getService()->send();
    }
}


//$fax = new WawiboxFax(WawiboxFax::SERVICE_TYPE_OLD );
//$fax->setFaxData(['06221707075', 'hello world']);
//$response = $fax->send();
//
//echo $response["message"];




