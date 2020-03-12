<?php
/**
 * Class RequestBuilder Description
 * Created by  PhpStorm.
 * Created Time 2020-03-12 14:37
 *
 * PHP version 7.1
 *
 * @category RequestBuilder
 * @package  P:XZ\Validation
 * @author   lichao <lichao@xiaozhu.com>
 * @license  https://lanzu.xiaozhu.com Apache2 Licence
 * @link     https://lanzu.xiaozhu.com
 */

namespace Validation\Request;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\Request;
use Validation\Request\Annotations\DefaultAnnotation;

class RequestBuilder
{
    /**
     * Fun buildFormRequest 创建Request类
     * Created Time 2020-03-12 12:43
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param Request $request
     * @param string $buildRequest
     *
     * @return object
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public static function buildFormRequest(Request $request, string $buildRequest)
    {
        $reflectionClass = new \ReflectionClass($buildRequest);
        $instance        = $reflectionClass->newInstanceWithoutConstructor();
        self::fire($reflectionClass, $instance, 'preInit');

        $reader = new AnnotationReader();
        foreach ($reflectionClass->getProperties() as $property) {
            $defaultAnnotation = $reader->getPropertyAnnotation(
                $property,
                DefaultAnnotation::class
            );
            if ($defaultAnnotation != null) {
                $value = $request->get($property->getName(), $defaultAnnotation->getValue());
            } else {
                $value = $request->get($property->getName());
            }
            $property->setAccessible(true);
            $property->setValue($instance, $value);
        }

        self::fire($reflectionClass, $instance, 'initCompleted');
        return $instance;
    }

    /**
     * Fun fire 触发事件
     * Created Time 2020-03-12 11:48
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param \ReflectionClass $reflectionClass
     * @param $instance
     * @param string $method
     *
     */
    private static function fire(\ReflectionClass $reflectionClass, $instance, string $method)
    {
        if ($reflectionClass->hasMethod($method)) {
            call_user_func([$instance, $method]);
        }
    }
}
