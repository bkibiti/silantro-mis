@extends("layouts.master")

@section('content-title')
   Product Subcategories
@endsection

@section('content-sub-title')
<li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item"><a href="#">Masters / Product Subcategories</a> </li>
@endsection

@section("content")
  <div class="col-sm-12">
        <div class="card-block">
            <div class="col-sm-12">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <button style="float: right;margin-bottom: 2%;" type="button" class="btn btn-secondary btn-sm"
                                data-toggle="modal"
                                data-target="#create">
                            Add Subcategory
                        </button>
           
                          <div class="table-responsive">
                              <table id="fixed-header" class="display table nowrap table-striped table-hover" style="width:100%">
                                  <thead>
                                      <tr>
                                          <th>Category</th>
                                          <th>Subcategory</th>
                                          <th>Actions</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr>

                                          @foreach($subcategories as $subcategory)

                                              <td>{{$subcategory->category['name']}}</td>
                                              <td>{{$subcategory->name}}</td>
                                         
                                          <td>
                        <a href="#">
                       <button class="btn btn-sm btn-rounded btn-info" 
                       data-subcategory_id="{{$subcategory->id}}"
                       data-category_name="{{$subcategory->category_id}}"
                       data-subcategory_name="{{$subcategory->name}}" 
                       type="button"
                       data-toggle="modal" data-target="#edit">Edit
                       </button>
                           </a>
                     <a href="#">
                     <button class="btn btn-sm btn-rounded btn-danger" data-id="{{$subcategory->id}}" 
                      data-subcategory_name="{{$subcategory->name}}"
                      data-category_name="{{$subcategory->category_id}}"
                      type="button"data-toggle="modal" data-target="#delete"> Delete
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
</div>


@endsection

@include('masters.sub_categories.create')
@include('masters.sub_categories.delete')
@include('masters.sub_categories.edit')

@push("page_scripts")
@include('partials.notification')

 <script>

              $('#edit').on('show.bs.modal', function (event) {
               var button = $(event.relatedTarget)
                var modal = $(this)

                modal.find('.modal-body #id_edit').val(button.data('subcategory_id'))
                modal.find('.modal-body #subcategory_edit').val(button.data('subcategory_name'))
                modal.find('.modal-body #category_edit').val(button.data('category_name'))
            });

            $('#delete').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)

                var message = "Are you sure you want to delete '".concat(button.data('subcategory_name'), "'?");
                var modal = $(this)

                modal.find('.modal-body #message').text(message)
                modal.find('.modal-body #id_edit').val(button.data('id'))
            });

        </script>
    @endpush
