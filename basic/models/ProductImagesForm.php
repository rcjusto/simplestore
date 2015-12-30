<?php
/**
 * Created by PhpStorm.
 * User: Rogelio
 * Date: 10/28/2015
 * Time: 3:37 PM
 */

namespace app\models;


use yii\base\Model;
use yii\web\UploadedFile;

class ProductImagesForm extends Model
{

    /** @var  UploadedFile */
    public $listFile;
    /** @var  UploadedFile */
    public $detailFile;

    public function rules()
    {
        return [
            [['listFile','detailFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload($id)
    {
        if ($this->validate()) {
            if (!is_null($this->listFile)) {
                ImageManager::delProductImages($id, 'list');
                ImageManager::addProductImage($id, 'list', $this->listFile);
            }
            if (!is_null($this->detailFile)) {
                ImageManager::addProductImage($id, 'det', $this->detailFile);
            }
            return true;
        } else {
            return false;
        }
    }

}