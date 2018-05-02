@extends('layout')

@section('content')

<h2>Add User</h2>

<div class="col-md-6 col-sm-12" id="app">
    <div v-if="fail">
        <div v-for="(item, index) in dataErrors" class="alert alert-danger">
            @{{ index }}
        </div>
    </div>
    <div v-if="sucess">
        <div class="alert alert-success">
            @{{ msg }}
        </div>
    </div>

    {{ Form::open(array('route' => 'user.save','files'=>true,'method' => 'post','id' =>'form-save-user',' @submit'=>"submitForm")) }}

    <div class="form-group">
        {{Form::label('name', 'Name');}}
        {{ Form::hidden('id','',array('type'=>"hidden"));}}
        {{ Form::text('name','',array('class'=>"form-control" ));}}
    </div>

    <div class="form-group">
        {{Form::label('email', 'E-mail address');}}
        {{ Form::text('email','',array('class'=>"form-control",'type'=>'email'));}}
    </div>

    <div class="form-group">
        {{Form::label('phone', 'Phone');}}
        {{ Form::text('phone','',array('class'=>"form-control"));}}
    </div>

    <div class="form-group">
        {{Form::label('gender', 'Gender');}}
        {{Form::select('gender', array(1 => 'Male', 0 => 'Female'),'',array('class'=>"form-control"))}}
    </div>

    <div class="form-group">
        {{Form::label('date_of_birth', 'Date of birth');}}
        {{ Form::text('date_of_birth','',array('class'=>"form-control datepicker"));}}
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
        <th>Gender</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Date of birth</th>
        <th>Biography</th>
        <th>Profile picture</th>
        <th>Actions</th>
    </tr>
    <tbody class="rows">
        @foreach($users as $user)
        <tr id="id_{{ $user->id }}">
            <td data-field="id" >{{ $user->id }}</td>
            <td data-field="name" >{{ $user->name }}</td>
            <td data-field="gender" >{{ $user->gender?'Male':'Femal' }}</td>
            <td data-field="email" >{{ $user->email }}</td>
            <td data-field="phone" >{{ $user->phone }}</td>
            <td data-field="date_of_birth" >{{ $user->date_of_birth }}</td>
            <td data-field="biography" >{{ $user->biography }}</td>
            <td data-field="profile_picture" >
                @if(!empty($user->profile_picture))
                <img src="{{ asset('upload/'.$user->profile_picture) }}" style="width: 100px;" />
                @endif
            </td>
            <td>
                <a href="#" class="edit-link">Edit</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/vue.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/vue-resource.js') }}"></script>
<script>
$(document).on('click', '.edit-link', function () {
    var tr = $(this).parent().parent();
    $.each(tr.find('td'), function (k, td) {
        var name = $(td).attr('data-field');
        $('[name="' + name + '"]').val($(td).html());
        if (name == 'gender') {
            console.log($(td).html());
            if ($(td).html() == "Male")
                $('[name="' + name + '"] option[value=1]').attr('selected', 'selected');
            else
                $('[name="' + name + '"] option[value=0]').attr('selected', 'selected');
        }
    })
});
$(document).ready(function () {
    Vue.http.options.emulateJSON = true;
    var imgUrl = '{{ asset("upload/") }}';
    var app = new Vue({
        el: '#app',
        data: {
            fail: false,
            sucess: false,
            msg: '',
            dataErrors: []
        },
        methods: {
            submitForm: function (event) {
                event.preventDefault();

                this.ajaxRequest = true;
                var payload = new FormData($('#form-save-user')[0]);
                var url = $('#form-save-user').attr('action');

                // send get request

                this.$http.post(url, payload, function (data) {
                    if (data['status'] == "fails") {
                        this.fail = true;
                        this.sucess = false;
                        this.dataErrors = data['msg'];
                    } else {
                        this.fail = false;
                        this.sucess = true;
                        this.msg = data['msg'];
                        $('#form-save-user')[0].reset();
                        if (data['user']['gender'] == 0)
                            data['user']['gender'] = 'Female';
                        else
                            data['user']['gender'] = 'Male';

                        var tds = '<td data-field="id" >' + data['user']['id'] + '</td>' +
                                '<td data-field="name" >' + data['user']['name'] + '</td>' +
                                '<td data-field="gender" >' + data['user']['gender'] + '</td>' +
                                '<td data-field="email" >' + data['user']['email'] + '</td>' +
                                '<td data-field="phone" >' + data['user']['phone'] + '</td>' +
                                '<td data-field="date_of_birth" >' + data['user']['date_of_birth'] + '</td>' +
                                '<td data-field="biography" >' + data['user']['biography'] + '</td>' +
                                '<td data-field="profile_picture" >';
                        if (data['user']['profile_picture'] != '')
                            tds += '<img src="' + imgUrl + '/' + data['user']['profile_picture'] + '" style="width: 100px;" />';
                        tds += '</td>' +
                                '<td><a href="#" class="edit-link">Edit</a></td>';

                        if ($('#id_' + data['user']['id']).length > 0) {
                            $('#id_' + data['user']['id']).html(tds);
                        } else {
                            $('.rows').append('<tr id="id_' + data['user']['id'] + '" >' + tds + '</tr>');
                        }
                    }
                });
            }
        }
    });
});
</script>

@stop