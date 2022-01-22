<div class="jumbotron">
  <h1>API System</h1>
</div>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Login</h3>
  </div>
  <div class="panel-body">
    API Login digunakan saat pertama kali otentikasi
  </div>
	<ul class="list-group">
    <li class="list-group-item"><strong>Method POST : api/system/login</strong></li>
    <li class="list-group-item"><strong>Request</strong><br/>
			<div class="row">
				<div class="col-md-4">
					Tag
				</div>
				<div class="col-md-8">
					login
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
					Password
				</div>
				<div class="col-md-8">
					[your password]
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
					realname
				</div>
				<div class="col-md-8">
					"" (jika ada kesalahan)<br/>
					[nama lengkap user] (jika tidak ada kesalahan)
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					key
				</div>
				<div class="col-md-8">
					"" (jika ada kesalahan)<br/>
					[key] (jika tidak ada kesalahan)
				</div>
			</div>
		</li>
  </ul>
</div>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Logout</h3>
  </div>
  <div class="panel-body">
    API Logout digunakan untuk keluar dari sistem
  </div>
	<ul class="list-group">
    <li class="list-group-item"><strong>Method POST : api/system/logout</strong></li>
    <li class="list-group-item"><strong>Request</strong><br/>
			<div class="row">
				<div class="col-md-4">
					Tag
				</div>
				<div class="col-md-8">
					logout
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					Username
				</div>
				<div class="col-md-8">
					[user]
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
		</li>
  </ul>
</div>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Update User Profile</h3>
  </div>
  <div class="panel-body">
    API Update Profile digunakan untuk mengubah data profile user
  </div>
	<ul class="list-group">
    <li class="list-group-item"><strong>Method POST : api/system/updateprofile</strong></li>
    <li class="list-group-item"><strong>Request</strong><br/>
			<div class="row">
				<div class="col-md-4">
					Tag
				</div>
				<div class="col-md-8">
					updateprofile
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					Username
				</div>
				<div class="col-md-8">
					[username]
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					Password
				</div>
				<div class="col-md-8">
					[password]
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					Real Name
				</div>
				<div class="col-md-8">
					[Nama Lengkap Pengguna]
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					Email
				</div>
				<div class="col-md-8">
					[alamat email]
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
		</li>
  </ul>
</div>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">User To Do List</h3>
  </div>
  <div class="panel-body">
    API User To Do digunakan untuk menampilkan tugas dan pekerjaan
  </div>
	<ul class="list-group">
    <li class="list-group-item"><strong>Method POST : api/system/getusertodo</strong></li>
    <li class="list-group-item"><strong>Request</strong><br/>
			<div class="row">
				<div class="col-md-4">
					Tag
				</div>
				<div class="col-md-8">
					usertodo
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					Username
				</div>
				<div class="col-md-8">
					[username]
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
					[Jumlah Baris Data]
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
					total
				</div>
				<div class="col-md-8">
					0 (jika terdapat kesalahan)<br/>
					[jumlah record] (jika tidak ada kesalahan)
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
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">User Inbox List</h3>
  </div>
  <div class="panel-body">
    API User Inbox digunakan untuk menampilkan pesan dari user lain
  </div>
	<ul class="list-group">
    <li class="list-group-item"><strong>Method POST : api/system/getuserinbox</strong></li>
    <li class="list-group-item"><strong>Request</strong><br/>
			<div class="row">
				<div class="col-md-4">
					Tag
				</div>
				<div class="col-md-8">
					userinbox
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					Username
				</div>
				<div class="col-md-8">
					[username]
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
					[Jumlah Baris Data]
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
					total
				</div>
				<div class="col-md-8">
					0 (jika terdapat kesalahan)<br/>
					[jumlah record] (jika tidak ada kesalahan)
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					rows
				</div>
				<div class="col-md-8">
					[kosong] (jika terdapat kesalahan atau tidak ada data)<br/>
					array record (jika tidak ada kesalahan dan ada data)<br/>
					userinboxid,username,senddate,fromname,subject,description
				</div>
			</div>
		</li>
  </ul>
</div>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">User Menu List</h3>
  </div>
  <div class="panel-body">
    API User Menu digunakan untuk menampilkan daftar menu yang sesuai otorisasi user
  </div>
	<ul class="list-group">
    <li class="list-group-item"><strong>Method POST : api/system/getusermenu</strong></li>
    <li class="list-group-item"><strong>Request</strong><br/>
			<div class="row">
				<div class="col-md-4">
					Tag
				</div>
				<div class="col-md-8">
					usermenu
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					Username
				</div>
				<div class="col-md-8">
					[username] yang digunakan saat login
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					Key
				</div>
				<div class="col-md-8">
					[key] yang didapatkan dari login
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
					menuname,description
				</div>
			</div>
		</li>
  </ul>
</div>