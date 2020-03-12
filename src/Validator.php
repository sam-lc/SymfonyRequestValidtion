<?php
/**
 * Class ValidatorBuilder Description
 * Created by  PhpStorm.
 * Created Time 2020-03-12 10:51
 *
 * PHP version 7.1
 *
 * @category ValidatorBuilder
 * @package  P:XZ\Validation
 * @author   lichao <lichao@xiaozhu.com>
 * @license  https://lanzu.xiaozhu.com Apache2 Licence
 * @link     https://lanzu.xiaozhu.com
 */

namespace Validation\Request;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
use Validation\Request\Exception\ValidationException;

class Validator
{
    /**
     * Fun validate Description
     * Created Time 2020-03-12 11:12
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param object $instance
     *
     * @throws \Exception
     */
    public static function validate($instance)
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $violations = $validator->validate($instance);
        if (0 !== count($violations)) {
            /**
             * @var ConstraintViolation $violation
             */
            foreach ($violations as $violation) {
                throw new ValidationException('参数异常：' . $violation->getPropertyPath() . $violation->getMessage());
            }
        }
    }
}