<?php
/**
 * Created by PhpStorm.
 * User: Rogelio
 * Date: 11/9/2015
 * Time: 1:30 PM
 */

namespace app\components;


use app\models\Block;
use app\models\Product;
use Yii;
use yii\web\View;

class StoreUtils
{

    public static function getProductIds($limit = 10, $condition = ['>','stock',0], $sort = ['featured'=>SORT_DESC]) {
        $result = [];
        $query = Product::find();
        $query->select(['id']);
        if (!is_null($condition)) $query->where($condition);
        if (!is_null($sort)) $query->orderBy($sort);
        $query->limit($limit);
        foreach($query->all() as $prod) $result[] = $prod->id;
        return $result;
    }

    public static function renderView($view, $params) {
        /** @var View $viewObj */
        $viewObj = Yii::createObject(['class'=>View::className()]);
        return $viewObj->render($view, $params);
    }




    /**
     * Create a web friendly URL slug from a string.
     *
     * Although supported, transliteration is discouraged because
     *     1) most web browsers support UTF-8 characters in URLs
     *     2) transliteration causes a loss of information
     *
     * @author Sean Murphy <sean@iamseanmurphy.com>
     * @copyright Copyright 2012 Sean Murphy. All rights reserved.
     * @license http://creativecommons.org/publicdomain/zero/1.0/
     *
     * @param string $str
     * @param array $options
     * @return string
     */
    public static function url_slug($str, $options = array()) {
        // Make sure string is in UTF-8 and strip invalid UTF-8 characters
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

        $defaults = array(
            'delimiter' => '-',
            'limit' => null,
            'lowercase' => true,
            'replacements' => array(),
            'transliterate' => false,
        );

        // Merge options
        $options = array_merge($defaults, $options);

        $char_map = array(
            // Latin
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', '?' => 'O',
            'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', '?' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
            'ß' => 'ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', '?' => 'o',
            'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', '?' => 'u', 'ý' => 'y', 'þ' => 'th',
            'ÿ' => 'y',
            // Latin symbols
            '©' => '(c)',
            // Greek
            '?' => 'A', '?' => 'B', '?' => 'G', '?' => 'D', '?' => 'E', '?' => 'Z', '?' => 'H', '?' => '8',
            '?' => 'I', '?' => 'K', '?' => 'L', '?' => 'M', '?' => 'N', '?' => '3', '?' => 'O', '?' => 'P',
            '?' => 'R', '?' => 'S', '?' => 'T', '?' => 'Y', '?' => 'F', '?' => 'X', '?' => 'PS', '?' => 'W',
            '?' => 'A', '?' => 'E', '?' => 'I', '?' => 'O', '?' => 'Y', '?' => 'H', '?' => 'W', '?' => 'I',
            '?' => 'Y',
            '?' => 'a', '?' => 'b', '?' => 'g', '?' => 'd', '?' => 'e', '?' => 'z', '?' => 'h', '?' => '8',
            '?' => 'i', '?' => 'k', '?' => 'l', '?' => 'm', '?' => 'n', '?' => '3', '?' => 'o', '?' => 'p',
            '?' => 'r', '?' => 's', '?' => 't', '?' => 'y', '?' => 'f', '?' => 'x', '?' => 'ps', '?' => 'w',
            '?' => 'a', '?' => 'e', '?' => 'i', '?' => 'o', '?' => 'y', '?' => 'h', '?' => 'w', '?' => 's',
            '?' => 'i', '?' => 'y', '?' => 'y', '?' => 'i',
            // Turkish
            '?' => 'S', '?' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', '?' => 'G',
            '?' => 's', '?' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', '?' => 'g',
            // Russian
            '?' => 'A', '?' => 'B', '?' => 'V', '?' => 'G', '?' => 'D', '?' => 'E', '?' => 'Yo', '?' => 'Zh',
            '?' => 'Z', '?' => 'I', '?' => 'J', '?' => 'K', '?' => 'L', '?' => 'M', '?' => 'N', '?' => 'O',
            '?' => 'P', '?' => 'R', '?' => 'S', '?' => 'T', '?' => 'U', '?' => 'F', '?' => 'H', '?' => 'C',
            '?' => 'Ch', '?' => 'Sh', '?' => 'Sh', '?' => '', '?' => 'Y', '?' => '', '?' => 'E', '?' => 'Yu',
            '?' => 'Ya',
            '?' => 'a', '?' => 'b', '?' => 'v', '?' => 'g', '?' => 'd', '?' => 'e', '?' => 'yo', '?' => 'zh',
            '?' => 'z', '?' => 'i', '?' => 'j', '?' => 'k', '?' => 'l', '?' => 'm', '?' => 'n', '?' => 'o',
            '?' => 'p', '?' => 'r', '?' => 's', '?' => 't', '?' => 'u', '?' => 'f', '?' => 'h', '?' => 'c',
            '?' => 'ch', '?' => 'sh', '?' => 'sh', '?' => '', '?' => 'y', '?' => '', '?' => 'e', '?' => 'yu',
            '?' => 'ya',
            // Ukrainian
            '?' => 'Ye', '?' => 'I', '?' => 'Yi', '?' => 'G',
            '?' => 'ye', '?' => 'i', '?' => 'yi', '?' => 'g',
            // Czech
            '?' => 'C', '?' => 'D', '?' => 'E', '?' => 'N', '?' => 'R', 'Š' => 'S', '?' => 'T', '?' => 'U',
            'Ž' => 'Z',
            '?' => 'c', '?' => 'd', '?' => 'e', '?' => 'n', '?' => 'r', 'š' => 's', '?' => 't', '?' => 'u',
            'ž' => 'z',
            // Polish
            '?' => 'A', '?' => 'C', '?' => 'e', '?' => 'L', '?' => 'N', 'Ó' => 'o', '?' => 'S', '?' => 'Z',
            '?' => 'Z',
            '?' => 'a', '?' => 'c', '?' => 'e', '?' => 'l', '?' => 'n', 'ó' => 'o', '?' => 's', '?' => 'z',
            '?' => 'z',
            // Latvian
            '?' => 'A', '?' => 'C', '?' => 'E', '?' => 'G', '?' => 'i', '?' => 'k', '?' => 'L', '?' => 'N',
            'Š' => 'S', '?' => 'u', 'Ž' => 'Z',
            '?' => 'a', '?' => 'c', '?' => 'e', '?' => 'g', '?' => 'i', '?' => 'k', '?' => 'l', '?' => 'n',
            'š' => 's', '?' => 'u', 'ž' => 'z'
        );

        // Make custom replacements
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

        // Transliterate characters to ASCII
        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }

        // Replace non-alphanumeric characters with our delimiter
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

        // Remove duplicate delimiters
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

        // Truncate slug to max. characters
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

        // Remove delimiter from ends
        $str = trim($str, $options['delimiter']);

        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }

    public static function sendPendingEmails()
    {
        $cmd = Yii::getAlias("@app/yii") . ' mail/index';
        Yii::error("RCJ - $cmd");

        $cmd .= " > /dev/null 2>&1 &";

        exec($cmd, $output, $exit);

        Yii::error("RCJ - " . implode("\n", $output));

        return $exit == 0;
    }

    public static function getBlockContent($id, $language)
    {
        /** @var Block $model */
        $model = Block::findOne($id);
        return !is_null($model) ? $model->getContent($language, '') : '';
    }


}