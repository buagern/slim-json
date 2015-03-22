<?php namespace Buagern\SlimJson;

/**
 * Middleware - JSON Middleware
 *
 * @package Slim
 * @subpackage Middleware
 * @author Settawat Jamsai <buagern@buataitom.com>
 */
class Middleware extends \Slim\Middleware {

    public function __construct()
    {
        $app = \Slim\Slim::getInstance();

        $isDebug = $app->config('debug');
        $app->config('debug', false);

        $app->error(function (\Exception $e) use ($app, $isDebug)
        {
            $response = [
                'error'   => true,
                'message' => ($e->getCode() ? '(#' . $e->getCode() . ') ' : '') . $e->getMessage(),
            ];

            if ($isDebug === true)
            {
                $response['detail'] = [];

                if ($e->getCode())
                {
                    $response['detail']['code'] = $e->getCode();
                }

                if ($e->getFile())
                {
                    $response['detail']['file'] = $e->getFile();
                }

                if ($e->getLine())
                {
                    $response['detail']['line'] = $e->getLine();
                }

                $response['detail']['message'] = $e->getMessage();
                $response['detail']['trace'] = $e->getTraceAsString();
            }

            return $app->render(500, $response);
        });

        $app->notFound(function() use ($app)
        {
            return $app->render(404, [
                'error'   => true,
                'message' => '\'' . $app->request->getPath() . '\' is not found.',
            ]);
        });

        $app->hook('slim.after.router', function () use ($app)
        {
            if ($app->response->header('Content-Type') === 'application/octet-stream')
            {
                return;
            }

            if (strlen($app->response->body()) == 0)
            {
                return $app->render(500, [
                    'error'   => true,
                    'message' => 'Empty response',
                ]);
            }
        });
    }

    public function call()
    {
        return $this->next->call();
    }
}