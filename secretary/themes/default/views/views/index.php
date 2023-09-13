
<html>

<head>
	
	<style>
	.table td, .table th { border: 1px solid black; padding: 5px; }
	</style>

</head>

<body>

	<div>

		<div style="height: 15px; width: 100%; margin: 20px 0; text-align: center; color: black; font: bold 15px Helvetica, Sans-Serif; text-decoration: uppercase; letter-spacing: 10px; padding: 8px 0px;font-size: 14px; font-family: Georgia, Serif; overflow: hidden; resize: none; text-decoration: none; font-weight: bold;">INVOICE
		</div>
		<div>
			<table style="padding-bottom: 20px;padding-right: 10px;font-size: 14px; font-family: Georgia, Serif;">
	            <tr>

                    <td style="" width="90">Client Name:</td>
                    <td width="190"><?php echo $q[0]["company_name"] ?></td>
                    <td style="" width="90">Invoice No:</td>
                    <td>000123</td>
                </tr>
	            <tr>
	            	<td style="" width="90">Address:</td>
			        <td width="190">December 15, 2009</td>
			        <td style="" width="90">Date:</td>
			        <td>December 15, 2009</td>
	            </tr>
	            <tr>
	            	<td style="" width="90">Attention:</td>
				    <td width="190">$875.00</td>
				    <td style="" width="90">Currency:</td>
				    <td>$875.00</td>
	            </tr>


		    </table>


            
            
		
		</div>
		
		<table cellspacing="0" cellpadding="2" class="table" style="font-size: 14px; font-family: Georgia, Serif;">
		
		  
		  <tr style="background-color: #eee;">
		      <td style="width: 430px;">Description</td>
	           <td style="text-align: center;width: 80px;">Amount</td>
	      </tr>
		  <tr class="item-row">
		      <td>Web Updates</td>
		      <td style="text-align: center;">$650.00</td>
		  </tr>  
		  <tr>
		      <td style="background-color: #eee;">Sub-Total</td>
		      <td  style="text-align: center;">$875.00</td>
		  </tr>
		  <tr>
		      <td style="background-color: #eee;">GST</td>
		      <td  style="text-align: center;">$875.00</td>
		  </tr>
		  <tr>
		      <td style="background-color: #eee;">Total</td>
		      <td  style="text-align: center;">$875.00</td>
		  </tr>
		  
		
		</table>
		
	</div>

	
	
	
</body>

</html>