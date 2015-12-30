<?php
/**
 * Created by PhpStorm.
 * User: Rogelio
 * Date: 10/28/2015
 * Time: 3:09 PM
 */

namespace app\models;


use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;

class ImageManager
{

    const PRODUCT_LIST = 'list';
    const PRODUCT_DETAIL = 'det';
    const FOLDER_PRODUCTS = 'p';
    const FOLDER_BANNER = 'b';

    public static $extensions = ['jpg','png','gif'];

    public static function getImageFolder($item) {
        $folder = Yii::getAlias('@webroot/images/'.$item);
        if (!file_exists($folder)) mkdir($folder, 0755, true);
        return $folder;
    }

    public static function getImagesWithPrefix($prefix, $item) {
        $result = [];
        foreach(self::$extensions as $ext) {
            $images = glob(self::getImageFolder($item) .DIRECTORY_SEPARATOR . $prefix . '*.' .$ext);
            $result = array_merge($result, $images);
        }
        return $result;
    }

    public static function getProductPrefx($product_id, $size = self::PRODUCT_LIST) {
        return $product_id . '_' . $size . '_';
    }

    public static function getProductImages($product_id, $size = self::PRODUCT_LIST) {
        $prefix = self::getProductPrefx($product_id, $size);
        return self::getImagesWithPrefix($prefix, self::FOLDER_PRODUCTS);
    }

    public static function delProductImages($product_id, $size = self::PRODUCT_LIST) {
        $list = self::getProductImages($product_id, $size);
        foreach($list as $file) unlink($file);
    }

    /**
     * @param $product_id
     * @param string $size
     * @param $sourceFile UploadedFile
     */
    public static function addProductImage($product_id, $size = self::PRODUCT_LIST, $sourceFile) {
        $prefix = self::getProductPrefx($product_id, $size);
        $ext = strtolower($sourceFile->getExtension());
        if (in_array($ext, self::$extensions)) {
            $index = 1;
            do {
                $filename = self::getImageFolder(self::FOLDER_PRODUCTS) .DIRECTORY_SEPARATOR . $prefix . $index . '.' . $ext;
                $index++;
            } while(file_exists($filename));
            $sourceFile->saveAs($filename);
        }
    }

    public static function removeFolderStr($str) {
        $prefix = Yii::getAlias('@webroot');
        $base = Url::base(true);
        if ($pos = strpos($str, $prefix)!==false) {
            return $base . str_replace('\\','/',substr($str, strlen($prefix) + $pos - 1));
        }
        return $str;
    }

    public static function removeFolder($arr) {
        return array_map(array('app\models\ImageManager', 'removeFolderStr'), $arr);
    }

    public static function deleteProductImage($name)
    {
        $file = self::getImageFolder(self::FOLDER_PRODUCTS) .DIRECTORY_SEPARATOR . $name;
        if (file_exists($file)) unlink($file);
    }

    public static function getBannerImage($id, $lang)
    {
        $prefix = $id . '_' . $lang;
        $list = self::getImagesWithPrefix($prefix, self::FOLDER_BANNER);
        if (empty($list)) {
            $prefix = $id . '_1';
            $list = self::getImagesWithPrefix($prefix, self::FOLDER_BANNER);
        }
        return count($list)>0 ? $list[0] : null;
    }

    /**
     * @param $banner_id
     * @param $sourceFile UploadedFile
     * @param $lang
     */
    public static function addBannerImage($banner_id, $sourceFile, $lang) {
        $prefix = $banner_id . '_' . $lang;
        $list = self::getImagesWithPrefix($prefix, self::FOLDER_BANNER);
        foreach($list as $fn) unlink($fn);

        $ext = strtolower($sourceFile->getExtension());
        if (in_array($ext, self::$extensions)) {
            $filename = self::getImageFolder(self::FOLDER_BANNER) .DIRECTORY_SEPARATOR . $prefix . '.' . $ext;
            $sourceFile->saveAs($filename);
        }
    }
    /**
     * @param $banner_id
     */
    public static function delBannerImage($banner_id) {
        $prefix = $banner_id . '_1';
        $list = self::getImagesWithPrefix($prefix, self::FOLDER_BANNER);
        foreach($list as $fn) unlink($fn);
    }


}