<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Response;

class BeforeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $params = $request->route()->parameters();
        $controller = $request->route()->getControllerClass();
        if ($instance = $this->getInstance($controller)) {
            if (count($params) > 0){
                $params = $this->getParameters($instance, reset($params));
            }else{
                $params = [null];
            }
            $instance->before(...$params);
        }
        return $next($request);
    }

    private function getInstance(?string $controller): ?Controller
    {
        if (!$controller) {
            return null;
        }
        $instance = app()->make($controller);
        return method_exists($instance, 'before') ? $instance : null;
    }
    private function getParameters(Controller $controller, Model $model): array
    {
        $reflector = new ReflectionMethod($controller, "before");
        $params = $reflector->getParameters();
        $result = [];
        foreach ($params as $param) {
            $type = $param->getType()->getName();
            $result[$param->getName()] = $this->getParameter($type) ?? $model;
        }
        return $result;
    }
    private function getParameter(string $type): ?object
    {
        return $type === Model::class ? null : app($type);
    }
}
