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

class ProductImportForm extends Model
{

    /** @var  UploadedFile */
    public $importFile;

    public function rules()
    {
        return [
            [['importFile'], 'file', 'skipOnEmpty' => true],
        ];
    }

    public function process()
    {
        if ($this->validate()) {
            if (!is_null($this->importFile)) {
                $imported = 0;
                $errors = 0;

                if (($handle = fopen($this->importFile->tempName, "r")) !== FALSE) {
                    $keys = fgetcsv($handle);
                    while (($row = fgetcsv($handle)) !== FALSE) {
                        $data = [];
                        for ($c = 0; $c < count($row); $c++)
                            $data[$keys[$c]] = $row[$c];

                        /** @var Product $product */
                        $product = (array_key_exists('id', $data) && $data['id']) ? Product::findOne($data['id']) : new Product();
                        if ($product->from_array($data)) $imported++;
                        else $errors++;
                    }
                    fclose($handle);
                }
                return "Imported: $imported, Errors: $errors";

            } else {
                return "File not found";
            }
        } else {
            return "Invalid File";
        }
    }

}