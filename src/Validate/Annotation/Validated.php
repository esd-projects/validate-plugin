<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/17
 * Time: 9:25
 */

namespace ESD\Plugins\Validate\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\CachedReader;
use ESD\BaseServer\Server\Server;
use ESD\Plugins\Validate\ValidationException;
use Inhere\Validate\Validation;
use ReflectionClass;

/**
 * 验证类
 * @Annotation
 * @Target("PROPERTY")
 */
class Validated extends Annotation
{
    public $required = false;
    public $integer = false;
    public $number = false;
    public $boolean = false;
    public $float = false;
    public $string = false;
    public $accepted = false;
    public $url = false;
    public $email = false;
    public $alpha = false;
    public $alphaNum = false;
    public $alphaDash = false;
    public $isMap = false;
    public $isList = false;
    public $isArray = false;
    public $intList = false;
    public $numList = false;
    public $strList = false;
    public $arrList = false;
    public $distinct = false;
    public $date = false;
    public $json = false;
    public $file = false;
    public $image = false;
    public $ip = false;
    public $ipv4 = false;
    public $ipv6 = false;
    public $macAddress = false;
    public $md5 = false;
    public $sha1 = false;
    public $color = false;
    public $regexp;
    public $dateFormat;
    public $dateEquals;
    public $beforeDate;
    public $beforeOrEqualDate;
    public $afterOrEqualDate;
    public $afterDate;
    public $fixedSize;
    public $startWith;
    public $endWith;
    public $notIn;
    public $in;
    public $inField;
    public $mustBe;
    public $notBe;
    public $eqField;
    public $neqField;
    public $ltField;
    public $lteField;
    public $gtField;
    public $gteField;
    public $min;
    public $max;

    public function build($name)
    {
        $result = [];
        foreach ($this as $key => $value) {
            $one = [$name];
            if ($value === true) {
                $one[] = $key;
            } else if ($value != null) {
                $one[] = $key;
                $one[] = $value;
            }
            if (count($one) > 1) {
                $result[] = $one;
            }
        }
        return $result;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param $values
     * @throws ValidationException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public static function valid(ReflectionClass $reflectionClass, $values)
    {
        $validRole = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $validated = Server::$instance->getContainer()->get(CachedReader::class)->getPropertyAnnotation($property, Validated::class);
            if ($validated instanceof Validated) {
                foreach ($validated->build($property->name) as $one) {
                    $validRole[] = $one;
                }
            }
        }
        if (!empty($validRole)) {
            $validation = Validation::check($values, $validRole);
            if ($validation->failed()) {
                throw new ValidationException($validation->firstError());
            }
        }
    }
}