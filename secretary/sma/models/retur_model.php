<?php defined('BASEPATH') OR exit('No direct script access allowed');

class retur_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
	public function getListSales($term,$term1,$term2, $term3, $limit = 50)
    {
        // $q = $this->db->query("SELECT A.`id`, date(B.`date`) tgl, B.reference_no, B.reference_no1,B.customer, `product_name`, `quantity`, `net_unit_price`,retur_qty FROM `sma_sale_items` A, sma_sales B where A.sale_id = B.id and B.sale_status ='completed' and (B.date like '%".$term."%' or B.reference_no like '%".$term."%' or B.reference_no1 like '%".$term."%' or product_name like '%".$term1."%' or B.customer like '%".$term2."%') limit 0,".$limit);
		$kondisi1 = "";
		$kondisi2 = "";
		$kondisi3 = "";
		$kondisi4 = "";
		if ($term != "" and $term != "_")
		{
			$kondisi1 = " and (B.reference_no like '%".$term."%' or B.reference_no1 like '%".$term."%')";
		}
		if ($term1 != "" and $term1 != "_")
		{
			$kondisi2 = " and product_name like '%".$term1."%' ";
		}
		if ($term2 != "" and $term2 != "_")
		{	
			$kondisi3 = " and B.customer like '%".$term2."%'";
		}
		if ($term3 != "" and $term3 != "_")
		{	
			$kondisi4 = " and B.customer_id = ".$term3."";
		}
		
			// $q = $this->db->query("SELECT A.`id`, date(B.`date`) tgl, B.reference_no, B.reference_no1,B.customer, `product_name`, `quantity`, `net_unit_price`,retur_qty FROM `sma_sale_items` A, sma_sales B where A.sale_id = B.id and B.sale_status ='completed' and (B.reference_no like '%".$term."%' or B.reference_no1 like '%".$term."%') and B.customer like '%".$term2."%' limit 0,".$limit);
			// echo "SELECT A.`id`, date(B.`date`) tgl, B.reference_no, B.reference_no1,B.customer, `product_name`, `quantity`, `net_unit_price`,retur_qty FROM `sma_sale_items` A, sma_sales B where A.sale_id = B.id and B.sale_status ='completed' ".$kondisi1." ".$kondisi2." ".$kondisi3." limit 0,".$limit;
			$q = $this->db->query("SELECT A.`id`, date(B.`date`) tgl, B.reference_no, B.reference_no1,B.customer, `product_name`, `quantity`, `net_unit_price`,retur_qty FROM `sma_sale_items` A, sma_sales B where A.sale_id = B.id and B.sale_status ='completed' ".$kondisi1." ".$kondisi2." ".$kondisi3." ".$kondisi4." order by tgl desc limit 0,".$limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

	public function getSales($term,$term1,$term2, $term3, $limit = 50)
    {
        // $q = $this->db->query("SELECT A.`id`, date(B.`date`) tgl, B.reference_no, B.reference_no1,B.customer, `product_name`, `quantity`, `net_unit_price`,retur_qty FROM `sma_sale_items` A, sma_sales B where A.sale_id = B.id and B.sale_status ='completed' and (B.date like '%".$term."%' or B.reference_no like '%".$term."%' or B.reference_no1 like '%".$term."%' or product_name like '%".$term1."%' or B.customer like '%".$term2."%') limit 0,".$limit);
		$kondisi1 = "";
		$kondisi2 = "";
		$kondisi3 = "";
		$kondisi4 = "";
		if ($term != "" and $term != "_")
		{
			$kondisi1 = " and (B.reference_no like '%".$term."%' or B.reference_no1 like '%".$term."%')";
		}
		if ($term2 != "" and $term2 != "_")
		{	
			$kondisi3 = " and B.customer like '%".$term2."%'";
		}
		if ($term3 != "" and $term3 != "_")
		{	
			$kondisi4 = " and B.customer_id = ".$term3."";
		}
		
			$q = $this->db->query("SELECT B.`id`, date(B.`date`) tgl, B.reference_no, B.reference_no1,B.customer, `product_name`, `quantity`, `net_unit_price`,retur_qty FROM `sma_sale_items` A, sma_sales B where A.sale_id = B.id and B.sale_status ='completed' ".$kondisi1." ".$kondisi3." ".$kondisi4." order by tgl desc limit 0,".$limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

	public function getListSLS($id)
    {
        
			$q = $this->db->query("Select * from sma_sales A, sma_sale_items B where A.id = B.sale_id and B.id=".$id);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	public function getListSLSAll($id)
    {
        
			$q = $this->db->query("Select * from sma_sales A, sma_sale_items B where A.id = B.sale_id and A.id=".$id);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
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
			$complete = 1;
			$sale_id = 0;
            foreach ($delivery_item as $item) {
                $balance_qty = 0;
				if (($item->b_quantity != $item->quantity) || ($item->b_quantity ==0)){
					$complete =0;
				}
				$sale_id = $item->sales_id;
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
			if ($complete){
				$this->db->update('sales', array('sale_status' => 'completed', 'receive_stat' => 1), array('id' => $sale_id));
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
    
	public function getItemByID($id)
    {

        $q = $this->db->get_where('sale_items', array('id' => $id), 1);
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
	
	public function returnSale1($data = array(), $items = array())
    {

        if ($this->db->insert('return_sales', $data)) {
            $return_id = $this->db->insert_id();
            if ($this->site->getReference('re') == $data['reference_no']) {
                $this->site->updateReference('re');
            }
            foreach ($items as $item) {
                $item['return_id'] = $return_id;
                $this->db->insert('return_items', $item);
				
				$this->db->query("UPDATE `sma_sale_items` SET retur_qty = retur_qty+".$item['quantity']." WHERE `id` = ".$item['sale_item_id']."");
				$this->db->query("UPDATE `sma_products` SET quantity = quantity+".$item['quantity']." WHERE `id` = ".$item['product_id']."");
				// $this->db->set('retur_qty', "retur_qty+".$item['quantity']);
				// $this->db->where('id', $item['product_id']);
				// $this->db->update('sale_itemsx');
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
            
			return $return_id;
        }
        return false;
    }
	
	
    public function deleteRetur($id)
    {
        $return_id = $this->resetReturnActions($id);
        if ($this->db->delete('return_items', array('return_id' => $id)) &&
        $this->db->delete('return_sales', array('id' => $id))) {
            // if ($return = $this->getReturnBySID($id)) {
                // $this->deleteReturn($return->id);
            // }
            // $this->resetDeliveryAction($id);
            return true;
        }
        return FALSE;
    }

    public function resetReturnActions($id)
    {
        // $return = $this->getInvoiceByID($id);
        $items = $this->getAllReturnItems($id);
        foreach ($items as $item) {

                    $quantity = number_format($item->quantity);
					
				$this->db->query("UPDATE `sma_sale_items` SET retur_qty = retur_qty-".$quantity." WHERE `id` = ".$item->sale_item_id."");
				$this->db->query("UPDATE `sma_products` SET quantity = quantity-".$quantity." WHERE `id` = ".$item->product_id."");

        }
        // $this->sma->update_award_points($sale->grand_total, $sale->customer_id, $sale->created_by, TRUE);
        return $items;
    }
	
    public function getAllReturnItems($return_id)
    {
        $q = $this->db->get_where('return_items', array('return_id' => $return_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
    public function deleteReturn($id)
    {
        if ($this->db->delete('return_items', array('return_id' => $id)) && $this->db->delete('return_sales', array('id' => $id))) {
            return true;
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
	

	/*-------- Pembelian ------------*/
	public function getListBuy($term,$term1,$term2, $term3, $limit = 50)
    {
        // $q = $this->db->query("SELECT A.`id`, date(B.`date`) tgl, B.reference_no, B.reference_no1,B.customer, `product_name`, `quantity`, `net_unit_price`,retur_qty FROM `sma_sale_items` A, sma_sales B where A.sale_id = B.id and B.sale_status ='completed' and (B.date like '%".$term."%' or B.reference_no like '%".$term."%' or B.reference_no1 like '%".$term."%' or product_name like '%".$term1."%' or B.customer like '%".$term2."%') limit 0,".$limit);
		$kondisi1 = "";
		$kondisi2 = "";
		$kondisi3 = "";
		$kondisi4 = "";
		if ($term != "" and $term != "_")
		{
			$kondisi1 = " and (B.reference_no like '%".$term."%' or B.reference_no1 like '%".$term."%')";
		}
		if ($term2 != "" and $term2 != "_")
		{	
			$kondisi3 = " and B.supplier like '%".$term2."%'";
		}
		if ($term3 != "" and $term3 != "_")
		{	
			$kondisi4 = " and B.supplier_id = ".$term3."";
		}
		
			$q = $this->db->query("SELECT B.`id`, date(B.`date`) tgl, B.reference_no, B.reference_no1,B.supplier,B.total FROM sma_purchases B where B.status ='received' ".$kondisi1." ".$kondisi2." ".$kondisi3." ".$kondisi4." order by tgl desc limit 0,".$limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

	public function getListPurchase($id)
    {
        
			$q = $this->db->query("Select * from sma_purchases A, sma_purchase_items B where A.id = B.purchase_id and B.id=".$id);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function returnSale2($data = array(), $items = array())
    {

        if ($this->db->insert('returnb_sales', $data)) {
            $return_id = $this->db->insert_id();
            if ($this->site->getReference('reb') == $data['reference_no']) {
                $this->site->updateReference('reb');
            }
            foreach ($items as $item) {
                $item['return_id'] = $return_id;
                $this->db->insert('returnb_items', $item);
				
				$this->db->query("UPDATE `sma_purchase_items` SET retur_qty = retur_qty+".$item['quantity']." WHERE `id` = ".$item['purchase_item_id']."");
				$this->db->query("UPDATE `sma_products` SET quantity = quantity-".$item['quantity']." WHERE `id` = ".$item['product_id']."");
				
            }
            
			return $return_id;
        }
        return false;
    }
	public function getReturnByIDPurchase($id)
    {
        $q = $this->db->get_where('returnb_sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getReturnBySIDPurchase($purchase_id)
    {
        $q = $this->db->get_where('returnb_sales', array('purchase_id' => $purchase_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getAllReturnPurchaseItems($return_id)
    {
        $q = $this->db->get_where('returnb_items', array('return_id' => $return_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
}
