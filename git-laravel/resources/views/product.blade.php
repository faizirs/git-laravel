<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <title>Product</title>
    <style>
        body{
            background-color: #c9c9c9;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            width: 250px;
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .content {
            margin-left: 270px;
            padding: 20px;
        }
        .list-group-item.active {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <ul class="list-group">
        <li class="list-group-item active">MAIN MENU</li>
        <a href="{{ route('product.index') }}" class="list-group-item" style="color: #212529;">Product</a>
        <li class="list-group-item">Profile</li>
        <a href="{{ route('product.logout') }}" class="list-group-item" style="color: #212529;">Logout</a>
    </ul>
</div>

<div class="content">
    <div class="card">
        <div class="card-header">
            <label><h3>Dashboard</h3></label>
            <hr>
            <b>Selamat Datang {{ Auth::user()->name }}</b>
        </div>
        <div class="card-body">
            <a href="{{ route('product.create') }}" class="btn btn-md btn-success mb-3">ADD PRODUCT</a>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">IMAGE</th>
                        <th scope="col">TITLE</th>
                        <th scope="col">PRICE</th>
                        <th scope="col">STOCK</th>
                        <th scope="col" style="width: 20%">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="text-center">
                                <img src="{{ asset('products/' . $product->image) }}" alt="{{ $product->title }}" class="rounded" style="width: 100px">
                            </td>
                            <td>{{ $product->title }}</td>
                            <td>{{ "Rp " . number_format($product->price,2,',','.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td class="text-center">
                                <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('products.destroy', $product->id) }}" method="POST">
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-dark">SHOW</a>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary">EDIT</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <div class="alert alert-danger">
                            Data Products belum Tersedia.
                        </div>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script>
    //message with sweetalert
    @if(session('success'))
        Swal.fire({
            icon: "success",
            title: "BERHASIL",
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
    @elseif(session('error'))
        Swal.fire({
            icon: "error",
            title: "GAGAL!",
            text: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 2000
        });
    @endif
</script>

</body>
</html>
