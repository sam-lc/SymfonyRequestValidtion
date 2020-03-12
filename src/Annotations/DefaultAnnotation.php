<?php
/**
 * Class DefaultAnnotation Description
 * Created by  PhpStorm.
 * Created Time 2020-03-12 11:35
 *
 * PHP version 7.1
 *
 * @category DefaultAnnotation
 * @package  P:XZ\Validation\Annotations
 * @author   lichao <lichao@xiaozhu.com>
 * @license  https://lanzu.xiaozhu.com Apache2 Licence
 * @link     https://lanzu.xiaozhu.com
 */

namespace Validation\Request\Annotations;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class DefaultAnnotation
{
    /**
     * @Required
     */
    protected $value;

    public function __construct(array $values)
    {
        $this->value = $values['value'];
    }

    public function getValue()
    {
        return $this->value;
    }
}