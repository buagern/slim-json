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

        $app->config('debug', false);

        $app->error(function (\Exception $e) use ($app)
        {
            return $app->render(500, [
                'error'   => true,
                'message' => ($e->getCode() ? '' : '(#' . $e->getCode() . ') ') . $e->getMessage(),
            ]);
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