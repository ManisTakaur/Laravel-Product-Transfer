@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <?php
    $user_id=Session::get('categoryEmployId');
    ?>
    <script>

        
        function getProducts(r,grand_total) {
            checkoutID=r;
            $.ajax({
                url: '/view-checkout-details/'+r,
                type: 'GET',
                beforeSend: function (request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function (response) {
                    let checkoutDetailsOption =
                       `<p class="alert" style="color:black; border-bottom:1px solid black;"> checkout ID: <label id="display_ID" class="label" style="font-size:13px; color:red;border:1px solid black; border-radious: 10/8px;">${r}</label>
                           <br><br>
                           <label style="font-size:13px;">Total Price: </label>
                           <label class="label label-success" style="font-size:13px; color:black; "> ৳ ${grand_total} </label>
                         </p>

                        `;

                    var t = $('#productsDetails').DataTable();
                    t.clear().draw();
                    document.getElementById("contentcheckout").innerHTML= checkoutDetailsOption;
                    console.log(response);
                    
                    $.each(response, function (i, data) {
                        t.row.add( [
                            data.product.pName+" ×"+data.checkoutQuantity,
                            data.product.brand,
                            data.product.size,
                            data.checkoutPrice,
                            data.product.category,
                        ] ).draw( true );


                    });
                    
                }
            });

        }
        
        
    </script>
    <div id="loader"></div>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <?php
    echo Session::put('message', '');
    ?>
    @if (session('info'))
        <div class="alert alert-danger">
            {{ session('info') }}
        </div>
    @endif
    <?php
    echo Session::put('info', '');
    ?>
    <hr class="alert-info">

    <div class="row">
        <div class="col-md-5">
            @component('components.widget')
                @slot('title')
                    View Product Checkouts
                @endslot
                @slot('body')
                        @component('components.table')
                            @slot('tableID')
                                productsTBL
                            @endslot
                            @slot('head')
                                    <th><i class="fa fa-sort"></i> ID </th>
                                    <th><i class="fa fa-sort"></i> Grand Total </th>
                                    <th><i class="fa fa-sort"></i> Date</th>
                                    <th><i class=""></i> Action</th>
                            @endslot
                                @slot('body')
                                @foreach ($checkout as $value)
                                    <tr>
                                        <td>{{ $value->id}}</td>
                                        <td>৳ {{$value->grand_total}}</td>
                                        <td>{{ \Carbon\Carbon::parse($value->created_at)->format("Y-m-d h:i A")}}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" 
                                            onclick='getProducts(
                                                "{{$value->id}}","{{$value->grand_total}}")'><i class="fa fa-eye-slash"></i> Details</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endslot
                        @endcomponent
                 @endslot
               @endcomponent
        </div>
            <div class="col-md-7">
                    @component('components.widget')
                         @slot('title')
                            View Checkout Details
                         @endslot
                         @slot('description')
                              <div class="col-sm-12" id="contentcheckout"></div>
                         @endslot
                         @slot('body')
                            
                                {{-- <span class="h4 text-success">  Products</span> --}}
                                 {{-- <hr class="alert-success"/> --}}
                                 @component('components.table')
                                     @slot('tableID')
                                         productsDetails
                                     @endslot
                                         @slot('head')
                                         <th>Product xQty</th>
                                         <th> Brand </th>
                                         <th> Size </th>
                                         <th>Price </th>
                                         <th>Category</th>
                                         @endslot
                                 @endcomponent

                             @endslot
                        @endcomponent
            </div>
        </div>
    <script>
        $(function () {

            $('[data-toggle="popover"]').popover();



            $('#productsTBL').DataTable({

                'paging'      : true,
                'lengthChange': false,
                'searching'   : true,
                'ordering'    : false,
                'info'        : true,
                'autoWidth'   : true
            });
            $('#productsDetails').DataTable({

                'paging'      : false,
                'lengthChange': false,
                'searching'   : false,
                'ordering'    : false,
                'info'        : false,
                'autoWidth'   : true
            });
            

        });




    </script>


@endsection