@extends('layouts.app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


<div class="row">
    <div class="col-md-12">

        @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif
        <?php
         echo Session::put('message','');
         ?>
            @component('components.widget')
            @slot('title') Products
            @endslot
            @slot('description') Manage Your Products Here <br /> <br />
            <button class="btn btn-success btn-flat" data-toggle="modal" data-target="#add_Product">
                <i class="fa fa-plus-square"></i> Add new Product</button>
            @endslot
            @slot('body')
            @component('components.table')
            @slot('tableID') Product_table
            @endslot
            @slot('head')
            <th>Product Name</th>
            <th>Category</th>
            <th>Brand</th>
            <th>Size</th>
            <th>Price</th>
            <th>Quanity</th>
            <th>Availiable Quanity</th>
            
            @endslot
            @slot('body')

            @endslot
            @endcomponent
    </div>
</div>
@endslot
@endcomponent

<div class="modal fade" id="add_Product">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add new Product</h4>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tbody>

                        <tr>
                            <td></td>
                            <td>Product Name</td>
                            <td><input type="text" id="name" required style="width:100%"/></td>
                            <td></td>
                        </tr>
                        <tr>
                                <td></td>
                                <td>Product Size</td>
                                <td><input type="text" id="size" required style="width:100%"/></td>
                                <td></td>
                            </tr>
                        <tr>
                            <td></td>
                            <td>Price</td>
                            <td><input type="number" id="price" style="width:100%" /></td>
                            <td></td>
                        </tr>
                        <tr>
                                <td></td>
                                <td>Product Brand</td>
                                <td><input type="text" id="brand" required style="width:100%"/></td>
                                <td></td>
                        </tr>
                        <tr>
                                <td></td>
                                <td>Product Category</td>
                                <td><input type="text" id="category" required style="width:100%"/></td>
                                <td></td>
                            </tr>
                        <tr>
                                <td></td>
                                <td>Product Quanity</td>
                                <td><input type="text" id="quantity" required style="width:100%"/></td>
                                <td></td>
                            </tr>
                            
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" class="btn btn-success btn-sm" data-dismiss="modal" onclick="save_Product()">SAVE</button>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    </div>
    
    <script>
        $(function() {
            $('.select2').select2({
                placeholder: "Select a category",
            });
            
            Product_load();

            $('#Product_table').DataTable({
                'paging'      : true,
                'lengthChange': false,
                'searching'   : true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : true
            })
        });
        

        function save_Product() {
            let data={};
            data={
                pName: $("#name").val(),
                size : $("#size").val(),
                brand : $("#brand").val(),
                quantity: $("#quantity").val(),
                price: $("#price").val(),
                category : $("#category").val()
            }
            if((data.price)<0){
                    alert("Negetive Values are not ALLOWED");
                    return;
            }

            $.ajax({
                data: {
                    data: data
                },
                url: '/save-products',
                type: 'POST',
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                  closeModel('add_Product');
                    Product_load();
                    showSnakBar();

                }
            });
        }
        

        
        function Product_load() {
            $.ajax({
                url: '/product-all',
                type: 'GET',
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    let t = $('#Product_table').DataTable();
                    t.clear().draw(true);
                    response.forEach(function(temp) {                        
                        t.row.add([
                            temp.pName,
                            temp.category,
                            temp.brand,
                            temp.size,
                            temp.price,
                            temp.quantity,
                            temp.availableQty
                        ]).draw(true);
                });
                }
            });
        }
        function closeModel(model_name) {
          $("#name").val("");
          $("#price").val("");
          $("#categoryName").val("");
            $("#" + model_name).modal('hide');
        }
    </script>

    @endsection
