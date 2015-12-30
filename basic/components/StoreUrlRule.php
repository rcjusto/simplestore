<?php
/**
 * Created by PhpStorm.
 * User: Rogelio
 * Date: 11/9/2015
 * Time: 2:58 PM
 */

namespace app\components;


use Yii;
use yii\base\Object;
use yii\web\UrlRuleInterface;

class StoreUrlRule extends Object implements UrlRuleInterface
{

    public function createUrl($manager, $route, $params)
    {
        if ($route === 'catalog/product') {
            if (isset($params['code'])) {
                return 'product/' . $params['code'];
            } elseif (isset($params['id'])) {
                return 'product/' . $params['id'];
            }
        } else if ($route === 'catalog/category') {
            $res = '';
            if (isset($params['code'])) {
                $res = 'category/' . $params['code'];
                unset($params['code']);
            } elseif (isset($params['id'])) {
                $res = 'category/' . $params['id'];
                unset($params['id']);
            }
            foreach($params as $pId => $pVal)
                $res .= "/$pId/$pVal";
            return $res;
        } else if ($route === 'site/page') {
            if (isset($params['code'])) {
                return 'page/' . $params['code'];
            } elseif (isset($params['id'])) {
                return 'page/' . $params['id'];
            }
        }
        return false;  // this rule does not apply
    }

    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        if (preg_match('%^product/(.+)?$%', $pathInfo, $matches)) {
            if (isset($matches[1])) {
                $params = [];
                if (is_numeric($matches[1])) $params['id'] = $matches[1];
                else $params['code'] = $matches[1];
                return ['catalog/product', $params];
            }
        } else  if (preg_match('%^category/(.+)?$%', $pathInfo, $matches)) {
            if (isset($matches[1])) {
                $params = [];
                $arr = explode('/', $matches[1]);
                if (is_numeric($matches[1])) $params['id'] = $arr[0];
                else $params['code'] = $arr[0];
                for($i=1; $i<count($arr);$i++) {
                    $params[$arr[$i]] = isset($arr[$i+1]) ? $arr[$i+1] : '';
                    $i++;
                }
                return ['catalog/category', $params];
            }
        } else  if (preg_match('%^page/(.+)?$%', $pathInfo, $matches)) {
            if (isset($matches[1])) {
                $params = ['code' => $matches[1]];
                return ['site/page', $params];
            }
        }
        return false;
    }

}