@extends('admin.layouts.layout')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Products Management</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Products</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Products</h3>
                                {{-- @if ($products_module['edit_access'] == 1 || $products_module['full_access'] == 1) --}}
                                <a style="max-width: 150px; float: right; display:inline-block"
                                    href="{{ url('admin/add-edit-product') }}" class="btn btn-block btn-primary">Add
                                    Products</a>
                                {{-- @endif --}}
                            </div>
                            @if (Session::has('success_message'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success!</strong> {{ Session::get('success_message') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="products" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Product Code</th>
                                            <th>Product Color</th>
                                            <th>Category</th>
                                            <th>Parent Category</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td>{{ $product['product_name'] }}</td>
                                                <td>{{ $product['product_code'] }}</td>
                                                <td>{{ $product['product_color'] }}</td>
                                                <td>{{ $product['category']['category_name'] }}</td>
                                                <td>
                                                    @if (isset($product['category']['parent_category']['category_name']))
                                                        {{ $product['category']['parent_category']['category_name'] }}
                                                    @else
                                                        ROOT
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($product['status'] == 1)
                                                        <a class="updateProductStatus" id="product-{{ $product['id'] }}"
                                                            product_id="{{ $product['id'] }}" href="javascript:void(0)"
                                                            style="color:#3f6ed3"><i class="fas fa-toggle-on"
                                                                status="Active"></i></a>
                                                    @else
                                                        <a class="updateProductStatus" id="product-{{ $product['id'] }}"
                                                            product_id="{{ $product['id'] }}" href="javascript:void(0)"
                                                            style="color:grey"><i class="fas fa-toggle-off"
                                                                status="Inactive"></i></a>
                                                    @endif
                                                    &nbsp;&nbsp;
                                                    <a style="color:#3f6ed3"
                                                        href="{{ url('admin/add-edit-product/' . $product['id']) }}"><i
                                                            class="fas fa-edit"></i></a>
                                                    &nbsp;&nbsp;
                                                    <a class="confirmDelete" title="Delete Product"
                                                        href="javascript:void(0)" record='product' style="color:#3f6ed3"
                                                        recordid="{{ $product['id'] }}"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->


    </div>
    <!-- /.content-wrapper -->
@endsection
