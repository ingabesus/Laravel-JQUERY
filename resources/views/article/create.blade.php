@extends('layouts.app')

@section('content')
<div class="container">


    <div id="alert" class="alert alert-success">
    </div>    

        <div class="ajaxForm">
            <div class="form-group">
                <label for="article_title">Article title</label>
                <input id="article_title" class="form-control" type="text" name="article_title" />
            </div>
            <div class="form-group">
                <label for="article_description">Article description</label>
                <input id="article_description" class="form-control" type="text" name="article_description" />
            </div>
            <div class="form-group"> 
                <label for="article_type_id">Article type ID</label>
                <input id="article_type_id" class="form-control" type="text" name="article_type_id" /> 
            </div>   
            <div class="form-group">
                <button id="submit-ajax-form-article" class="btn btn-primary"> Save </button>   
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
            $("#submit-ajax-form-article").click(function() {
                let article_title;
                let article_description;
                let article_type_id;
                article_title = $('#article_title').val();
                article_description = $('#article_description').val();
                article_type_id = $('#article_type_id').val();
                $.ajax({
                    type: 'POST',
                    url: '{{route("article.store")}}' ,
                    data: {article_title: article_title, article_description: article_description, article_type_id: article_type_id  },
                    success: function(data) {
                        $("#alert").html(data);
                    }
                });
            });
        })
    </script>
@endsection