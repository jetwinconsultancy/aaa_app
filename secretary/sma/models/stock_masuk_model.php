<?php defined('BASEPATH') OR exit('No direct script access allowed');

class stock_masuk_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getProductNames($term, $limit = 5)
    {
        $this->db->where("type = 'standard' AND (name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllProducts()
    {
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductByID($id)
    {
        $q = $this->db->get_where('products', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductsByCode($code)
    {
        $this->db->select('*')->from('products')->like('code', $code, 'both');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getProductByCode($code)
    {
        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
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

    public function updateProductQuantity($product_id, $quantity, $warehouse_id, $product_cost)
    {
        if ($this->addQuantity($product_id, $warehouse_id, $quantity)) {
            $this->site->syncProductQty($product_id, $warehouse_id);
            return true;
        }
        return false;
    }

    public function calculateAndUpdateQuantity($item_id, $product_id, $quantity, $warehouse_id, $product_cost)
    {
        if ($this->updatePrice($product_id, $product_cost) && $this->calculateAndAddQuantity($item_id, $product_id, $warehouse_id, $quantity)) {
            return true;
        }
        return false;
    }

    public function calculateAndAddQuantity($item_id, $product_id, $warehouse_id, $quantity)
    {

        if ($this->getProductQuantity($product_id, $warehouse_id)) {
            $quantity_details = $this->getProductQuantity($product_id, $warehouse_id);
            $product_quantity = $quantity_details['quantity'];
            $item_details = $this->getItemByID($item_id);
            $item_quantity = $item_details->quantity;
            $after_quantity = $product_quantity - $item_quantity;
            $new_quantity = $after_quantity + $quantity;
            if ($this->updateQuantity($product_id, $warehouse_id, $new_quantity)) {
                return TRUE;
            }
        } else {

            if ($this->insertQuantity($product_id, $warehouse_id, $quantity)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function addQuantity($product_id, $warehouse_id, $quantity)
    {

        if ($this->getProductQuantity($product_id, $warehouse_id)) {
            $warehouse_quantity = $this->getProductQuantity($product_id, $warehouse_id);
            $old_quantity = $warehouse_quantity['quantity'];
            $new_quantity = $old_quantity + $quantity;

            if ($this->updateQuantity($product_id, $warehouse_id, $new_quantity)) {
                return TRUE;
            }
        } else {

            if ($this->insertQuantity($product_id, $warehouse_id, $quantity)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function insertQuantity($product_id, $warehouse_id, $quantity)
    {
        $productData = array(
            'product_id' => $product_id,
            'warehouse_id' => $warehouse_id,
            'quantity' => $quantity
        );
        if ($this->db->insert('warehouses_products', $productData)) {
            $this->site->syncProductQty($product_id, $warehouse_id);
            return true;
        }
        return false;
    }

    public function updateQuantity($product_id, $warehouse_id, $quantity)
    {
        if ($this->db->update('warehouses_products', array('quantity' => $quantity), array('product_id' => $product_id, 'warehouse_id' => $warehouse_id))) {
            $this->site->syncProductQty($product_id, $warehouse_id);
            return true;
        }
        return false;
    }

    public function getProductQuantity($product_id, $warehouse)
    {
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse), 1);

        if ($q->num_rows() > 0) {
            return $q->row_array(); //$q->row();
        }

        return FALSE;
    }

    public function updatePrice($id, $unit_cost)
    {

        if ($this->db->update('products', array('cost' => $unit_cost), array('id' => $id))) {
            return true;
        }

        return false;
    }

    public function getAllPurchases()
    {
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllPurchaseItems($purchase_id)
    {
        $this->db->select('purchase_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, products.unit, products.details as details, product_variants.name as variant')
            ->join('products', 'products.id=purchase_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=purchase_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=purchase_items.tax_rate_id', 'left')
            ->group_by('purchase_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('purchase_items', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getQTYRecieved($purchase_id,$product_id)
    {
        $this->db->select_sum('recieved_items.quantity')
            ->group_by('recieved_items.id');
        $q = $this->db->get_where('recieved_items', array('id_po' => $purchase_id, 'product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getAllReceiveItems($receive_id)
    {
        $this->db->select('recieved_items.*')
            ->join('products', 'products.id=recieved_items.product_id', 'left')
            ->group_by('recieved_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('recieved_items', array('received_id' => $receive_id));
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
        $q = $this->db->get_where('purchase_items', array('id' => $id), 1);
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

    public function getPurchaseByID($id)
    {
        $q = $this->db->get_where('purchases', array('id' => $id), 1);
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

    public function getProductWarehouseOptionQty($option_id, $warehouse_id)
    {
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id, 'warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addProductOptionQuantity($option_id, $warehouse_id, $quantity, $product_id)
    {
        if ($option = $this->getProductWarehouseOptionQty($option_id, $warehouse_id)) {
            $nq = $option->quantity + $quantity;
            if ($this->db->update('warehouses_products_variants', array('quantity' => $nq), array('option_id' => $option_id, 'warehouse_id' => $warehouse_id))) {
                return TRUE;
            }
        } else {
            if ($this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $quantity))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function resetProductOptionQuantity($option_id, $warehouse_id, $quantity, $product_id)
    {
        if ($option = $this->getProductWarehouseOptionQty($option_id, $warehouse_id)) {
            $nq = $option->quantity - $quantity;
            if ($this->db->update('warehouses_products_variants', array('quantity' => $nq), array('option_id' => $option_id, 'warehouse_id' => $warehouse_id))) {
                return TRUE;
            }
        } else {
            $nq = 0 - $quantity;
            if ($this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $nq))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getOverSoldCosting($product_id)
    {
        $q = $this->db->get_where('costing', array('overselling' => 1));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

	
	public function syncQuantityPurchased($do_id)
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
	
    public function addPurchase($data, $items)
    {

        if ($this->db->insert('purchases', $data)) {
            $purchase_id = $this->db->insert_id();
            if ($this->site->getReference('po') == $data['reference_no']) {
                $this->site->updateReference('po');
            }
            foreach ($items as $item) {
                $item['purchase_id'] = $purchase_id;
                $this->db->insert('purchase_items', $item);
                $this->db->update('products', array('cost' => $item['real_unit_cost']), array('id' => $item['product_id']));
                if($item['option_id']) {
                    $this->db->update('product_variants', array('cost' => $item['real_unit_cost']), array('id' => $item['option_id'], 'product_id' => $item['product_id']));
                }
            }
            if ($data['status'] == 'received') {
                $this->syncPurchaseQuantity($purchase_id,'');
            }
            return true;
        }
        return false;
    }

    public function updatePurchase($id, $data, $items = array())
    {
        $opurchase = $this->getPurchaseByID($id);
        $oitems = $this->getAllPurchaseItems($id);
		$this->syncPurchaseQuantity($id,1);
        if ($this->db->update('purchases', $data, array('id' => $id)) && $this->db->delete('purchase_items', array('purchase_id' => $id))) {
            $purchase_id = $id;
            foreach ($items as $item) {
                $item['purchase_id'] = $id;
                $this->db->insert('purchase_items', $item);
            }
            if ($opurchase->status == 'received') {
                // $this->site->syncQuantity(NULL, NULL, $oitems);
            }
            if ($data['status'] == 'received') {
                $this->syncPurchaseQuantity($id);
            }
            $this->site->syncPurchasePayments($id);
            return true;
        }

        return false;
    }
	
	public function syncPurchaseQuantity($purchase_id,$reset)
    {
        if ($purchase_items = $this->getAllPurchaseItems($purchase_id)) {
            foreach ($purchase_items as $item) {
                $balance_qty = 0;
				$q = $this->db->get_where('products',array('id' =>$item->product_id),1);
				if ($q->num_rows() > 0) {
					$data = $q->row();
					$balance_qty=$data->quantity;
				}
						if ($reset == 0)
						{
							$product_qty = $balance_qty+$item->quantity;
						}else{
							$product_qty = $balance_qty-$item->quantity;
						}
				if ($this->db->update('products', array('quantity' => ($product_qty)), array('id' => $item->product_id))) {
					// Search product exists
					$q = $this->db->get_where('warehouses_products',array('product_id' =>$item->product_id, 'warehouse_id' => $item->warehouse_id ));
					if ($q->num_rows() > 0) {
						// Get Balance Before
							$data = $q->row();
							$wh_balance_qty = $data->quantity;
						if ($reset == 0)
						{
							$this->db->update('warehouses_products', array('quantity' => ($wh_balance_qty+$item->quantity)), array('product_id' => $item->product_id, 'warehouse_id' => $item->warehouse_id));
						}else
						{
							$this->db->update('warehouses_products', array('quantity' => ($wh_balance_qty-$item->quantity)), array('product_id' => $item->product_id, 'warehouse_id' => $item->warehouse_id));
						}
						
					} else {
						if(!$wh_balance_qty) { $wh_balance_qty = 0; }
						// New Item delivery = insert to warehouse_product using delivery_quantity;
						if ($reset == 0)
						{
							$this->db->insert('warehouses_products', array('quantity' => ($item->quantity), 'product_id' => $item->product_id, 'warehouse_id' => $item->warehouse_id));
						}else
						{
							$this->db->insert('warehouses_products', array('quantity' => (0-$item->quantity), 'product_id' => $item->product_id, 'warehouse_id' => $item->warehouse_id));
						}
					}
				} 
            }
        }
    }
	
    public function getQtyItemPurchase($purchase_id, $product_id)
    {
        $this->db->select('purchase_items.*')
            ->join('purchases', 'purchases.id=purchase_items.product_id', 'left')
            ->group_by('purchase_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('purchase_items', array('purchase_id' => $purchase_id, 'product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getQtyItemRecieved($purchase_id, $product_id)
    {
        $this->db->select('recieved_items.*')
            ->join('purchases', 'purchases.id=purchase_items.product_id', 'left')
            ->group_by('purchase_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('purchase_items', array('purchase_id' => $purchase_id, 'product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function insert_receive($id,$data, $items)
    {
				// $jlh_barang = $this->getQtyItemPurchase(1,1);
				// return ($jlh_barang);
				// $this->db->set($data);
        if ($this->db->insert('recieved', $data)) {
            $received_id = $this->db->insert_id();
            // if ($this->site->getReference('po') == $data['reference_no']) {
                // $this->site->updateReference('po');
            // }
			$qty_tidak_full_diterima =0;
            foreach ($items as $item) {
                $item['received_id'] = $received_id;
                $this->db->insert('recieved_items', $item);
				$jlh_barang = $this->getQtyItemPurchase($id,$item['product_id']);
				// print_r($jlh_barang);
				// echo $item['quantity']."-".$jlh_barang[0]->quantity."<br/>";
				if ($item['quantity']!=$jlh_barang[0]->quantity)
				{
					$qty_tidak_full_diterima++;
				}
                // if($item['option_id']) {
                    // $this->db->update('product_variants', array('cost' => $item['real_unit_cost']), array('id' => $item['option_id'], 'product_id' => $item['product_id']));
                // }
            }
				// print_r ($qty_tidak_full_diterima);
				// print_r($item);
            if ($data['status'] == 'received') {
                // $this->site->syncQuantity(NULL, $purchase_id);
            }
			if ($qty_tidak_full_diterima > 0)
			{
				$this->db->update('purchases', array('status' => "partial"), array('id' => $id));
			}
			if ($this->site->checkReceived($id)){
				// echo "suksesberat";
				$this->site->syncQuantity(NULL, $id);
				$this->db->update('purchases', array('status' => "received"), array('id' => $id));
			
			} else {
				// echo "tidak full";
			}
            return true;
        }
        return false;
    }

    public function deletePurchase($id)
    {
        $purchase_items = $this->site->getAllPurchaseItems($id);

        if ($this->db->delete('purchase_items', array('purchase_id' => $id)) && $this->db->delete('purchases', array('id' => $id))) {
            $this->db->delete('payments', array('purchase_id' => $id));
            $this->site->syncQuantity(NULL, NULL, $purchase_items);
            return true;
        }
        return FALSE;
    }
   
	public function deleteReceived($id)
    {
        // $purchase_items = $this->site->getAllReceiveItems($id);

        if ($this->db->delete('recieved_items', array('received_id' => $id)) && $this->db->delete('recieved', array('id' => $id))) {
            // $this->db->delete('payments', array('purchase_id' => $id));
            // $this->site->syncQuantity(NULL, NULL, $purchase_items);
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

    public function getPurchasePayments($purchase_id)
    {
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('payments', array('purchase_id' => $purchase_id));
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

    public function getPaymentsForPurchase($purchase_id)
    {
        $this->db->select('payments.date, payments.paid_by, payments.amount, payments.reference_no, users.first_name, users.last_name, type')
            ->join('users', 'users.id=payments.created_by', 'left');
        $q = $this->db->get_where('payments', array('purchase_id' => $purchase_id));
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
            $this->site->syncPurchasePayments($data['purchase_id']);
            return true;
        }
        return false;
    }

    public function updatePayment($id, $data = array())
    {
        if ($this->db->update('payments', $data, array('id' => $id))) {
            $this->site->syncPurchasePayments($data['purchase_id']);
            return true;
        }
        return false;
    }

    public function deletePayment($id)
    {
        $opay = $this->getPaymentByID($id);
        if ($this->db->delete('payments', array('id' => $id))) {
            $this->site->syncPurchasePayments($opay->purchase_id);
            return true;
        }
        return FALSE;
    }

    public function getProductOptions($product_id)
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

    public function getProductVariantByName($name, $product_id)
    {
        $q = $this->db->get_where('product_variants', array('name' => $name, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getExpenseByID($id)
    {
        $q = $this->db->get_where('expenses', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addExpense($data = array())
    {
        if ($this->db->insert('expenses', $data)) {
            if ($this->site->getReference('ex') == $data['reference']) {
                $this->site->updateReference('ex');
            }
            return true;
        }
        return false;
    }

    public function updateExpense($id, $data = array())
    {
        if ($this->db->update('expenses', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function deleteExpense($id)
    {
        if ($this->db->delete('expenses', array('id' => $id))) {
            return true;
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

}
