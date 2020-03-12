SymfonyRequestValidation
=============
Validator是symfony/validator的扩展组件

目前只支持注解格式

目的：让request验证简单便捷

Install
==================
composer require saml/symfony-request-validation

Base Usage
==================
定义验证类CreateProductRequest

注解@Assert\NotBlank 代表不能为空


更多规则详见[symfony/validator](https://symfony.com/doc/4.4/validation.html#basic-constraints)
```php
<?php

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class CreateProductRequest
{
    /**
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @Assert\NotBlank
     */
    protected $type;

    public function __construct(Request $request) 
    {
        $this->name = $request->get('name');
        $this->type = $request->get('type');
    }

    /**
     * Fun getName Description
     * Created Time 2020-03-06 13:53
     * Author lichao <lichao@xiaozhu.com>
     *
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Fun getType Description
     * Created Time 2020-03-06 13:52
     * Author lichao <lichao@xiaozhu.com>
     *
     *
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }
}

```
Controller中使用

Validation\Request\Validator::validate会自动验证$createProductRequest是否符合验证类CreateProductRequest中规定
```php
<?php
use Symfony\Component\HttpFoundation\Request;
use Validation\Request\Exception\ValidationException;
use Validation\Request\Validator;

class ProductController
{
    public function test(Request $request)
    {
        $createProductRequest = new CreateProductRequest($request);
        try{
            Validator::validate($createProductRequest);
            code...
        }catch (ValidationException $e){
            var_dump($e->getMessage());
        }
    }
}
```

RequestBuilder Use
=======================
如果不想自己去new CreateProductRequest可以尝试调RequestBuilder::buildFormRequest

RequestBuilder利用反射获取CreateProductRequest的Properties从Request中获取自动赋值
```php
<?php
namespace App\Http\Controller;

use Symfony\Component\HttpFoundation\Request;
use Validation\Request\Exception\ValidationException;
use Validation\Request\RequestBuilder;
use Validation\Request\Validator;

class ProductController
{
    public function test(Request $request)
    {
        $createProductRequest = RequestBuilder::buildFormRequest($request, CreateProductRequest::class);
        try{
            Validator::validate($createProductRequest);
            code...
        }catch (ValidationException $e){
            var_dump($e->getMessage());
        }
    }
}
```
所以RequestBuilder可以省略__construct()

支持俩个事件perInit(初始化之前),initCompleted（完成初始化）

```php
<?php

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

class CreateProductRequest
{
    /**
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @Assert\NotBlank
     */
    protected $type;


    
    public function perInit()
    {
        code...
    }
    
    public function initCompleted()
    {
        code...
    }
    /**
     * Fun getName Description
     * Created Time 2020-03-06 13:53
     * Author lichao <lichao@xiaozhu.com>
     *
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Fun getType Description
     * Created Time 2020-03-06 13:52
     * Author lichao <lichao@xiaozhu.com>
     *
     *
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }
}

```

DefaultAnnotation 默认值组件
======================
<br>只有在使用RequestBuilder的情况下才有效</br>

```php
<?php
use Symfony\Component\Validator\Constraints as Assert;
use Validation\Request\Annotations\DefaultAnnotation;

class CreateProductRequest
{
    /**
     * @Assert\NotBlank
     * @DefaultAnnotation(value="test")
     */
    protected $name;
    ....
}
```

在前端未设置参数name的时间会自动获取默认值'test',等于
Symfony\Component\HttpFoundation\Request->get('name','test');

todo
================
1. 在前端传值为json格式时Symfony\Component\HttpFoundation\Request->get('')无法获取参数
需要使用Symfony\Component\HttpFoundation\Request->getContent()获取，建议新写Request扩展组件已实现
2. Symfony\Component\HttpFoundation\Request->get('key','default');默认值只有在前端未传参数时才会获取，若传参未空则不会去获取默认值。建议新写Request扩展组件已实现
3. 中文翻译
4. 扩展验证规则