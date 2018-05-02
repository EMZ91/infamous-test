@extends('layout')

@section('content')

<h2>Add User</h2>
<div class="col-md-6 col-sm-12" id="app">
    {{ Form::open(array('route' => 'user.save','files'=>true,'method' => 'post','id' =>'form-save-user',' @submit'=>"submitForm")) }}

    <div class="form-group">
        {{Form::label('name', 'Name');}}
        {{ Form::text('name','',array('class'=>"form-control",'zzzzz'=>"name" ));}}
    </div>

    <div class="form-group">
        {{Form::label('email', 'E-mail address');}}
        {{ Form::text('email','',array('class'=>"form-control",'type'=>'email','zzzzz'=>"email"));}}
    </div>

    <div class="form-group">
        {{Form::label('phone', 'Phone');}}
        {{ Form::text('phone','',array('class'=>"form-control",'zzzzz'=>"phone"));}}
    </div>

    <div class="form-group">
        {{Form::label('gender', 'Gender');}}
        {{Form::select('gender', array(1 => 'Male', 0 => 'Female'),'',array('class'=>"form-control",'zzzzz'=>"gender"))}}
    </div>

    <div class="form-group">
        {{Form::label('date_of_birth', 'Date of birth');}}
        {{ Form::text('date_of_birth','',array('class'=>"form-control datepicker",'zzzzz'=>"date_of_birth"));}}
    </div>

    <div class="form-group">
        {{Form::label('biography', 'Biography');}}
        {{ Form::textarea('biography','',array('class'=>"form-control"));}}
    </div>

    <div class="form-group">
        {{Form::label('profile_picture', 'Profile picture');}}

        {{ Form::file('profile_picture','',array('class'=>'form-control-file'));}}
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary mb-2" >Save</button>
    </div>
</div>
{{ Form::close() }}


<table class="table table-responsive table-striped" >
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Gendar</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Actions</th>
    </tr>
    @foreach($users as $user)
    <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->gendar?'Male':'Femal' }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->phone }}</td>
        <td>
            <a href="#">Edit</a> -
            <a href="#">Delete</a>
        </td>
    </tr>
    <p></p>
    @endforeach
</table>


<script src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/vue.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/vue-resource.js') }}"></script>
<script>
$(document).ready(function () {
    var url = "/";

   // Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');


    var app = new Vue({
        el: '#app',
        data: {
            id: '',
            response: null
        },
        methods: {
            submitForm: function (event) {
                event.preventDefault();
                var payload = new FormData('#form-save-user');
                var url = $('#form-save-user').attr('action');
                
                // send get request
                
                this.$http.post(url, payload, function (data) {
                    console.log('zzzzzzzzz');
                    // set data on vm
                    this.response = data;

                });
            }
        }
    });
});
</script>

@stop