<div class="jumbotron">
  <h1>API Inventory</h1>
</div>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Get Stock</h3>
  </div>
  <div class="panel-body">
    API Get Stock digunakan untuk mendapatkan stock
  </div>
	<ul class="list-group">
    <li class="list-group-item"><strong>Method POST : api/inventory/getstock</strong></li>
    <li class="list-group-item"><strong>Request</strong><br/>
			<div class="row">
				<div class="col-md-4">
					Tag
				</div>
				<div class="col-md-8">
					stock
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					Username
				</div>
				<div class="col-md-8">
					[your username]
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					Productname
				</div>
				<div class="col-md-8">
					[Nama Material / Service]
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					Sloccode
				</div>
				<div class="col-md-8">
					[Kode Gudang]
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					Uomcode
				</div>
				<div class="col-md-8">
					[Kode Satuan]
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					Page
				</div>
				<div class="col-md-8">
					[Halaman]
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					Rows
				</div>
				<div class="col-md-8">
					[Jumlah Baris dalam 1 halaman]
				</div>
			</div>
		</li>
		<li class="list-group-item"><strong>Response</strong><br/>
			<div class="row">
				<div class="col-md-4">
					error
				</div>
				<div class="col-md-8">
					true (jika terdapat kesalahan)<br/>
					false (jika tidak ada kesalahan)
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					error_msg
				</div>
				<div class="col-md-8">
					"OK" (jika error = false)<br/>
					[pesan error, jika terdapat error]
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					username
				</div>
				<div class="col-md-8">
					"" (jika ada kesalahan)<br/>
					[nama user] (jika tidak ada kesalahan)
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					rows
				</div>
				<div class="col-md-8">
					[kosong] (jika terdapat kesalahan atau tidak ada data)<br/>
					array record (jika tidak ada kesalahan dan ada data)<br/>
					productstockid,productname,sloccode,description,qty,company,uomcode,qtyinprogress
				</div>
			</div>
		</li>
  </ul>
</div>