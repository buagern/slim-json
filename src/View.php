<?php namespace Buagern\SlimJson;

/**
 * View - View wrapper for JSON responses (with error code).
 *
 * @package Slim
 * @subpackage View
 * @author Settawat Jamsai <buagern@buataitom.com>
 */

class View extends \Slim\View {

    /*
     * Bitmask consisting of JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP,
     * JSON_HEX_APOS, JSON_NUMERIC_CHECK, JSON_PRETTY_PRINT,
     * JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT, JSON_UNESCAPED_UNICODE.
     * The behaviour of these constants is described on the JSON constants page.
     * http://php.net/manual/en/json.constants.php
     *
     * @var int
     */
    public $encodeOptions = 0;


    /**
     * Content-Type sent through the HTTP header.
     * Default is set to "application/json"
     *
     * @var string
     */
    public $contentType = 'application/json';


    public function render($status = 200, $data = null)
    {
        $app = \Slim\Slim::getInstance();

        $responseStatus = $app->response->getStatus();

        if ( ! is_integer($status))
        {
            $status = $responseStatus;
        }

        //$response = $this->all();

        $response = [
            'status' => $status,
            'error'  => ( ! $this->has('error')) ? false : $this->get('error'),
            'data'   => $this->all(),
        ];

        if ($this->has('error'))
        {
            //$response['error'] = false;
            unset($response['data']['error']);
        }

        //$response['status'] = $status;

		if (isset($this->data->flash) and is_object($this->data->flash))
		{
		    $flash = $this->data->flash->getMessages();

            if (count($flash))
            {
                $response['flash'] = $flash;
            }
            else
            {
                unset($response['flash'], $response['data']['flash']);
            }
		}

        $app->response->status($status);
        $app->response->header('Content-Type', $this->contentType);

        $jsonp_callback = $app->request->get('callback', null);

        if ($jsonp_callback !== null)
        {
            $app->response->body($jsonp_callback.'('.json_encode($response, $this->encodeOptions).')');
        }
        else
        {
            $app->response->body(json_encode($response, $this->encodeOptions));
        }

        $app->stop();
    }
}