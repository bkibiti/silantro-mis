@extends("layouts.master")


@section('page_css')
@endsection

@section('content-title')
    Users

@endsection

@section('content-sub-title')
    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="feather icon-home"></i></a></li>
    <li class="breadcrumb-item"><a href="#"> User Management / Users </a> </li>
@endsection



@section("content")

<div class="col-sm-12">

<div class="card">



    <div class="card-body">
        <form id="expense_form" action="{{route('users.search')}}" method="GET">
            @csrf()

            <div class="form-group row">

                <div class="col-md-2">
                    <select class="form-control select2"  class="form-control" name="status"  data-placeholder="Select Status" required data-width="100%">
                        <option value="0" {{ (old('status')==0 ? "selected":"") }} >All Users</option>
                        <option value="1" {{ (old('status')==1 ? "selected":"") }} >Active Users</option>
                        <option value="2" {{ (old('status')==2 ? "selected":"") }} >In Active Users</option>
                    </select>
                </div>
              
               
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success">Filter</button>
                </div>

                <div class="col-md-6"></div>
                <div class="col-md-2">
                    @can('Manage Users')
                        <button type="button" style="float: right; margin-bottom: 1%;" class="btn btn-icon btn-rounded btn-warning btn-sm" data-toggle="modal" 
                                     title="Add User" data-target="#register"><i class="feather icon-plus"></i></button>
                    @endcan
                </div>
            </div>

        </form>

        <hr>
      
          
        <div class="table-responsive">
            <table id="fixed-header" class="display table nowrap table-striped table-hover" style="width:100%">
            <thead>
                <tr>
                <th>Name</th>
                <th>Username</th>
                <th>E-mail</th>
                <th>Mobile</th>
                <th>Role</th>
                <th>Position</th>
                <th>Status</th>
                @can('Manage Users')
                <th>Actions</th>
                @endcan
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                <td>{{$user->name}} </td>
                <td>{{$user->username}} </td>
                <td>{{$user->email}}</td>
                <td>{{$user->mobile}}</td>
                <td>{{ implode(", ", $user->getRoleNames()->toArray()) }}</td>
                <td>{{$user->position}}</td>
                <td>
                    @if ($user->status == 1)
                        <h6 class="m-0 text-c-green">Active</h6>
                    @endif
                    @if ($user->status == -1)
                        <h6 class="m-0 text-c-purple">In-active</h6>
                    @endif
                    @if ($user->status == 0)
                        <h6 class="m-0 text-c-red">De-activated</h6>
                    @endif
                </td>
                @can('Manage Users')
                    <td style='white-space: nowrap'>
                
                        <a href="#">
                            <button class="btn btn-rounded btn-info btn-sm" data-name="{{$user->name}}"
                                data-email="{{$user->email}}" data-id="{{$user->id}}" data-username="{{$user->username}}"
                                data-job="{{$user->position}}"  data-mobile="{{$user->mobile}}"
                                data-role="{{ implode(", ", $user->getRoleNames()->toArray()) }}"
                                type="button" data-toggle="modal" data-target="#editUser">Edit</button>
                        </a>


                            @if ($user->status == 1)
                            <a href="#">
                                <button class="btn btn-danger btn-rounded btn-sm"  type="button" data-toggle="modal" data-target="#disableUser" data-id="{{$user->id}}" data-status="{{$user->status}}" data-name="{{$user->name}}">De Activate</button>
                            </a>
                            @endif
                            @if ($user->status == 0)
                            <a href="#">
                            <button class="btn btn-success btn-rounded btn-sm"  type="button" data-toggle="modal" data-target="#disableUser" data-id="{{$user->id}}" data-status="{{$user->status}}" data-name="{{$user->name}}">Activate</button>
                            </a>
                            @endif


                        </td>
                    @endcan
                       
                </tr>
                @endforeach

                </tbody>
            </table>
        </div>

    </div>

</div>
</div>

@include('users.create')
@include('users.edit')
@include('users.de_activate')



@endsection

@push("page_scripts")
@include('partials.notification')

<script>
  $(document).ready(function(){

      $('#editUser').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var name = button.data('name')
        var username = button.data('username')
        var email=button.data('email')
        var mobile=button.data('mobile')
        var job=button.data('job')
        var role=button.data('role')
        var id=button.data('id')
        var modal = $(this)


        modal.find('.modal-body #name1').val(name);
        modal.find('.modal-body #username1').val(username);
        modal.find('.modal-body #email1').val(email);
        modal.find('.modal-body #position1').val(job);
        modal.find('.modal-body #mobile1').val(mobile).change();
        modal.find('.modal-body #UserID').val(id);

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url:"{{route('getRoleID')}}",
            method:"POST",
            data:{role:role, _token:_token},
            success:function(result)
            {
                $('#role1').val(result).change();
            }
        })

      });//end edit



      //de activate and activate user
      $('#disableUser').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget)
          var id = button.data('id')
          var status=button.data('status')
          var user = button.data('name')
          var modal = $(this)

          if(status == 1){
            var message =  "Are you sure you want to de-activate - ".concat(user)
          }
          if(status == 0){
            var message =  "Are you sure you want to activate - ".concat(user)
          }
          modal.find('.modal-body #userid').val(id);
          modal.find('.modal-body #status').val(status);
          modal.find('.modal-body #prompt_message').text(message);

      })//end

    //delete user
    $('#deleteUser').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget)
          var id = button.data('id')
          var user = button.data('name')
          var modal = $(this)
          var message =  "Are you sure you want to delete '".concat(user);

          modal.find('.modal-body #user').val(id)
          modal.find('.modal-body #message_del').text(message)

      })//end


  });
</script>

@endpush
