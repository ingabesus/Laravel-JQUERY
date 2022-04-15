@extends('layouts.app')

@section('content')

<style>
th div {
  cursor: pointer;
}
</style> 

<div class="container">
  <div class="row">
    <div class="col-md-6">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTypeModal">
          Create type
        </button>
        <input id="hidden-sort" type="hidden" value="id" />
        <input id="hidden-direction" type="hidden" value="asc" />
        <!-- <button id="remove-table">Remove table</button>
        <div id="alert" class="alert alert-success d-none">
        </div>   -->

        
          <div class="searchAjaxForm" >
            <input id="searchValue" class="form-control"    minlength="3" type="text">
            <button type="button" id="submitSearch">Find</button>
          </div> 
       
      
          <a class="btn btn-primary " href="{{ route('article.index') }}">
            Article list
          </a>
        
    </div>
  </div>
    
    <table id="types-table" class="table table-striped">
        <thead> 
            <tr>
                <th><div class="types-sort" data-sort="id" data-direction="asc">Id</div></th>
                <th><div class="types-sort" data-sort="title" data-direction="asc">Title</div></th>
                <th><div class="types-sort" data-sort="description" data-direction="asc">Description</div></th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($types as $type) 
            <tr class="type{{$type->id}}">
                <td class="col-type-id">{{$type->id}}</td>
                <td class="col-type-title">{{$type->title}}</td>
                <td class="col-type-description">{{$type->description}}</td>
                <td style="width: 250px">            
                    <button class="btn btn-danger delete-type" type="submit" data-typeid="{{$type->id}}">DELETE</button>
                    <button type="button" class="btn btn-primary show-type" data-bs-toggle="modal" data-bs-target="#showTypeModal" data-typeid="{{$type->id}}">Show</button>
                    <button type="button" class="btn btn-secondary edit-type" data-bs-toggle="modal" data-bs-target="#editTypeModal" data-typeid="{{$type->id}}">Edit</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <table class="template">
        <tr>
          <td class="col-type-id"></td>
          <td class="col-type-title"></td>
          <td class="col-type-description"></td>
          <td style="width: 250px">
            <button class="btn btn-danger delete-type" type="submit" data-typeid="">DELETE</button>
            <button type="button" class="btn btn-primary show-type" data-bs-toggle="modal" data-bs-target="#showTypeModal" data-typeid="">Show</button>
            <button type="button" class="btn btn-secondary edit-type" data-bs-toggle="modal" data-bs-target="#editTypeModal" data-typeid="">Edit</button>
          </td>
        </tr>  
    </table>    
