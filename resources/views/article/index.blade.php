@extends('layouts.app')

@section('content')
<style>
th div {
  cursor: pointer;
}
</style> 

<div class="container">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createArticleModal">
      Create Article
    </button>
    <input id="hidden-sort" type="hidden" value="id" />
    <input id="hidden-direction" type="hidden" value="asc" />
    <button type="button" id="delete-selected-articles" class="btn btn-danger">
      Delete selected
    </button>
    <div id="alert" class="alert alert-success d-none">
    </div>    
    <div class="searchAjaxForm">
      <input id="searchValue" type="text">
      <button type="button" id="submitSearch">Find</button>
      <span class="search-feedback"></span>
    </div> 
    <div class="filter">
        <select name="select_type_id" id="select_type_id">
            <option value="all">Select type</option>
                @foreach ($types as $type)
                   <option value="{{$type->id}}" >{{$type->title}}</option>
                @endforeach
        </select>
    </div>
    <table id="articles-table" class="table table-striped">
        <thead>
          <tr>
              <th><div class="articles-sort" data-sort="id" data-direction="asc">Id</div></th>
              <th style="width: 20px;">Select all<input type="checkbox" id="select_all_articles"/></th>
              <th><div class="articles-sort" data-sort="title" data-direction="asc">Title</div></th>
              <th><div class="articles-sort" data-sort="description" data-direction="asc">Description</div></th>
              <th><div class="articles-sort" data-sort="articleType.title" data-direction="asc">Type</div></th>
              <th>Action</th>
           </tr>
          </thead>
          <tbody id="article-table-body">
            @foreach ($articles as $article) 
            <tr class="article{{$article->id}}">
                <td class="col-article-id">{{$article->id}}</td>
                <td class="col-article-select"><input type="checkbox" class="select-article" id="article_select_{{$article->id}}" value="{{$article->id}}"/></td>
                <td class="col-article-title">{{$article->title}}</td>
                <td class="col-article-description">{{$article->description}}</td>
                <td class="col-article-type-id">{{$article->articleType->title}}</td>
                <td  style="width: 250px">            
                    <button class="btn btn-danger delete-article" type="submit" data-articleid="{{$article->id}}">DELETE</button>
                    <button type="button" class="btn btn-primary show-article" data-bs-toggle="modal" data-bs-target="#showArticleModal" data-articleid="{{$article->id}}">Show</button>
                    <button type="button" class="btn btn-secondary edit-article" data-bs-toggle="modal" data-bs-target="#editArticleModal" data-articleid="{{$article->id}}">Edit</button>
                </td>
            </tr>
          
        @endforeach
      </tbody>
    </table>
    <table class="template d-none">
        <tr>
          <td class="col-article-id"></td>
          <td class="col-article-select"></td>
          <td class="col-article-title"></td>
          <td class="col-article-description"></td>
          <td class="col-article-type-id"></td>
          <td>
            <button class="btn btn-danger delete-article d-none" type="submit" data-articleid="">DELETE</button>
            <button type="button" class="btn btn-primary show-article d-none" data-bs-toggle="modal" data-bs-target="#showArticleModal" data-articleid="">Show</button>
            <button type="button" class="btn btn-secondary edit-article d-none" data-bs-toggle="modal" data-bs-target="#editArticleModal" data-articleid="">Edit</button>
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
          $('.article55').remove();
        });
       
        function createRowFromHtml(articleId, articleTitle,  articleDescription, articleTypetitle ) {
          $(".template tr").addClass("article"+articleId);
          $(".template .delete-article").attr('data-articleid', articleId );
          $(".template .show-article").attr('data-articleid', articleId );
          $(".template .edit-article").attr('data-articleid', articleId );
          $(".template .col-article-id").html(articleId );
          $(".template .col-article-select").html("<input type='checkbox' class='select-article' id='article_select_"+articleId+"' value="+articleId+"/>");
          $(".template .col-article-title").html(articleTitle );
          $(".template .col-article-description").html(articleDescription );
          $(".template .col-article-type-id").html(articleTypetitle );
    
          return $(".template tbody").html();
        }
    
        console.log("Jquery veikia");
        $("#submit-ajax-form-article").click(function() {
            let article_title;
            let article_description;
            let article_type_id;
            let sort;
            let direction;

            article_title = $('#article_title').val();
            article_description = $('#article_description').val();
            article_type_id = $('#article_type_id').val();
            sort = $('#hidden-sort').val();
            direction = $('#hidden-direction').val();

            $.ajax({
                type: 'POST',
                url: '{{route("article.store")}}' ,
                data: {article_title: article_title,  article_description: article_description, article_type_id: article_type_id, sort:sort, direction:direction  },
                success: function(data) {
                    
                    console.log(data);
                    let html;

                    if($.isEmptyObject(data.errorMessage)) {
                      $("#articles-table tbody").html('');
                      $.each(data.articles, function(key, article) {
                          let html;
                          html = createRowFromHtml(article.id, article.title, article.description, article.article_type.title);
                          $("#articles-table tbody").append(html);
                     });

                          $("#createArticleModal").hide();
                          $('body').removeClass('modal-open');
                          $('.modal-backdrop').remove();
                          $('body').css({overflow:'auto'});
                          $("#alert").removeClass("d-none");
                          $("#alert").html(data.successMessage +" " + data.articleTitle);
                          $('#article_title').val('');
                          $('#article_description').val('');
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
        
        $(document).on('click', '.delete-article', function() {
            let articleid;
            articleid = $(this).attr('data-articleid');
            console.log(articleid);
            $.ajax({
                type: 'POST',
                url: '/articles/destroy/' + articleid  ,
                success: function(data) {
                   console.log(data);
                   $('.article'+articleid).remove();
                    $("#alert").removeClass("d-none");
                    $("#alert").html(data.successMessage);                    
                }
            });
        });
        $(document).on('click', '.show-article', function() {
            let articleid;
            articleid = $(this).attr('data-articleid');
            console.log(articleid);
            $.ajax({
                type: 'GET',
                url: '/articles/show/' + articleid  ,
                success: function(data) {
                   $('.show-article-id').html(data.articleId);                   
                   $('.show-article-title').html(data.articleTitle);                   
                   $('.show-article-description').html(data.articleDescription); 
                   $('.show-article-type-id').html(data.articleTypetitle);                                 
                }
            });
        });
        $(document).on('click', '.edit-article', function() {
          let articleid;
            articleid = $(this).attr('data-articleid');
            console.log(articleid);

            $.ajax({
                type: 'GET',
                url: '/articles/show/' + articleid  ,
                success: function(data) {
                  $('#edit_article_id').val(data.articleId);                   
                  $('#edit_article_title').val(data.articleTitle);                   
                  $('#edit_article_description').val(data.articleDescription);
                  $('#edit_article_type_id').val(data.articleTypeid);                                  
                }
            });
        });
        $(document).on('click', '#update-article', function() {
          let articleid;
          let article_title;
          let article_description; 
          let article_type_id; 
          
            articleid = $('#edit_article_id').val();
            article_title = $('#edit_article_title').val();
            article_description = $('#edit_article_description').val();
            article_type_id = $('#edit_article_type_id').val();

          $.ajax({
                type: 'POST',
                url: '/articles/update/' + articleid  ,
                data: {article_title: article_title, article_description: article_description,  article_type_id: article_type_id },
                success: function(data) {
                  $(".article"+articleid+ " " + ".col-article-title").html(data.articleTitle)
                  $(".article"+articleid+ " " + ".col-article-description").html(data.articleDescription)
                  $(".article"+articleid+ " " + ".col-article-type-id").html(data.articleTypetitle)
                  $("#alert").removeClass("d-none");
                  $("#alert").html(data.successMessage);
                  $("#editArticleModal").hide();
                  $('body').removeClass('modal-open');
                  $('.modal-backdrop').remove();
                  $('body').css({overflow:'auto'});
                }
            });
        })

        $('.articles-sort').click(function() {
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
                url: '{{route("article.indexAjax")}}'  ,
                data: {sort: sort, direction: direction },
                success: function(data) {
                  console.log(data.articles);
                    $("#articles-table tbody").html('');
                    $.each(data.articles, function(key, article) {
             
                          let html;
                          html = createRowFromHtml(article.id, article.title, article.description, article.article_type.title);

                          $("#articles-table tbody").append(html);
                     });
                }
            });
        });
        $('#select_all_articles').on('click', function () {
  
          let status = $(this).prop('checked'); 
            $(".select-article").each( function() {  
            $(this).prop("checked",status);
            });
        });
        $('#delete-selected-articles').on('click', function () {
            $('#article-table-body input[type=checkbox]:checked').each(function () {
              let articleid = this.value;
                $.ajax({
                  type: 'POST',
                  url: '/articles/destroy/' + articleid,
                  success: function(data) {

                     if($.isEmptyObject(data.errorMessage)) {
                      $('#alert').removeClass('alert-danger');
                      $('#alert').addClass('alert-success');
                      $("#alert").removeClass("d-none");
                      $('.article'+articleid).remove();
                      $("#alert").html(data.successMessage);
          
                  } else {
                      $('#alert').removeClass('alert-success');
                      $('#alert').addClass('alert-danger');
                      $("#alert").removeClass("d-none");
                      $("#alert").html(data.errorMessage);
                  } 
                  }
              });
              $("#select_all_articles").prop("checked",false);
              $(".select-article").each( function() {  
                $(this).prop("checked",false);
                });
              setTimeout(() => {
                $('#alert').addClass('d-none');
              }, 2000);        
            });
          });

        $(document).on('input', '#searchValue', function() {
         
            let searchValue = $('#searchValue').val();
            let searchFieldCount= searchValue.length;
              if(searchFieldCount == 0) {
                  console.log("Field is empty");
                  $(".search-feedback").css('display', 'block');
                  $(".search-feedback").html("Field is empty");
                } else if (searchFieldCount != 0 && searchFieldCount< 3 ) {
                  console.log("Min 3");
                  $(".search-feedback").css('display', 'block');
                  $(".search-feedback").html("Min 3");
                } else {
                  $(".search-feedback").css('display', 'none');
                console.log(searchFieldCount);
                console.log(searchValue);
              $.ajax({
                    type: 'GET',
                    url: '{{route("article.search")}}'  ,
                    data: {searchValue: searchValue},
                    success: function(data) {
                      if($.isEmptyObject(data.errorMessage)) {
                        $("#articles-table").show();
                        $("#alert").addClass("d-none");
                        $("#articles-table tbody").html('');
                        $.each(data.articles, function(key, article) {
                         
                              let html;
                              html = createRowFromHtml(article.id, article.title,  article.description, article.type_title);
                             
                              $("#articles-table tbody").append(html);
                        });                             
                      } else {
                            $("#articles-table").hide();
                            $('#alert').removeClass('alert-success');
                            $('#alert').addClass('alert-danger');
                            $("#alert").removeClass("d-none");
                            $("#alert").html(data.errorMessage); 
                      }                            
                    }
                });
              }
            });


        $(document).on('input', '#select_type_id', function() {
         
         let select_type_id = $('#select_type_id').val();
         
             console.log(select_type_id);
           $.ajax({
                 type: 'GET',
                 url: '{{route("article.filter")}}'  ,
                 data: {select_type_id: select_type_id},
                 success: function(data) {
                   if($.isEmptyObject(data.errorMessage)) {
                     $("#articles-table").show();
                     $("#alert").addClass("d-none");
                     $("#articles-table tbody").html('');
                     $.each(data.articles, function(key, article) {
                      
                           let html;
                           html = createRowFromHtml(article.id, article.title,  article.description, article.type_title);
                          
                           $("#articles-table tbody").append(html);
                     });                             
                   } else {
                         $("#articles-table").hide();
                         $('#alert').removeClass('alert-success');
                         $('#alert').addClass('alert-danger');
                         $("#alert").removeClass("d-none");
                         $("#alert").html(data.errorMessage); 
                   }                            
                 }
             });
           
         });
    })
</script>

@endsection