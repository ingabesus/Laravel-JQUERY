@extends('layouts.app')

@section('content')
<div class="container">
    <div id="alert" class="alert alert-success">
    </div>    
        <div class="ajaxForm">
            <div class="form-group">
                <label for="type_title">Type title</label>
                <input id="type_title" class="form-control" type="text" name="type_title" />
            </div>
            <div class="form-group">
                <label for="type_description">Type description</label>
                <input id="type_description" class="form-control" type="text" name="type_description" />
            </div>
            <div class="form-group">
                <button id="submit-ajax-form" class="btn btn-primary"> Save </button>   
            </div>
        </div>    
</div>
    <script>
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            console.log("Jquery veikia");
            $("#submit-ajax-form").click(function() {
                let type_title;
                let type_description;
                type_title = $('#type_title').val();
                type_description = $('#type_description').val();
               
                $.ajax({
                    type: 'POST',
                    url: '{{route("type.store")}}' ,
                    data: {type_title: type_title, type_description: type_description  },
                    success: function(data) {
                        $("#alert").html(data);
                    }
                });
            });
         })
    </script>
@endsection