<?php 
session_start();
include '../../main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Transaksi Penjualan - Kasir Fwbi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body { background:#f1f4f9; }

.produk-item {
    cursor:pointer;
    transition:0.3s;
    border-radius:15px;
}
.produk-item:hover {
    transform: translateY(-5px) scale(1.02);
    border-color:#1d4ed8;
    box-shadow:0 5px 15px rgba(0,0,0,.15);
}

.keranjang-box {
    border-radius:20px;
    position: sticky;
    top:20px;
}

.badge-stok {
    font-size:12px;
}
</style>
</head>

<body>
<div class="d-flex">
<?php include '../../template/sidebar.php'; ?>

<div class="container-fluid p-4">
<div class="row">

<!-- PRODUK -->
<div class="col-md-7">
<div class="card shadow border-0 mb-4">
<div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
<h5 class="fw-bold m-0"><i class="fas fa-boxes me-2 text-primary"></i>Pilih Produk</h5>
<input type="text" id="searchProduk" class="form-control w-50" placeholder="Cari produk...">
</div>

<div class="card-body overflow-auto" style="max-height:75vh;">
<div class="row" id="produkList">
<?php 
$sql = mysqli_query($conn,"SELECT * FROM produk WHERE Stok > 0 ORDER BY NamaProduk ASC");
while($p = mysqli_fetch_assoc($sql)){
?>
<div class="col-md-4 mb-3 produk-card">
<div class="card produk-item h-100 shadow-sm"
onclick="tambahItem('<?= $p['ProdukID'] ?>','<?= $p['NamaProduk'] ?>','<?= $p['Harga'] ?>','<?= $p['Stok'] ?>')">
<div class="card-body text-center">
<h6 class="fw-bold"><?= $p['NamaProduk'] ?></h6>
<p class="text-primary fw-bold mb-1">Rp <?= number_format($p['Harga']) ?></p>
<span class="badge bg-light text-dark border badge-stok">Stok: <?= $p['Stok'] ?></span>
</div>
</div>
</div>
<?php } ?>
</div>
</div>
</div>
</div>

<!-- KERANJANG -->
<div class="col-md-5">
<div class="card keranjang-box shadow border-0">
<div class="card-header bg-primary text-white py-3">
<h5 class="fw-bold m-0"><i class="fas fa-shopping-cart me-2"></i>Keranjang</h5>
</div>

<div class="card-body">
<form action="proses_simpan.php" method="POST" id="formTransaksi">

<div class="card-body px-4 pt-0">
                            <form action="proses_simpan.php" method="POST" id="formTransaksi">
                                <div class="row g-2 mb-4">
                                    <div class="col-12">
                                        <input type="text" name="NamaPelanggan" class="form-control form-control-custom" placeholder="Nama Pelanggan" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="NomorTelepon" class="form-control form-control-custom" placeholder="No. Telp" required>
                                    </div>
                                    <div class="col-md-6">
                                        <textarea name="Alamat" class="form-control form-control-custom" rows="1" placeholder="Alamat" required></textarea>
                                    </div>
                                </div>

<!-- TABEL PESANAN -->
<div class="table-responsive">
<table class="table table-sm align-middle" id="tabelPesanan">
<thead>
<tr class="small text-muted">
<th>Produk</th>
<th width="60">Qty</th>
<th>Total</th>
<th></th>
</tr>
</thead>
<tbody></tbody>
</table>
</div>

<hr>
<div class="d-flex justify-content-between">
<span class="fw-bold">Grand Total</span>
<h4 class="fw-bold text-primary" id="totalHarga">Rp 0</h4>
</div>

<div class="bg-light p-3 rounded mt-3">
<label class="fw-bold small">Uang Bayar</label>
<input type="number" id="uangBayar" class="form-control form-control-lg fw-bold text-success" oninput="hitungKembalian()">
<div class="d-flex justify-content-between mt-2">
<span class="fw-bold small">Kembalian</span>
<span class="fw-bold text-danger" id="textKembalian">Rp 0</span>
</div>
</div>

<div class="d-grid gap-2 mt-3">
<button type="button" onclick="confirmBayar()" class="btn btn-success py-3 fw-bold" id="btnBayar" disabled>
<i class="fas fa-check-circle me-2"></i>Bayar Sekarang
</button>
<button type="button" onclick="clearCart()" class="btn btn-outline-danger fw-bold">
<i class="fas fa-trash me-2"></i>Reset Keranjang
</button>
</div>

</form>
</div>
</div>
</div>

</div>
</div>

<script>
let items = [];

function tambahItem(id,nama,harga,stokMax){
let index = items.findIndex(i=>i.id===id);
if(index!==-1){
if(items[index].qty < stokMax){
items[index].qty++;
}else{
Swal.fire('Stok Habis','Tidak bisa menambah lagi','warning');
}
}else{
items.push({id,nama,harga:parseInt(harga),qty:1});
}
renderTabel();
}

function hapusItem(i){
items.splice(i,1);
renderTabel();
}

function clearCart(){
Swal.fire({
title:'Kosongkan keranjang?',
icon:'question',
showCancelButton:true,
confirmButtonText:'Ya'
}).then(r=>{
if(r.isConfirmed){
items=[];
renderTabel();
}
});
}

function hitungKembalian(){
let total = items.reduce((sum,i)=>sum+(i.qty*i.harga),0);
let bayar = document.getElementById('uangBayar').value;
let kembali = bayar - total;

document.getElementById('textKembalian').innerText='Rp '+(kembali>=0?kembali.toLocaleString('id-ID'):0);
document.getElementById('btnBayar').disabled = (items.length===0 || kembali<0 || bayar=="");
}

function renderTabel(){
let html='';
let total=0;
items.forEach((item,i)=>{
let sub=item.qty*item.harga;
total+=sub;
html+=`
<tr>
<td>${item.nama}<input type="hidden" name="ProdukID[]" value="${item.id}"></td>
<td><input type="number" name="Jumlah[]" class="form-control form-control-sm text-center" value="${item.qty}" readonly></td>
<td>Rp ${sub.toLocaleString('id-ID')}</td>
<td><button type="button" class="btn btn-sm text-danger" onclick="hapusItem(${i})"><i class="fas fa-times"></i></button></td>
</tr>`;
});
document.querySelector('#tabelPesanan tbody').innerHTML=html;
document.getElementById('totalHarga').innerText='Rp '+total.toLocaleString('id-ID');
hitungKembalian();
}

function confirmBayar(){
Swal.fire({
title:'Konfirmasi Pembayaran?',
icon:'question',
showCancelButton:true,
confirmButtonText:'Ya, Bayar'
}).then(r=>{
if(r.isConfirmed){
document.getElementById('formTransaksi').submit();
}
});
}

// SEARCH PRODUK
document.getElementById('searchProduk').addEventListener('keyup',function(){
let keyword=this.value.toLowerCase();
document.querySelectorAll('.produk-card').forEach(card=>{
let text=card.innerText.toLowerCase();
card.style.display = text.includes(keyword)?'block':'none';
});
});
</script>

</body>
</html>
