<div class="jumbotron">
  <h1>API Production</h1>
</div>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Get Schedule</h3>
  </div>
  <div class="panel-body">
    API Get Schedule digunakan untuk mendapatkan schedule produksi, di filter berdasarkan nama material, startdate, dan end date
  </div>
	<ul class="list-group">
    <li class="list-group-item"><strong>Method POST : api/production/getschedule</strong></li>
    <li class="list-group-item"><strong>Request</strong><br/>
			<div class="row">
				<div class="col-md-4">
					Tag
				</div>
				<div class="col-md-8">
					schedule
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					Username
				</div>
				<div class="col-md-8">
					[Nama Username]
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					productname
				</div>
				<div class="col-md-8">
					[Nama Material / Service]
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					startdate
				</div>
				<div class="col-md-8">
					[Tgl Mulai Produksi]
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					enddate
				</div>
				<div class="col-md-8">
					[Tgl Akhir Produksi]
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
					rows
				</div>
				<div class="col-md-8">
					[kosong] (jika terdapat kesalahan atau tidak ada data)<br/>
					array record (jika tidak ada kesalahan dan ada data)<br/>
					usertodoid,username,tododate,menuname,docno,description
				</div>
			</div>
		</li>
  </ul>
</div>