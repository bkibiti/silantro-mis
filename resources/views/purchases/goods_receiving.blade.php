@extends("layouts.master")

@section('content-title')

    Purchase

@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#">Purchase Management / Purchase</a></li>
@endsection
@section("content")

    <div class="col-sm-6">
            <div class="card">
                    <div class="card-body">
                        <div id="product-table" class="table-responsive">
                            <table id="products" class="display table nowrap table-striped table-hover" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                    <tbody>
                                        @foreach($products as $prod)
                                        <tr>
                                            <td>{{$prod->name}}</td>
                                            <td>{{$prod->category->name}}</td>
                                            <td>
                                                <a href="#">
                                                    <button class="btn btn-sm btn-rounded btn-info selectproduct"
                                                            data-id="{{$prod->id}}"
                                                            data-name="{{$prod->name}}"
                                                            data-category_id="{{$prod->category->id}}"
                                                            data-purchase_uom="{{$prod->purchase_uom}}"
                                                            data-quantity_per_unit="{{$prod->quantity_per_unit}}"
                                                            data-toggle="button">Select
                                                    </button>
                                                </a>

                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
    </div>
    <div class="col-sm-6">
          <div class="card">
                <div class="card-body">
                        <div class="panel-body">
                                <form method="POST" action="{{ route('goods-receiving.store') }}" >
                                    @csrf
                                    <input type="hidden" name="id" id="id">
                                  
                                    
                                    <div class="form-group row">
                                        <label for="category" class="col-md-4 col-form-label text-md-right">Supplier<font color="red">*</font></label>

                                        <div class="col-md-8">
                                            <select class="form-control select2"  class="form-control" name="supplier" required data-width="100%">
                                                    <option value="">Select Supplier</option>

                                                    @foreach($suppliers as $supp)
                                                    <option value="{{ $supp->id }}">{{ $supp->name }}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                </div>
                                    <div class="form-group row">
                                        <label for="product_name" class="col-md-4 col-form-label text-md-right">Product</label>

                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="name" name="name" maxlength="50" minlength="2"
                                                placeholder="" required value="{{ old('name') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                            <label for="category" class="col-md-4 col-form-label text-md-right">Purchase Unit</label>
                                            <div class="col-md-8">
                                                    <input type="text" class="form-control" id="purchase_uom" readonly>
                                            </div>
                                    </div>
                                    <div class="form-group row">
                                            <label for="category" class="col-md-4 col-form-label text-md-right" >Quantity per Unit</label>
                                            <div class="col-md-8">
                                                    <input type="number" class="form-control" id="quantity_per_unit" name="quantity_per_unit" readonly>
                                            </div>
                                    </div>
                                 

                                    <div class="form-group row">
                                            <label for="category" class="col-md-4 col-form-label text-md-right">Quantity Purchased<font color="red">*</font></label>
                                            <div class="col-md-8">
                                                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" required
                                                        value="{{ old('quantity') }}">
                                            </div>
                                    </div>
                                    <div class="form-group row">
                                            <label for="category" class="col-md-4 col-form-label text-md-right">Purchase Unit Cost<font color="red">*</font></label>
                                            <div class="col-md-8">
                                                    <input  class="form-control" id="unit_cost" name="unit_cost" min="1" required
                                                        value="{{ old('unit_cost') }}">
                                            </div>
                                    </div>


                                <div class="modal-footer">
                                        

                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                </div>
          </div>
    </div>

@endsection
@push("page_scripts")
    @include('partials.notification')

    <script>
        $('#products').DataTable({
            bAutoWidth: true,
            lengthChange: false,
            scrollY:  "400px",
            scrollCollapse: true,
            paging: false,
            bInfo: false,

        });

        $('.selectproduct').click(function() {

            $('#id').val($(this).data('id'));
            $('#name').val($(this).data('name'))
            $('#purchase_uom').val($(this).data('purchase_uom'))
            $('#quantity_per_unit').val($(this).data('quantity_per_unit'))


        });


      

    </script>

@endpush
