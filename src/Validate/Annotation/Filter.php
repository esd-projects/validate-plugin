<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/17
 * Time: 11:53
 */

namespace ESD\Plugins\Validate\Annotation;


use DI\DependencyException;
use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\CachedReader;
use ESD\BaseServer\Server\Server;
use Inhere\Validate\Filter\Filtration;
use ReflectionClass;

/**
 * 过滤器
 * @Annotation
 * @Target("PROPERTY")
 */
class Filter extends Annotation
{
    /**
     * 默认值
     * @var mixed
     */
    public $default;
    /**
     * 返回绝对值
     * @var bool
     */
    public $abs = false;
    /**
     * 过滤非法字符并转换为int类型 支持数组
     * @var bool
     */
    public $integer = false;
    /**
     * 转换为 bool
     * @var bool
     */
    public $boolean = false;
    /**
     * 过滤非法字符,保留float格式的数据
     * @var bool
     */
    public $float = false;
    /**
     * 过滤非法字符并转换为string类型
     * @var bool
     */
    public $string = false;
    /**
     * 去除首尾空白字符，支持数组。
     * @var bool
     */
    public $trim = false;
    /**
     * 转换 \n \r\n \r 为 <br/>
     * @var bool
     */
    public $nl2br = false;
    /**
     * 字符串转换为小写
     * @var bool
     */
    public $lowercase = false;
    /**
     * 字符串转换为大写
     * @var bool
     */
    public $uppercase = false;
    /**
     * 字符串转换为蛇形风格
     * @var bool
     */
    public $snakeCase = false;
    /**
     * 字符串转换为驼峰风格
     * @var bool
     */
    public $camelCase = false;
    /**
     * 字符串日期转换时间戳
     * @var bool
     */
    public $strToTime = false;
    /**
     * URL 过滤,移除所有不符合 URL 的字符
     * @var bool
     */
    public $url = false;
    /**
     * 字符串转数组 'tag0,tag1' -> ['tag0', 'tag1']
     * @var bool
     */
    public $str2array = false;
    /**
     * 去除数组中的重复值(by array_unique())
     * @var bool
     */
    public $unique = false;
    /**
     * email 过滤,移除所有不符合 email 的字符
     * @var bool
     */
    public $email = false;
    /**
     * 去除 URL 编码不需要的字符,与 urlencode() 函数很类似
     * @var bool
     */
    public $encoded = false;
    /**
     * 清理空格
     * @var bool
     */
    public $clearSpace = false;
    /**
     * 清理换行符
     * @var bool
     */
    public $clearNewline = false;
    /**
     * 相当于使用 strip_tags()
     * @var bool
     */
    public $stripTags = false;
    /**
     * 相当于使用 htmlspecialchars() 转义数据
     * @var bool
     */
    public $escape = false;
    /**
     * 应用 addslashes() 转义数据
     * @var bool
     */
    public $quotes = false;

    public function build($name)
    {
        $result = [$name];
        $filter = [];
        foreach ($this as $key => $value) {
            if ($value === true) {
                $filter[] = $key;
            }
        }
        if (!empty($filter)) {
            $result[] = implode("|", $filter);
            return $result;
        } else {
            return null;
        }
    }


    /**
     * @param ReflectionClass $reflectionClass
     * @param $values
     * @return array
     * @throws DependencyException
     * @throws \DI\NotFoundException
     */
    public static function filter(ReflectionClass $reflectionClass, $values)
    {
        $filterRole = self::buildRole($reflectionClass);
        if (!empty($filterRole)) {
            return Filtration::make($values, $filterRole)->filtering() + $values;
        } else {
            return $values;
        }
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return array
     * @throws DependencyException
     * @throws \DI\NotFoundException
     * @throws \DI\DependencyException
     */
    public static function buildRole(ReflectionClass $reflectionClass)
    {
        $filterRole = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $filter = Server::$instance->getContainer()->get(CachedReader::class)->getPropertyAnnotation($property, Filter::class);
            if ($filter instanceof Filter) {
                $one = $filter->build($property->name);
                if (!empty($one)) {
                    $filterRole[] = $one;
                }
            }
        }
        return $filterRole;
    }
}