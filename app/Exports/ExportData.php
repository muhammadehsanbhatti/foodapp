<?php
namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Item;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ExportData implements FromCollection, WithHeadings
{
    use Exportable;

    public function set_module($mod = "") {
        $this->module = $mod;
        return $this;
    }

    public function get_module() {
        return $this->module;
    }

    public function set_product_id($prod_id) {
        $this->product_id = $prod_id;
        return $this;
    }

    public function get_product_id() {
        return $this->product_id;
    }

    public function query()
    {
        
    }

    public function headings(): array
    {
        if ($this->module == "items") {
            return [
                'Item_Id', 'Product_id', 'Product_Name', 'Item_Name', 'Item_Description', 'Buy Price', 'Sell Price', 'Created At', 'Updated At',
            ];
        }
        else if ($this->module == "products") {
            return [
                'Id', 'Product_Name', 'Product_Description', 'Created At', 'Updated At',
            ];
        }
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if ($this->module == "items") {

            $posted_data = array();
            $posted_data['products_join'] = true;
            $posted_data['without_product'] = true;
            $posted_data['custom_select_order'] = ['items.id', 'items.product_id', 'products.name AS prod_name', 'items.name AS item_name', 'items.description', 'items.buy_price', 'items.sell_price', 'items.created_at', 'items.updated_at'];
            
            if ($this->product_id == 0 || $this->product_id == '' ) {
                $posted_data['orderBy_name'] = 'product_id';
                $posted_data['orderBy_value'] = 'ASC';
            }
            else {
                $posted_data['product_id'] = $this->product_id;
            }

            return Item::getItems($posted_data);


                // return Item::getItems([
                //     'orderBy_name' => 'product_id',
                //     'orderBy_value' => 'ASC',
                //     'without_product' => true,
                //     'products_join' => true,
                //     'custom_select_order' => ['items.id', 'items.product_id', 'products.name AS prod_name', 'items.name AS item_name', 'items.description', 'items.buy_price', 'items.sell_price', 'items.created_at', 'items.updated_at'],
                // ]);
        }
        else if ($this->module == "products") {
            return Product::query();
        }
    }
}
