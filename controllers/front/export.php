<?php
class ExportCategoryProductsExportModuleFrontController extends ModuleFrontController{
    public function init() {
        $path = __DIR__;
        require_once __DIR__ . '/vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf(['mode' => 'c']);

        //$languages = Language::getLanguages(false);

        $subcategories = Category::getChildren($_GET['category_id'], 1);

        $products = "<style>
        table {
            font-family: sans-serif;
            border-collapse: collapse;
            border-left: 0.5mm solid #8e8e8e;
            border-bottom: 0.5mm solid #8e8e8e;
            margin-top: 20mm;
        }
        
        table.layout {
            border: 0mm solid black;
            border-collapse: collapse;
        }
        td.layout {
            text-align: center;
            border: 0mm solid black;
        }
        th {
            padding: 3mm;
            vertical-align: middle;
            border-right: 0.5mm solid #8e8e8e;
            border-top: 0.5mm solid #8e8e8e;
        }
        td {
            padding: 3mm;
            border-top: 0.5mm solid #8e8e8e;
            border-right: 0.5mm solid #8e8e8e;
            vertical-align: middle;
        }
        
        </style>
        
        <img src='".__DIR__."/intro.jpg' class='img-fluid'/>";


        if(isset($subcategories) && !empty($subcategories)){
            $products .= $this->getPDFWithSubcategories($subcategories);
        }
        else{
            $products .= $this->getPDFWithoutSubcategories($_GET['category_id']);
        }

        $mpdf->WriteHTML($products);
        $mpdf->Output();
      }


        function getPDFWithoutSubcategories($category_id){
            $products .= '
            <table>
                <tr>
                    <th colspan="8">'.$_GET['category_name'].'</th>
                </tr>
        
                <tr>
                    <th>
                        Category No.
                    </th>
                    <th>
                        Img
                    </th>
                    <th>
                        Reference
                    </th>
                    <th>
                        Item
                    </th>
                    <th>
                        Exc Vat
                    </th>
                    <th>
                        Qty
                    </th>
                    <th>
                        Total
                    </th>
                </tr>';

            $products .= $this->getProducts($category_id);
                
            $products .= '</table>';
        return $products;
    }

    function getPDFWithSubcategories($subcategories){
        foreach($subcategories as $subcategory){
            $products .= '
            <table>
                <tr>
                    <th colspan="8">'.$subcategory['name'].'</th>
                </tr>
        
                <tr>
                    <th>
                        Category No.
                    </th>
                    <th>
                        Img
                    </th>
                    <th>
                        Reference
                    </th>
                    <th>
                        Item
                    </th>
                    <th>
                        Exc Vat
                    </th>
                    <th>
                        Qty
                    </th>
                    <th>
                        Total
                    </th>
                </tr>';

                $products .= $this->getProducts($subcategory['id_category']);
                
            $products .= '</table>';
        }
        return $products;
    }

    private function getProductImag($id_product){
        $id_image = Product::getCover($id_product);
        // get Image by id
        if (sizeof($id_image) > 0) {
        $image = new Image($id_image['id_image']);
        // get image full URL
        return _PS_BASE_URL_._THEME_PROD_DIR_.$image->getExistingImgPath()."-small_default.jpg";
        }
    }

    private function getProducts($category_id){
        $product_list = Product::getProducts($_GET['language_id'], 0,0, 'id_product', 'ASC', $category_id, true);
        foreach($product_list as $product){
            $products .= "
            <tr>
                <td>"
                    .$product['id_category_default'].
                "</td>
                <td>
                    <img src='".$this->getProductImag($product['id_product'])."' clas='img-fluid'/>".
                "</td>
                <td>"
                    .$product['reference'].
                "</td>
                <td>"
                    .$product['name'].
                "</td>
                <td>"
                    .round($product['price'],2).
                "</td>
                <td>
                    
                </td>
                <td>
                    
                </td>
            </tr>";
        }
        return $products;
    }
    
  
}