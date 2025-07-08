<!-- resources/views/products.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kasir Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

    <h1 class="mb-4">Kasir Produk</h1>

    <form id="productForm" class="mb-4">
        @csrf
        <div class="mb-3">
            <label class="form-label">Kode</label>
            <input type="text" name="kode" class="form-control" placeholder="Kode" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" placeholder="Nama" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="harga" class="form-control" placeholder="Harga" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" placeholder="Stock" required>
        </div>
        <div class="mb-3">
            <button type="button" class="btn btn-primary" onclick="loadProducts()">View</button>
            <button type="button" class="btn btn-success" onclick="insertProduct()">Insert</button>
            <button type="button" class="btn btn-warning" onclick="updateProduct()">Update</button>
            <button type="button" class="btn btn-danger" onclick="deleteProduct()">Delete</button>
        </div>
    </form>

    <div id="productList" class="mt-4"></div>

    <script>
        function loadProducts() {
    fetch('/products')
        .then(response => response.json())
        .then(data => {
            let output = '<ul class="list-group">';
            data.forEach(product => {
                output += `<li class="list-group-item">${product.id}. <strong>${product.kode}</strong> - ${product.nama} - Rp${product.harga} - Stock: ${product.stock}</li>`;
            });
            output += '</ul>';
            document.getElementById('productList').innerHTML = output;
        })
        .catch(error => console.error('Error:', error));
}


    
function insertProduct() {
    const kode = document.querySelector('[name="kode"]').value;
    const nama = document.querySelector('[name="nama"]').value;
    const harga = document.querySelector('[name="harga"]').value;
    const stock = document.querySelector('[name="stock"]').value;

    fetch('/products', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            kode: kode,
            nama: nama,
            harga: parseInt(harga),
            stock: parseInt(stock)
        })
    })
    .then(response => {
        if (!response.ok) throw new Error('Insert error');
        return response.json();
    })
    .then(() => {
        alert('Insert berhasil');
        loadProducts();
        document.getElementById('productForm').reset();
    })
    .catch(() => alert('Gagal insert data, pastikan data valid & tidak duplicate.'));
}




        function updateProduct() {
            const id = prompt('Masukkan ID produk yang mau diupdate:');
            const formData = new FormData(document.getElementById('productForm'));

            fetch(`/products/${id}`, {
                method: 'POST',
                headers: {
                    'X-HTTP-Method-Override': 'PUT',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(() => loadProducts())
            .catch(() => alert('Gagal update data'));
        }

        function deleteProduct() {
            const id = prompt('Masukkan ID produk yang mau dihapus:');

            fetch(`/products/${id}`, {
                method: 'POST',
                headers: {
                    'X-HTTP-Method-Override': 'DELETE',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(() => loadProducts())
            .catch(() => alert('Gagal hapus data'));
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
