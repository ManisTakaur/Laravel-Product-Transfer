@extends('layouts.app')
@section('content')
{{--Sangit is author--}}



    <meta name="csrf-token" content="{{ csrf_token() }}"/>




    <script>


        let pro_name;
        let pro_id;
        let data_product = [];
        let row;
        let alert_product = 0;
        let availableStock = 0;
        let sizeProduct;
        let price_global=0;
        let important={
            total_checkout_value : 0,
            grand_total:0
        };
        // function button_disable() {
        //     document.getElementById("BtnSave").disabled = true;
        // }
        // window.onload = button_disable;
        // function button_enable() {
        //     document.getElementById('BtnSave').disabled=false;

        // }

        function delete_product(row,pro_id,type) {
            let chk = confirm("Are You Sure to Delete This?");
            if (chk) {
                let i = row.parentNode.parentNode.rowIndex;

                if(type=="product")
                document.getElementById("hist_table").deleteRow(i);
                else
                document.getElementById("hist_table_service").deleteRow(i);

                let index = productCheckPreviousEntry(pro_id,type);
                data_product.splice(index, 1);
                calculate_price_all();

            }
        }

        function select_pro_gtter(pID,pName,AVS,size,price) {
            pro_id = pID;
            availableStock = AVS;
            pro_name= pName;
            sizeProduct =size;
            price_global = price;

            if (pro_id < 10000)
                alert_product = 1;
            else
                alert_product = 0;

            array_data();
        }
        

        function stockCheck(r,value) {
            if(value=="")
                return true;

            if(data_product[r].availableStock>=parseInt(value))
                return true;
            else return false;

        }
        function multiply(r,type) {
        //    alert('s');
            let pID = productCheckPreviousEntry(r,type);
            let textValue1 = document.getElementById('quantity'+r+'_'+type).value;
            let textValue2 = document.getElementById('price'+r+'_'+type).value;
            if(!isInt(textValue1)){
                alert("Fractional quantity is not possible!");
                document.getElementById('quantity'+r+'_'+type).value =1;
                return;
            }
            else if(parseInt(textValue1)<0){
                alert("Negative quantity not allowed!");
                document.getElementById('quantity'+r+'_'+type).value =1;
                return ;
            }

            
                if(stockCheck(pID,textValue1)){
                    document.getElementById('tPrice'+r+type).innerHTML = Math.ceil(parseInt(textValue1) * textValue2);
                    let pID = productCheckPreviousEntry(r,type);
                    data_product[pID].quantity=textValue1;
                    data_product[pID].price=textValue2;
                }
                else {
                    document.getElementById('quantity'+r).value=1;
                    alert("Not available stock");
                }
            

            calculate_price_all();
            //console.log(data_product);
        }

        

        function array_data() {

                let quantity = 1;
                let price = price_global;
                let value = parseInt(quantity);

                price = Math.ceil(price);

                if(availableStock==-1){}
                else if (availableStock == 0 || value > availableStock)
                    alert("Out of stock Product Please check");
                else {

                    if(productCheckPreviousEntry(pro_id,'product')==-1) {

                        let productInformation ={
                            proName: pro_name,
                            proID:pro_id,
                            quantity:quantity,
                            price:price,
                            availableStock:availableStock,
                            type: 'product'
                        };

                        data_product.push(productInformation);

                        let table = document.getElementById("hist_table");

                        let row = table.insertRow(-1);

                        let proName = row.insertCell(0);
                        let qt = row.insertCell(1);
                        let priceRow = row.insertCell(2);
                        let total= row.insertCell(3);
                        let button = row.insertCell(4);
                        let current_row = data_product.length-1;

                        let s = '<button class="btn btn-danger btn-sm" onclick="delete_product(this,'+pro_id+',\'product\');"><i class="fa fa-trash "></i></button>'
                        proName.innerHTML = pro_name+"("+sizeProduct+")";
                        qt.innerHTML = "<input type='number' id='quantity"+pro_id+"_product' onkeyup='multiply("+pro_id+",\"product\")' value="+quantity+" style='width:80%;'>";
                        priceRow.innerHTML = "<input type='number' id='price"+pro_id+"_product' onkeyup='multiply("+pro_id+",\"product\")' value="+price+" style='width:80%;' disabled>";
                        button.innerHTML = s;
                        total.innerHTML = "<span id='tPrice"+pro_id+"product'>"+price+"</span>";

                        price_global=0;
                        pro_id = -1;
                        availableStock = 0;
                        pro_name= "";
                        sizeProduct ="";
                    }
                    else{
                        alert("Product already added! please Check");
                    }
            }
            calculate_price_all();
        }
        function productCheckPreviousEntry(proId,type) {
            let checker=-1;
            for(let i=0;i<data_product.length;i++){
                if(data_product[i].proID==proId && data_product[i].type==type)
                    checker=i;
            }
            return checker;
        }


        function init_important_data() {
            important={
                total_checkout_value : 0,
                
                grand_total:0
            };
        }
        function calculate_price_all() {
            init_important_data();
            for(let i=0;i<data_product.length;i++){
                important.total_checkout_value += (data_product[i].quantity*data_product[i].price)
            }
            
            important.grand_total = parseInt(important.total_checkout_value);
            //Displaying all value
            $("#grand_total").text("৳ "+ important.grand_total);
        }

        function array_data_save() {
            let request;
            
            // important.payment_method= paymentMethod;
            if (important.total_checkout_value == 0) {
                alert("Checkout can't be created at Zero Price");
            }
            else {
                data_product.push(important);
                $.ajax({
                    data: {data: data_product},
                    url: '/save-checkout',
                    type: 'POST',
                    beforeSend: function (request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function (response) {
                        window.location="/create-checkout";
                    }
                });
            }

            
        }

    </script>

            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            <?php
            echo Session::put('message', '');
            ?>

            <?php
            $all_published_product = \App\Product::where('availableQty','!=',0)->get();
            ?>

            <div class="row">

                <div class="col-md-5">
                    <div class="box box-solid box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title"> Create New Product Checkout</h3>
                        </div>
                        <div class="box-body">
                            <div class="panel-group" id="accordion">
                
                              <div class="panel panel-primary">
                                <div class="panel-heading">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" style="color:white;">
                                  <h4 class="panel-title">
                                    Select Products
                                    <i class="fa fa-arrow-circle-down pull-right"></i>
                                  </h4>
                                </a>
                                </div>
                                <div id="collapse2" class="panel-collapse">
                                  <div class="panel-body">
                                      <table id="productsTBL" class="table table-condensed" style="width:100%">
                                          <thead>
                                          <th>Product(size)</th>
                                          <th>Brand</th>
                                          <th>Price</th>
                                          <th>Add</th>
                                          </thead>

                                          <tbody>

                                          </tbody>
                                      </table>
                                  </div>
                                </div>
                              </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="box box-widget widget-user-2">
                        <div class="widget-user-header">

                            <h3 class="widget-user-username">Cart</h3>
                            <h5 class="widget-user-desc">Checkout Maker</h5>


                        </div>

                        <div class="box-body">
                            

                            <table id="hist_table" class="table table-striped">
                                <thead>
                                <tr>
                                    <th><i class="icon-sort"></i> Products</th>
                                    <th><i class="icon-sort"></i> Qty</th>
                                    <th><i class="icon-sort"></i> Price</th>
                                    <th><i class="icon-sort"></i> Total</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <br/> <br />

                            <table class="table-striped table col-sm-offset-3" style="width:50%">
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td> Grand Total: </td>
                                        <td id="grand_total">৳ 0</td>
                                    </tr>
                                </tbody>
                            </table>


                            <br/> <br />
                            <a href="#" onclick="array_data_save()" class="btn btn-primary btn-sm">Save All</a>
                    </div>
                    </div>
                    <!-- /.widget-user -->
                </div>



               
<!-- DataTables -->

<script src="{{ asset('/styleResource/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('/styleResource/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>

<script>
    $(function () {
        $('.select2').select2();

       
        $('#productsTBL').DataTable({
                   processing: true,
                   serverSide: true,
                   ajax: '/product-ss',
                   columns: [
                       {data: 'pName'},
                       {data: 'brand'},
                       {data: 'price'},
                       {data:  'action'},
                   ],
                   drawCallback: function() {
                       $('[data-toggle="popover"]').popover();
                   }
        });
    });
</script>




@endsection