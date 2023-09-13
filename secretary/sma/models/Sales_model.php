<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

	public function calculate_sales()
	{
		/*----Update QTY Barang-----*/
		//update sma_products set quantity = (select sum(quantity) from sma_purchase_items where product_id = sma_products.id)-(select sum(quantity) from sma_sale_items where product_id = sma_products.id)
        // $q = $this->db->get_where('sales');
		// print_r($q);
        // if ($q->num_rows() > 0) {
            // foreach (($q->result()) as $row) {
                // $this->site->syncQuantity($row->id);
				// echo $row->id."<br/>";
				// sleep(5);
            // }
        // }
        // return FALSE;
    }
    
	public function getProductNames($term, $warehouse_id, $limit = 5)
    {
        $this->db->select('products.id, code, name, type, warehouses_products.quantity,unit,unit1,unit_c1,unit2,unit_c2,unit_primary, price, tax_rate, tax_method, products.cost')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
        if ($this->Settings->overselling) {
            $this->db->where("hapus=0 and (name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        } else {
            $this->db->where("hapus=0 and (products.track_quantity = 0 OR warehouses_products.quantity > 0) AND warehouses_products.warehouse_id = '" . $warehouse_id . "' AND "
                . "(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        }
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getProductComboItems($pid, $warehouse_id = NULL)
    {
        $this->db->select('products.id as id, combo_items.item_code as code, combo_items.quantity as qty, products.name as name, warehouses_products.quantity as quantity')
            ->join('products', 'products.code=combo_items.item_code', 'left')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('combo_items.id');
        if($warehouse_id) {
            $this->db->where('warehouses_products.warehouse_id', $warehouse_id);
        }
        $q = $this->db->get_where('combo_items', array('combo_items.product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }
    
	public function getProductLastPrice($pid, $customer_id)
    {
		$this->db->select('sale_items.net_unit_price as last_price')
            ->join('sales', 'sales.id=sale_items.sale_id', 'left')
			->order_by('date', 'desc');
        $this->db->where('customer_id = '.$customer_id);
        // $this->db->get_where('sale_items.product_id = '.$pid);
        // $q = $this->db->get_where('sales', array('customer_id'=>$customer_id);
        $q = $this->db->get_where('sale_items', array('product_id' => $pid));
		// $this->output->enable_profiler(TRUE);
		// $this->db->last_query(); 
		// echo $this->db->get_compiled_select();
		// print_r($q);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
				// print_r($row->last_price);
				return $row->last_price;
            }

            return $data;
        }
        return FALSE;
    }

    public function getProductByCode($code)
    {
        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function syncQuantity($sale_id)
    {
        if ($sale_items = $this->getAllInvoiceItems($sale_id)) {
            foreach ($sale_items as $item) {
                $this->site->syncProductQty($item->product_id, $item->warehouse_id);
                if (isset($item->option_id) && !empty($item->option_id)) {
                    $this->site->syncVariantQty($item->option_id, $item->warehouse_id);
                }
            }
        }
    }
    
	public function syncQuantityDelivery($do_id)
    {
        if ($delivery_item = $this->getAllDeliveryItems($do_id)) {
            foreach ($delivery_item as $item) {
                $balance_qty = 0;
				$q = $this->db->get_where('products',array('id' =>$item->product_id),1);
				if ($q->num_rows() > 0) {
					$data = $q->row();
					$balance_qty=$data->quantity;
				}
					if ($item->quantity != 0)
					{
						$product_qty = $balance_qty+$item->b_quantity-$item->quantity;	
					} else {
						$product_qty = $balance_qty-$item->b_quantity;
					}
				if ($this->db->update('products', array('quantity' => ($product_qty)), array('id' => $item->product_id))) {
					// Search product exists
					$q = $this->db->get_where('warehouses_products',array('product_id' =>$item->product_id, 'warehouse_id' => $item->warehouse_id ));
					if ($q->num_rows() > 0) {
						// Get Balance Before
							$data = $q->row();
							$wh_balance_qty = $data->quantity;
							// echo $wh_balance_qty;
						if ($item->quantity == 0)
						{
							// Update warehouse_product balance = balance_before - delivery_quantity not confirmed yet
							$this->db->update('warehouses_products', array('quantity' => ($wh_balance_qty-$item->b_quantity)), array('product_id' => $item->product_id, 'warehouse_id' => $item->warehouse_id));
						} else 
						{
							// Update warehouse_product balance = balance_before + delivered_quantity after confirmed delivery
							$this->db->update('warehouses_products', array('quantity' => ($wh_balance_qty+($item->b_quantity - $item->quantity))), array('product_id' => $item->product_id, 'warehouse_id' => $item->warehouse_id));
						}
					} else {
						if(!$wh_balance_qty) { $wh_balance_qty = 0; }
						// New Item delivery = insert to warehouse_product using delivery_quantity;
						$this->db->insert('warehouses_products', array('quantity' => (0-$item->b_quantity), 'product_id' => $item->product_id, 'warehouse_id' => $item->warehouse_id));
					}
				} 
            }
        }
    }
	
	public function syncQuantityReturn($return_id)
    {
        if ($return_item = $this->getAllReturnItems($return_id)) {
            foreach ($return_item as $item) {
                $balance_qty = 0;
				$q = $this->db->get_where('products',array('id' =>$item->product_id),1);
				if ($q->num_rows() > 0) {
					$data = $q->row();
					$balance_qty=$data->quantity;
				}
						$product_qty = $balance_qty+$item->quantity;	
				if ($this->db->update('products', array('quantity' => ($product_qty)), array('id' => $item->product_id))) {
					// Search product exists
					$q = $this->db->get_where('warehouses_products',array('product_id' =>$item->product_id, 'warehouse_id' => $item->warehouse_id ));
					if ($q->num_rows() > 0) {
						// Get Balance Before
							$data = $q->row();
							$wh_balance_qty = $data->quantity;
							// echo $wh_balance_qty;
							// Update warehouse_product balance = balance_before + delivered_quantity after confirmed delivery
							$this->db->update('warehouses_products', array('quantity' => ($wh_balance_qty+($item->b_quantity - $item->quantity))), array('product_id' => $item->product_id, 'warehouse_id' => $item->warehouse_id));
					} else {
						if(!$wh_balance_qty) { $wh_balance_qty = 0; }
						// New Item delivery = insert to warehouse_product using delivery_quantity;
						$this->db->insert('warehouses_products', array('quantity' => ($item->b_quantity), 'product_id' => $item->product_id, 'warehouse_id' => $item->warehouse_id));
					}
				} 
            }
        }
    }
	// public function getBalanceQuantity($product_id, $warehouse){
		
        // $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse), 1);
	// }
    
	public function getProductQuantity($product_id, $warehouse)
    {
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse), 1);
        if ($q->num_rows() > 0) {
            return $q->row_array(); //$q->row();
        }
        return FALSE;
    }

    public function getProductOptions($product_id, $warehouse_id, $all = NULL)
    {
        $this->db->select('product_variants.id as id, product_variants.name as name, product_variants.price as price, product_variants.quantity as total_quantity, warehouses_products_variants.quantity as quantity')
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left')
            //->join('warehouses', 'warehouses.id=product_variants.warehouse_id', 'left')
            ->where('product_variants.product_id', $product_id)
            ->where('warehouses_products_variants.warehouse_id', $warehouse_id)
            ->group_by('product_variants.id');
            if( ! $this->Settings->overselling && ! $all) {
                $this->db->where('warehouses_products_variants.quantity >', 0);
            }
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductVariants($product_id)
    {
        $q = $this->db->get_where('product_variants', array('product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getItemByID($id)
    {

        $q = $this->db->get_where('sale_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getAllInvoiceItems($sale_id)
    {
        $this->db->select('b_quantity,sale_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, products.unit, products.details as details, product_variants.name as variant, delivery_items.b_quantity as qty_kirim')
            ->join('products', 'products.id=sale_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=sale_items.tax_rate_id', 'left')
            ->join('delivery_items', 'delivery_items.product_id=sale_items.product_id and delivery_items.sales_id=sale_items.sale_id ', 'left')
            ->group_by('sale_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAllDeliveryItems($delivery_id)
    {
        $this->db->select('delivery_items.*')
            ->group_by('delivery_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('delivery_items', array('do_id' => $delivery_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAllDeliveryItemsBySalesID($sales_id)
    {
        // $this->db->select('delivery_items.id, delivery_items.product_id, sale_items.product_name, sale_items.product_code, sale_items.quantity, sale_items.serial_no, sum(delivery_items.quantity)');
        // $this->db->join('sale_items', 'sale_items.product_id=delivery_items.product_id and sale_items.sales_id=delivery_items.sales_id', 'left');
            // ->group_by('delivery_items.product_id')
            // ->order_by('id', 'asc');
        // $q = $this->db->get_where('delivery_items', array('sales_id' => $sales_id));
        $q = $this->db->query('SELECT sma_delivery_items.product_id,sma_delivery_items.sales_id,sma_sale_items.sale_id, sma_sale_items.product_code, sma_sale_items.product_name, sma_sale_items.product_type, sma_sale_items.net_unit_price, sma_sale_items.unit_price, sma_sale_items.real_unit_price, sma_sale_items.quantity, sum(sma_delivery_items.quantity) as delivered_quantity FROM `sma_delivery_items` join sma_sale_items on sma_sale_items.sale_id = sma_delivery_items.sales_id and sma_sale_items.product_id=sma_delivery_items.product_id where sma_delivery_items.sales_id = '.$sales_id.' GROUP by sma_delivery_items.sales_id,sma_delivery_items.product_id');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllReturnItems($return_id)
    {
        $this->db->select('return_items.*, products.details as details, product_variants.name as variant')
            ->join('products', 'products.id=return_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=return_items.option_id', 'left')
            ->group_by('return_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('return_items', array('return_id' => $return_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getAllInvoiceItemsWithDetails($sale_id)
    {
        $this->db->select('sale_items.id, sale_items.product_id, sale_items.product_name, sale_items.product_code, sale_items.quantity, sale_items.serial_no, sale_items.tax, sale_items.net_unit_price, sale_items.item_tax, sale_items.item_discount, sale_items.subtotal, products.details');
        $this->db->join('products', 'products.id=sale_items.product_id', 'left');
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
    
	public function getAllDeliveryItemsWithDetails($do_id)
    {
        $this->db->select('delivery_items.id, delivery_items.product_id, products.name as product_name, products.code as product_code, delivery_items.b_quantity, delivery_items.quantity, products.details');
        $this->db->join('products', 'products.id=delivery_items.product_id', 'left');
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('delivery_items', array('do_id' => $do_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getInvoiceByID($id)
    {
        $q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getReturnByID($id)
    {
        $q = $this->db->get_where('return_sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getReturnBySID($sale_id)
    {
        $q = $this->db->get_where('return_sales', array('sale_id' => $sale_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductOptionByID($id)
    {
        $q = $this->db->get_where('product_variants', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPurchasedItems($product_id, $warehouse_id, $option_id = NULL)
    {
        $orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->select('id, quantity, quantity_balance, net_unit_cost, item_tax');
        $this->db->where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->where('quantity_balance !=', 0);
        if ($option_id) {
            $this->db->where('option_id', $option_id);
        }
        $this->db->group_by('id');
        $this->db->order_by('date', $orderby);
        $this->db->order_by('purchase_id', $orderby);
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getConfirmDeliveryItems($id)
    {
        $q = $this->db->get_where('delivery_items', array('do_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getConfirmDeliverySaleItems($id)
    {
        $q = $this->db->get_where('delivery_items', array('sales_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getSisaDeliverySaleItems($id)
    {
		//select a.product_id,a.quantity,b.product_id,b.TotalSold from sma_sale_items a INNER JOIN ( select product_id,sales_id, sum(quantity) TotalSold from sma_delivery_items where sales_id=18259 group by product_id,sales_id ) b on a.product_id = b.product_id and a.sale_id=b.sales_id
		$q =$this->db->query('select a.product_id,a.quantity,a.warehouse_id,b.product_id,b.TotalSold from sma_sale_items a INNER JOIN ( select product_id,sales_id, sum(quantity) TotalSold from sma_delivery_items where sales_id='.$id.' group by product_id,sales_id ) b on a.product_id = b.product_id and a.sale_id=b.sales_id');
		// $this->db->from('sale_items a');
		// $this->db->join('(select product_id,sales_id, sum(quantity) TotalSold from sma_delivery_items where sales_id=18259 group by product_id,sales_id) b', 'sales_id=18259', 'inner');
        
		// $this->db->where("a.product_id = b.product_id and a.sale_id=18259");
       
        // $q = $this->db->get();
        // $q = $this->db->get_where('delivery_items', array('sales_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function updateOptionQuantity($option_id, $quantity)
    {
        if ($option = $this->getProductOptionByID($option_id)) {
            $nq = $option->quantity - $quantity;
            if ($this->db->update('product_variants', array('quantity' => $nq), array('id' => $option_id))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function addOptionQuantity($option_id, $quantity)
    {
        if ($option = $this->getProductOptionByID($option_id)) {
            $nq = $option->quantity + $quantity;
            if ($this->db->update('product_variants', array('quantity' => $nq), array('id' => $option_id))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getProductWarehouseOptionQty($option_id, $warehouse_id)
    {
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id, 'warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateProductOptionQuantity($option_id, $warehouse_id, $quantity, $product_id)
    {
        if ($option = $this->getProductWarehouseOptionQty($option_id, $warehouse_id)) {
            $nq = $option->quantity - $quantity;
            if ($this->db->update('warehouses_products_variants', array('quantity' => $nq), array('option_id' => $option_id, 'warehouse_id' => $warehouse_id))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        } else {
            $nq = 0 - $quantity;
            if ($this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $nq))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        }
        return FALSE;
    }

    public function addSale($data = array(), $items = array(), $payment = array())
    {

        $cost = $this->site->costing($items);
        // $this->sma->print_arrays($cost);

        if ($this->db->insert('sales', $data)) {
            $sale_id = $this->db->insert_id();
            if ($this->site->getReference('so') == $data['reference_no']) {
                $this->site->updateReference('so');
            }
            foreach ($items as $item) {

                $item['sale_id'] = $sale_id;
                $this->db->insert('sale_items', $item);
                $sale_item_id = $this->db->insert_id();
                if ($data['sale_status'] == 'completed' && $this->site->getProductByID($item['product_id'])) {

                    $item_costs = $this->site->item_costing($item);
                    foreach ($item_costs as $item_cost) {
                        $item_cost['sale_item_id'] = $sale_item_id;
                        $item_cost['sale_id'] = $sale_id;
                        if(! isset($item_cost['pi_overselling'])) {
                            $this->db->insert('costing', $item_cost);
                        }
                    }

                }
            }

            if ($data['sale_status'] == 'completed') {
                $this->site->syncPurchaseItems($cost);
            }

            if ($data['payment_status'] == 'partial' || $data['payment_status'] == 'paid' && !empty($payment)) {
                $payment['sale_id'] = $sale_id;
                if ($payment['paid_by'] == 'gift_card') {
                    $this->db->update('gift_cards', array('balance' => $payment['gc_balance']), array('card_no' => $payment['cc_no']));
                    unset($payment['gc_balance']);
                    $this->db->insert('payments', $payment);
                } else {
                    $this->db->insert('payments', $payment);
                }
                if ($this->site->getReference('pay') == $payment['reference_no']) {
                    $this->site->updateReference('pay');
                }
                $this->site->syncSalePayments($sale_id);

            }

            // $this->site->syncQuantity($sale_id);
            $this->sma->update_award_points($data['grand_total'], $data['customer_id'], $data['created_by']);
            return $sale_id;

        }

        return false;
    }
	
	public function add_confirm_delivery($items = array(),$konfirmasi = array(),$do_id = "")
	{
		// $this->db->delete('delivery_items', array('do_id' => $do_id));
		
		foreach ($items as $item) {
			$qty = str_replace(",","",$item['quantity']);
			$this->db->update('delivery_items', array('quantity' => $qty),array('do_id' => $do_id, 'product_id' => $item['product_id']));
			$this->syncQuantityDelivery($do_id);
		}
		$this->db->update('deliveries',$konfirmasi,array('id' => $do_id));
	}
	
	public function addUpdatePrice($data = array(), $items = array())
    {

        $cost = $this->site->costing($items);
        // $this->sma->print_arrays($cost);

        if ($this->db->insert('sales_update_price', $data)) {
            $sale_id = $this->db->insert_id();
            // if ($this->site->getReference('so') == $data['reference_no']) {
                // $this->site->updateReference('so');
            // }
            foreach ($items as $item) {

                $item['sales_update_id'] = $sale_id;
                $this->db->insert('sales_update_price_items', $item);
                $this->db->update('products', array('price' => $item['unit_price']), array('id'=>$item['product_id']));
                // $sale_item_id = $this->db->insert_id();
                // if ($data['sale_status'] == 'completed' && $this->site->getProductByID($item['product_id'])) {

                    // $item_costs = $this->site->item_costing($item);
                    // foreach ($item_costs as $item_cost) {
                        // $item_cost['sale_item_id'] = $sale_item_id;
                        // $item_cost['sale_id'] = $sale_id;
                        // if(! isset($item_cost['pi_overselling'])) {
                            // $this->db->insert('costing', $item_cost);
                        // }
                    // }

                // }
            }

            // if ($data['sale_status'] == 'completed') {
                // $this->site->syncPurchaseItems($cost);
            // }

            // if ($data['payment_status'] == 'partial' || $data['payment_status'] == 'paid' && !empty($payment)) {
                // $payment['sale_id'] = $sale_id;
                // if ($payment['paid_by'] == 'gift_card') {
                    // $this->db->update('gift_cards', array('balance' => $payment['gc_balance']), array('card_no' => $payment['cc_no']));
                    // unset($payment['gc_balance']);
                    // $this->db->insert('payments', $payment);
                // } else {
                    // $this->db->insert('payments', $payment);
                // }
                // if ($this->site->getReference('pay') == $payment['reference_no']) {
                    // $this->site->updateReference('pay');
                // }
                // $this->site->syncSalePayments($sale_id);

            // }

            // $this->site->syncQuantity($sale_id);
            // $this->sma->update_award_points($data['grand_total'], $data['customer_id'], $data['created_by']);
            return true;

        }

        return false;
    }

    public function updateSale($id, $data, $items = array())
    {
        $this->resetSaleActions($id);

        if ($data['sale_status'] == 'completed') {
            $cost = $this->site->costing($items);
        }

        if ($this->db->update('sales', $data, array('id' => $id)) && $this->db->delete('sale_items', array('sale_id' => $id))) {

            foreach ($items as $item) {

                $item['sale_id'] = $id;
                $this->db->insert('sale_items', $item);
                $sale_item_id = $this->db->insert_id();
                if ($data['sale_status'] == 'completed' && $this->site->getProductByID($item['product_id'])) {
                    $item_costs = $this->site->item_costing($item);
                    foreach ($item_costs as $item_cost) {
                        $item_cost['sale_item_id'] = $sale_item_id;
                        $item_cost['sale_id'] = $id;
                        if(! isset($item_cost['pi_overselling'])) {
                            $this->db->insert('costing', $item_cost);
                        }
                    }
                }

            }

            if ($data['sale_status'] == 'completed') {
                $this->site->syncPurchaseItems($cost);
            }

            // $this->site->syncQuantity($id);
            $this->sma->update_award_points($data['grand_total'], $data['customer_id'], $data['created_by']);
            return true;

        }
        return false;
    }

    public function deleteSale($id)
    {
        $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('payments', array('sale_id' => $id)) &&
        $this->db->delete('sale_items', array('sale_id' => $id)) &&
        $this->db->delete('sales', array('id' => $id))) {
            if ($return = $this->getReturnBySID($id)) {
                $this->deleteReturn($return->id);
            }
            $this->resetDeliveryAction($id);
            return true;
        }
        return FALSE;
    }

    public function resetSaleActions($id)
    {
        $sale = $this->getInvoiceByID($id);
        $items = $this->getAllInvoiceItems($id);
        foreach ($items as $item) {

            if ($sale->sale_status == 'completed') {
                if ($costings = $this->getCostingLines($item->id, $item->product_id)) {
                    $quantity = $item->quantity;
                    foreach ($costings as $cost) {
                        if ($cost->quantity >= $quantity) {
                            $qty = $cost->quantity - $quantity;
                            $bln = $cost->quantity_balance ? $cost->quantity_balance + $quantity : $quantity;
                            $this->db->update('costing', array('quantity' => $qty, 'quantity_balance' => $bln), array('id' => $cost->id));
                            $quantity = 0;
                        } elseif ($cost->quantity < $quantity) {
                            $qty = $quantity - $cost->quantity;
                            $this->db->delete('costing', array('id' => $cost->id));
                            $quantity -= $qty;
                        }
                        if ($quantity == 0) {
                            break;
                        }
                    }
                    $this->updatePurchaseItem($cost->purchase_item_id, $item->quantity, $cost->sale_item_id);
                }
            }

        }
        $this->sma->update_award_points($sale->grand_total, $sale->customer_id, $sale->created_by, TRUE);
        return $items;
    }
	
	public function resetDeliveryAction($id)
    {
        // $sale = $this->getInvoiceByID($id);
        $deliverys = $this->getAllDeliveryByID($id);
			foreach ($deliverys as $delivery) {
				$this->deleteDelivery($delivery->id);
			}

        // $this->sma->update_award_points($sale->grand_total, $sale->customer_id, $sale->created_by, TRUE);
        // return $items;
    }

    public function deleteReturn($id)
    {
        if ($this->db->delete('return_items', array('return_id' => $id)) && $this->db->delete('return_sales', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function updatePurchaseItem($id, $qty, $sale_item_id)
    {
        if ($id) {
            if($pi = $this->getPurchaseItemByID($id)) {
                $bln = $pi->quantity_balance + $qty;
                $this->db->update('purchase_items', array('quantity_balance' => $bln), array('id' => $id));
            }
        } else {
            if ($sale_item = $this->getSaleItemByID($sale_item_id)) {
                $option_id = isset($sale_item->option_id) && !empty($sale_item->option_id) ? $sale_item->option_id : NULL;
                $clause = array('purchase_id' => NULL, 'transfer_id' => NULL, 'product_id' => $sale_item->product_id, 'warehouse_id' => $sale_item->warehouse_id, 'option_id' => $option_id);
                if ($pi = $this->site->getPurchasedItem($clause)) {
                    $quantity_balance = $pi->quantity_balance+$qty;
                    $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), $clause);
                } else {
                    $clause['quantity'] = 0;
                    $clause['quantity_balance'] = $qty;
                    $this->db->insert('purchase_items', $clause);
                }
            }
        }
    }

    public function getPurchaseItemByID($id)
    {
        $q = $this->db->get_where('purchase_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	public function returnSale_updateProdut($item)
	{
		$balance_qty = 0;
		$q = $this->db->get_where('products',array('id' =>$item->product_id),1);
		if ($q->num_rows() > 0) {
			$data = $q->row();
			$balance_qty=$data->quantity;
		}
			if ($item->quantity != 0)
			{
				$product_qty = $balance_qty+$item->quantity;	
			} else {
				$product_qty = $balance_qty-$item->b_quantity;
			}
		if ($this->db->update('products', array('quantity' => ($product_qty)), array('id' => $item->product_id))) {
			// Search product exists
			$q = $this->db->get_where('warehouses_products',array('product_id' =>$item->product_id, 'warehouse_id' => $item->warehouse_id ));
			if ($q->num_rows() > 0) {
				// Get Balance Before
					$data = $q->row();
					$wh_balance_qty = $data->quantity;
					// echo $wh_balance_qty;
				if ($item->quantity == 0)
				{
					// Update warehouse_product balance = balance_before - delivery_quantity not confirmed yet
					$this->db->update('warehouses_products', array('quantity' => ($wh_balance_qty-$item->b_quantity)), array('product_id' => $item->product_id, 'warehouse_id' => $item->warehouse_id));
				} else 
				{
					// Update warehouse_product balance = balance_before + delivered_quantity after confirmed delivery
					$this->db->update('warehouses_products', array('quantity' => ($wh_balance_qty+($item->b_quantity - $item->quantity))), array('product_id' => $item->product_id, 'warehouse_id' => $item->warehouse_id));
				}
			} else {
				if(!$wh_balance_qty) { $wh_balance_qty = 0; }
				// New Item delivery = insert to warehouse_product using delivery_quantity;
				$this->db->insert('warehouses_products', array('quantity' => (0-$item->b_quantity), 'product_id' => $item->product_id, 'warehouse_id' => $item->warehouse_id));
			}
		} 
	}
    
	public function returnSale($data = array(), $items = array(), $payment = array())
    {

        foreach ($items as $item) {
            if ($costings = $this->getCostingLines($item['sale_item_id'], $item['product_id'])) {
                $quantity = $item['quantity'];
                foreach ($costings as $cost) {
                    if ($cost->quantity >= $quantity) {
                        $qty = $cost->quantity - $quantity;
                        $bln = $cost->quantity_balance && $cost->quantity_balance >= $quantity ? $cost->quantity_balance - $quantity : 0;
                        $this->db->update('costing', array('quantity' => $qty, 'quantity_balance' => $bln), array('id' => $cost->id));
                        $quantity = 0;
                    } elseif ($cost->quantity < $quantity) {
                        $qty = $quantity - $cost->quantity;
                        $this->db->delete('costing', array('id' => $cost->id));
                        $quantity = $qty;
                    }
                }
                $this->updatePurchaseItem($cost->purchase_item_id, $item['quantity'], $cost->sale_item_id);
            }
			

        }
        //$this->sma->print_arrays($items);
        $sale_items = $this->site->getAllSaleItems($data['sale_id']);

        if ($this->db->insert('return_sales', $data)) {
            $return_id = $this->db->insert_id();
            if ($this->site->getReference('re') == $data['reference_no']) {
                $this->site->updateReference('re');
            }
            foreach ($items as $item) {
                $item['return_id'] = $return_id;
                $this->db->insert('return_items', $item);

                // if ($sale_item = $this->getSaleItemByID($item['sale_item_id'])) {
                    // if ($sale_item->quantity == $item['quantity']) {
                        // $this->db->delete('sale_items', array('id' => $item['sale_item_id']));
                    // } else {
                        // $nqty = $sale_item->quantity - $item['quantity'];
                        // $tax = $sale_item->unit_price - $sale_item->net_unit_price;
                        // $discount = $sale_item->item_discount / $sale_item->quantity;
                        // $item_tax = $tax * $nqty;
                        // $item_discount = $discount * $nqty;
                        // $subtotal = $sale_item->unit_price * $nqty;
                        // $this->db->update('sale_items', array('quantity' => $nqty, 'item_tax' => $item_tax, 'item_discount' => $item_discount, 'subtotal' => $subtotal), array('id' => $item['sale_item_id']));
                    // }

                // }
            }
            $this->calculateSaleTotals($data['sale_id'], $return_id, $data['surcharge']);
            if (!empty($payment)) {
                $payment['sale_id'] = $data['sale_id'];
                $payment['return_id'] = $return_id;
                $this->db->insert('payments', $payment);
                if ($this->site->getReference('pay') == $data['reference_no']) {
                    $this->site->updateReference('pay');
                }
                $this->site->syncSalePayments($data['sale_id']);
            }
            // $this->site->syncQuantity(NULL, NULL, $sale_items);
            $this->syncQuantityReturn($return_id);
            
			return true;
        }
        return false;
    }

    public function getCostingLines($sale_item_id, $product_id)
    {
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('costing', array('sale_item_id' => $sale_item_id, 'product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getSaleItemByID($id)
    {
        $q = $this->db->get_where('sale_items', array('id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
	public function getSaleItemBySalesID($id)
    {
        $q = $this->db->get_where('sale_items', array('sale_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getRemainingDeliveryByID($id)
    {
        $q = $this->db->get_where('sale_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function calculateSaleTotals($id, $return_id, $surcharge)
    {
        $sale = $this->getInvoiceByID($id);
        $items = $this->getAllInvoiceItems($id);
        if (!empty($items)) {
            $this->sma->update_award_points($sale->grand_total, $sale->customer_id, $sale->created_by, TRUE);
            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $total_items = 0;
            foreach ($items as $item) {
                $total_items += $item->quantity;
                $product_tax += $item->item_tax;
                $product_discount += $item->item_discount;
                $total += $item->net_unit_price * $item->quantity;
            }
            if ($sale->order_discount_id) {
                $percentage = '%';
                $order_discount_id = $sale->order_discount_id;
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = (($total + $product_tax) * (Float)($ods[0])) / 100;
                } else {
                    $order_discount = $order_discount_id;
                }
            }
            if ($sale->order_tax_id) {
                $order_tax_id = $sale->order_tax_id;
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $order_tax_details->rate;
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = (($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100;
                    }
                }
            }
            $total_discount = $order_discount + $product_discount;
            $total_tax = $product_tax + $order_tax;
            $grand_total = $total + $total_tax + $sale->shipping - $order_discount + $surcharge;
            $data = array(
                'total' => $total,
                'product_discount' => $product_discount,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $product_tax,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'grand_total' => $grand_total,
                'total_items' => $total_items,
                'return_id' => $return_id,
                'surcharge' => $surcharge
            );

            if ($this->db->update('sales', $data, array('id' => $id))) {
                $this->sma->update_award_points($data['grand_total'], $sale->customer_id, $sale->created_by);
                return true;
            }
        } else {
            $this->db->delete('sales', array('id' => $id));
            //$this->db->delete('payments', array('sale_id' => $id, 'return_id !=' => $return_id));
        }
        return FALSE;
    }

    public function getProductByName($name)
    {
        $q = $this->db->get_where('products', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	/*------Group Sales ----*/
	
    public function addGroupSales($data = array(),$inv_id = array())
    {
        if ($this->db->insert('group_sales', $data)) {
			
            $group_sales_id = $this->db->insert_id();
			
            foreach ($inv_id as $item) {
                $this->db->update('sales', array('group_id'=> $group_sales_id), array('id' => $item));
            }
            if ($this->site->getReference('do') == $data['do_reference_no']) {
                $this->site->updateReference('do');
            }
            return $group_sales_id;
        }
        return false;
    }
	
	public function get_group_detail($id)
    {
        $q = $this->db->get_where('group_sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function get_invoice_group($id)
    {
        $q = $this->db->get_where('sales', array('group_id' => $id));
        if ($q->num_rows() > 0) {
             foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	//Mendapatkan List Penjualan, grand_total dan jumlah yang sudah dibayarkan
	public function getGroupByID($id)
    {
		$this->db->select("group_id as id,customer_id, sum(grand_total) grand_total, sum(paid) paid")
				->group_by("group_id");
        $q = $this->db->get_where('sales', array('group_id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	//List Payment yang sudah dibayarkan
	public function getGroupPayments($group_id)
    {
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('payments_group', array('group_id' => $group_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
    public function addPaymentGroup($data = array())
    {
		
        if ($id=$this->db->insert('payments_group', $data)) {
            // if ($this->site->getReference('pay') == $data['reference_no']) {
                // $this->site->updateReference('pay');
            // }
            
			$this->syncGroupSalePayment($data['group_id']);
			
            return true;
        }
        return false;
    }
    public function updatePayment_Group($id, $data = array())
    {
        if ($this->db->update('payments_group', $data, array('id' => $id))) {
            $this->syncGroupSalePayment($data['group_id']);
            return true;
        }
        return false;
    }

    public function getPaymentGroupByID($id)
    {
        $q = $this->db->get_where('payments_group', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function deletePayment_Group($id)
    {
        $opay = $this->getPaymentGroupByID($id);
        if ($this->db->delete('payments_group', array('id' => $id))) {
            $this->syncGroupSalePayment($opay->group_id);
            return true;
        }
        return FALSE;
    }

	public function syncGroupSalePayment($id=null,$tipe=0)
	{
		$this->db->select("group_id, sum(amount) amount")
				->group_by("group_id");
        $q = $this->db->get_where('payments_group', array('group_id' => $id));
		
		// $GroupPaymentById = $q->row();
		$payments = $q->row();
		$paid = $payments->amount;
		print_r($paid);
		echo "<br/>";
		$this->db->select("sales.*");
		if($tipe!=0)
		{
			$this->db->order_by('id','desc');
		}else
		{
			$this->db->order_by('id','asc');
		}
		$q = $this->db->get_where('sales', array('group_id' => $payments->group_id));
		
		$this->db->update('sales', array('paid' => 0, 'payment_status' => "pending"), array('group_id' => $id));
		foreach (($q->result()) as $row) 
		{
			print_r($row->id);
			echo "-".$paid."<br/>";
				if ($paid>0){
					$selisih = ($row->grand_total)-$paid;
					if ($selisih > 0)
					{
						$this->db->update('sales', array('paid' => $paid, 'payment_status' => "partial"), array('id' => $row->id));
						$paid=0;
						break;
					} else {
						$this->db->update('sales', array('paid' => $row->grand_total, 'payment_status' => "paid"), array('id' => $row->id));
						$paid -= $row->grand_total;
					}
				}
			
		}
		// foreach ($sales as $sale) :
			// if($sale->payment_status != 'paid')
			// {
			// }
		// endforeach;
		
		// $payment_status = $paid <= 0 ? 'pending' : $sale->payment_status;
		// if ($paid <= 0 && $sale->due_date <= date('Y-m-d')) {
			// $payment_status = 'due';
		// } elseif ($this->sma->formatDecimal($sale->grand_total) > $this->sma->formatDecimal($paid) && $paid > 0) {
			// $payment_status = 'partial';
		// } elseif ($this->sma->formatDecimal($sale->grand_total) <= $this->sma->formatDecimal($paid)) {
			// $payment_status = 'paid';
		// }

		// if ($this->db->update('sales', array('paid' => $paid, 'payment_status' => $payment_status), array('id' => $id))) {
			// return true;
		// } 
	}
	
	public function get_product_group($id)
    {
            // ->join('sales', 'group_id='.$id , 'left')
		$this->db->select('b_quantity,sale_items.product_id,sale_items.product_code, sale_items.product_name, sale_items.net_unit_price as unit_price, sale_items.subtotal, sma_sale_items.quantity, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, products.unit, products.details as details, product_variants.name as variant')
            ->join('products', 'products.id=sale_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=sale_items.tax_rate_id', 'left')
            ->join('delivery_items', 'delivery_items.product_id=sale_items.product_id and delivery_items.sales_id=sale_items.sale_id ', 'left')
            ->join('sales', 'sales.id=sale_items.sale_id' , 'left')
			->group_by('sale_items.id')
			->order_by('sale_items.product_name');
        $q = $this->db->get_where('sale_items', array('group_id'=> $id));
           
        if ($q->num_rows() > 0) {
             foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function deleteGroup($id)
    {
        if ($this->db->delete('group_sales', array('id' => $id))) {
			$this->db->update('sales', array('group_id' => "0"), array('group_id' => $id));
            return true;
        }
        return FALSE;
    }
	
    public function addDelivery($data = array(),$produk = array(),$sale_id = array())
    {
        if ($this->db->insert('deliveries', $data)) {
			
            $do_id = $this->db->insert_id();
			
            foreach ($produk as $item) {
                $item['do_id'] = $do_id;
                $item['sales_id'] = $sale_id;
				// print_r($item);
                $this->db->insert('delivery_items', $item);
            }
            if ($this->site->getReference('do') == $data['do_reference_no']) {
                $this->site->updateReference('do');
            }
			$this->syncQuantityDelivery($do_id);
            return true;
        }
        return false;
    }

    public function updateDelivery($id, $data = array())
    {
        if ($this->db->update('deliveries', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function getDeliveryByID($id)
    {
        $q = $this->db->get_where('deliveries', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllDeliveryByID($id)
    {
        $q = $this->db->get_where('deliveries', array('sale_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function deleteDelivery($id)
    {
        if ($this->db->delete('deliveries', array('id' => $id))) {
			$p_delivery = $this->getAllDeliveryItems($id);
			foreach ($p_delivery as $prod) {
				$product_qty =0;
				if ($prod->quantity != 0){
					$product_qty = $prod->quantity;	
				} else {
					$product_qty = $prod->b_quantity;
				}
				$this->db->set('quantity', 'quantity+'.$product_qty, FALSE);
				$this->db->where('id',  $prod->product_id);
				$this->db->update('products');
				$this->db->set('quantity', 'quantity+'.$product_qty, FALSE);
				$this->db->where('id',  $prod->product_id);
				$this->db->update('warehouses_products');
			}
			$this->db->delete('delivery_items', array('do_id' => $id));
            return true;
        }
        return FALSE;
    }
    
	public function getInvoicePayments($sale_id)
    {
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getPaymentByID($id)
    {
        $q = $this->db->get_where('payments', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPaymentsForSale($sale_id)
    {
        $this->db->select('payments.date, payments.paid_by, payments.amount, payments.cc_no, payments.cheque_no, payments.reference_no, users.first_name, users.last_name, type')
            ->join('users', 'users.id=payments.created_by', 'left');
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function addPayment($data = array())
    {
        if ($this->db->insert('payments', $data)) {
            if ($this->site->getReference('pay') == $data['reference_no']) {
                $this->site->updateReference('pay');
            }
            $this->site->syncSalePayments($data['sale_id']);
            if ($data['paid_by'] == 'gift_card') {
                $gc = $this->site->getGiftCardByNO($data['cc_no']);
                $this->db->update('gift_cards', array('balance' => ($gc->balance - $data['amount'])), array('card_no' => $data['cc_no']));
            }
            return true;
        }
        return false;
    }


    public function updatePayment($id, $data = array())
    {
        if ($this->db->update('payments', $data, array('id' => $id))) {
            $this->site->syncSalePayments($data['sale_id']);
            return true;
        }
        return false;
    }

    public function deletePayment($id)
    {
        $opay = $this->getPaymentByID($id);
        if ($this->db->delete('payments', array('id' => $id))) {
            $this->site->syncSalePayments($opay->sale_id);
            return true;
        }
        return FALSE;
    }

    public function getWarehouseProductQuantity($warehouse_id, $product_id)
    {
        $q = $this->db->get_where('warehouses_products', array('warehouse_id' => $warehouse_id, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    /* ----------------- Gift Cards --------------------- */

    public function addGiftCard($data = array(), $ca_data = array(), $sa_data = array())
    {
        if ($this->db->insert('gift_cards', $data)) {
            if (!empty($ca_data)) {
                $this->db->update('companies', array('award_points' => $ca_data['points']), array('id' => $ca_data['customer']));
            } elseif (!empty($sa_data)) {
                $this->db->update('users', array('award_points' => $sa_data['points']), array('id' => $sa_data['user']));
            }
            return true;
        }
        return false;
    }

    public function updateGiftCard($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('gift_cards', $data)) {
            return true;
        }
        return false;
    }

    public function deleteGiftCard($id)
    {
        if ($this->db->delete('gift_cards', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getPaypalSettings()
    {
        $q = $this->db->get_where('paypal', array('id' => 1));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSkrillSettings()
    {
        $q = $this->db->get_where('skrill', array('id' => 1));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getQuoteByID($id)
    {
        $q = $this->db->get_where('quotes', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllQuoteItems($quote_id)
    {
        $q = $this->db->get_where('quote_items', array('quote_id' => $quote_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStaff()
    {
        if (!$this->Owner) {
            $this->db->where('group_id !=', 1);
        }
        $this->db->where('group_id !=', 3)->where('group_id !=', 4);
        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductVariantByName($name, $product_id)
    {
        $q = $this->db->get_where('product_variants', array('name' => $name, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTaxRateByName($name)
    {
        $q = $this->db->get_where('tax_rates', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

}