</div>
<script>
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function() {
        $("#remove-table").click(function(){
          $('.type55').remove();
        });
        function createRow(typeId, typeTitle, typeDescription ) {
                    let html
                    html += "<tr class='type"+typeId+"'>";
                    html += "<td>"+typeId+"</td>";    
                    html += "<td>"+typeTitle+"</td>";  
                    html += "<td>"+typeDescription+"</td>";  
                    html += "<td>";
                    html +=  "<button class='btn btn-danger delete-type' type='submit' data-typeid='"+typeId+"'>DELETE</button>"; 
                    html +=  "</td>";
                    html += "</tr>";
                   return html 
        }
        function createRowFromHtml(typeId, typeTitle,  typeDescription) {
          $(".template tr").addClass("type"+typeId);
          $(".template .delete-type").attr('data-typeid', typeId );
          $(".template .show-type").attr('data-typeid', typeId );
          $(".template .edit-type").attr('data-typeid', typeId );
          $(".template .col-type-id").html(typeId );
          $(".template .col-type-title").html(typeTitle );
          $(".template .col-type-description").html(typeDescription );
    
          return $(".template tbody").html();
        }
    
        console.log("Jquery veikia");
        $("#submit-ajax-form").click(function() {
            let type_title;
            let type_description;
            let sort;
            let direction;

            type_title = $('#type_title').val();
            type_description = $('#type_description').val();
            sort = $('#hidden-sort').val();
            direction = $('#hidden-direction').val();
           
            $.ajax({
                type: 'POST',
                url: '{{route("type.store")}}' ,
                data: {type_title: type_title,  type_description: type_description, sort:sort, direction:direction  },
                success: function(data) {
                    
                    console.log(data);
                    let html;
                   
                    if($.isEmptyObject(data.errorMessage)) {
                      $("#types-table tbody").html('');
                      $.each(data.types, function(key, type) {
                          let html;
                          html = createRowFromHtml(type.id, type.title, type.description);
                          $("#types-table tbody").append(html);
                     });

                          $("#createTypeModal").hide();
                          $('body').removeClass('modal-open');
                          $('.modal-backdrop').remove();
                          $('body').css({overflow:'auto'});
                          $("#alert").removeClass("d-none");
                          $("#alert").html(data.successMessage +" " + data.typeTitle);
                          $('#type_title').val('');
                          $('#type_description').val('');
                          } else {
                            console.log(data.errorMessage);
                            console.log(data.errors);
                            $('.create-input').removeClass('is-invalid');
                            $('.invalid-feedback').html('');
                            $.each(data.errors, function(key, error) {
                              console.log(key);
                              $('#'+key).addClass('is-invalid');
                              $('.input_'+key).html("<strong>"+error+"</strong>");
                            });
                          }    
                }
            });
        }); 
        
        $(document).on('click', '.delete-type', function() {
            let typeid;
            typeid = $(this).attr('data-typeid');
            console.log(typeid);
            $.ajax({
                type: 'POST',
                url: '/types/destroy/' + typeid  ,
                success: function(data) {
                   console.log(data);
                
                    if($.isEmptyObject(data.errorMessage)) {
                        $('#alert').removeClass('alert-danger');
                        $('#alert').addClass('alert-success');
                        $("#alert").removeClass("d-none");
                        $('.type'+typeid).remove();
                        $("#alert").html(data.successMessage);                    
                        
                    } else {
                        $('#alert').removeClass('alert-success');
                        $('#alert').addClass('alert-danger');
                        $("#alert").removeClass("d-none");
                        $("#alert").html(data.errorMessage); 
                    }              
                  }
              });
          });
    

        $(document).on('click', '.show-type', function() {
            let typeid;
            typeid = $(this).attr('data-typeid');
            console.log(typeid);
            $.ajax({
                type: 'GET',
                url: '/types/show/' + typeid  ,
                success: function(data) {
                   $('.show-type-id').html(data.typeId);                   
                   $('.show-type-title').html(data.typeTitle);                   
                   $('.show-type-description').html(data.typeDescription);                                  
                }
            });
        });
        $(document).on('click', '.edit-type', function() {
          let typeid;
            typeid = $(this).attr('data-typeid');
            console.log(typeid);

            $.ajax({
                type: 'GET',
                url: '/types/show/' + typeid  ,
                success: function(data) {
                    $('#edit_type_id').val(data.typeId);                   
                    $('#edit_type_title').val(data.typeTitle);                   
                    $('#edit_type_description').val(data.typeDescription);                                  
                }
            });
        });
        $(document).on('click', '#update-type', function() {
          let typeid;
          let type_title;
          let type_description;  
            
            typeid = $('#edit_type_id').val();
            type_title = $('#edit_type_title').val();
            type_description = $('#edit_type_description').val();

          $.ajax({
                type: 'POST',
                url: '/types/update/' + typeid  ,
                data: {type_title: type_title, type_description: type_description  },
                success: function(data) {
                  
                  $(".type"+typeid+ " " + ".col-type-title").html(data.typeTitle)
                  $(".type"+typeid+ " " + ".col-type-description").html(data.typeDescription)
                  
                    $("#alert").removeClass("d-none");
                    $("#alert").html(data.successMessage);
                    $("#editTypeModal").hide();
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    $('body').css({overflow:'auto'});
                }
            });
        })

        $('#submitSearch').click(function() {
          let searchValue = $('#searchValue').val();
          console.log(searchValue);
          $.ajax({
                type: 'GET',
                url: '{{route("type.search")}}'  ,
                data: {searchValue: searchValue},
                success: function(data) {
                  if($.isEmptyObject(data.errorMessage)) {
                    $("#types-table").show();
                    $("#alert").addClass("d-none");
                    $("#types-table tbody").html('');
                     $.each(data.types, function(key, type) {
                          let html;
                          html = createRowFromHtml(type.id, type.title, type.description);
                     
                          $("#types-table tbody").append(html);
                     });                             
                  } else {
                        $("#types-table").hide();
                        $('#alert').removeClass('alert-success');
                        $('#alert').addClass('alert-danger');
                        $("#alert").removeClass("d-none");
                        $("#alert").html(data.errorMessage); 
                  }                            
                }
            });
        });

        $('.types-sort').click(function() {
          let sort;
          let direction;
          sort = $(this).attr('data-sort');
          direction = $(this).attr('data-direction');
          $("#hidden-sort").val(sort);
          $("#hidden-direction").val(direction);
          if(direction == 'asc') {
            $(this).attr('data-direction', 'desc');
          } else {
            $(this).attr('data-direction', 'asc');
          }
          $.ajax({
                type: 'GET',
                url: '{{route("type.indexAjax")}}'  ,
                data: {sort: sort, direction: direction },
                success: function(data) {
                  console.log(data.types);
                  
                    $("#types-table tbody").html('');
                     $.each(data.types, function(key, type) {
                     
                          let html;
                          html = createRowFromHtml(type.id, type.title, type.description);
                         
                          $("#types-table tbody").append(html);
                     });
                }
            });
        });
    })
</script>


@endsection