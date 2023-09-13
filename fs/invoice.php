<html>
	<head>
		<title>Invoice Print</title>
		<!-- Web Fonts  -->
		<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />

		<!-- Invoice Print Style -->
		<link rel="stylesheet" href="assets/stylesheets/invoice-print.css" />
	</head>
	<body>
		<div class="invoice">
			<header class="clearfix">
				<div class="row">
					<div class="col-sm-6 mt-md">
						<h2 class="h2 mt-none mb-sm text-dark text-weight-bold">INVOICE</h2>
						<h4 class="h4 m-none text-dark text-weight-bold">#INVOICE NO</h4>
					</div>
					<div class="col-sm-6 text-right mt-md mb-md">
						<div class="ib">
							<img src="img/logo.png" style="width:80px;"alt="Logo Here" />
						</div>
						<address class="ib mr-xlg">
							Company Name
							<br/>
							Address
							<br/>
							Phone: +65 1111 2222
							<br/>
							user@user.com
						</address>
					</div>
				</div>
			</header>
			<div class="bill-info">
				<div class="row">
					<div class="col-md-6">
						<div class="bill-to">
							<p class="h5 mb-xs text-dark text-weight-semibold">To:</p>
							<address>
								MR. XXXXX
								<br/>
								Company Name
								<br/>
								Address
								<br/>
								Phone: +65 2223 33333
								<br/>
								client@client.com
							</address>
						</div>
					</div>
					<div class="col-md-6">
						<div class="bill-data text-right">
							<p class="mb-none">
								<span class="text-dark">Invoice Date:</span>
								<span class="value">10/22/2016</span>
							</p>
							<p class="mb-none">
								<span class="text-dark">Due Date:</span>
								<span class="value">11/22/2016</span>
							</p>
						</div>
					</div>
				</div>
			</div>
		
			<div class="table-responsive">
				<table class="table invoice-items">
					<thead>
						<tr class="h4 text-dark">
							<th id="cell-id"     class="text-weight-semibold">#</th>
							<th id="cell-item"   class="text-weight-semibold">Service</th>
							<th id="cell-qty"    class="text-center text-weight-semibold">Amount</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td class="text-weight-semibold text-dark">Service 1</td>
							<td class="text-center">SGD 10,000.00</td>
						</tr>
						<tr>
							<td>2</td>
							<td class="text-weight-semibold text-dark">Service 2</td>
							<td class="text-center">SGD 15,000.00</td>
						</tr>
					</tbody>
				</table>
			</div>
		
			<div class="invoice-summary">
				<div class="row">
					<div class="col-sm-4 col-sm-offset-8">
						<table class="table h5 text-dark">
							<tbody>
								<tr class="b-top-none">
									<td colspan="2">Grand Total</td>
									<td class="text-left">SGD 25,000.00</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<script>
			window.print();
		</script>
	</body>
</html>