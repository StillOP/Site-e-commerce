<script>
    var type = '<?php if(isset($_GET['type'])) { echo $_GET['type']; } else {echo "none"; } ?>';
    
    if(type == "book")
    {
        var book = '<?php $book = $database->select("SELECT * FROM book WHERE book_id='{$_GET['article_id']}'"); echo json_encode($book); ?>';
        book = JSON.parse(book);
        
        var container = document.querySelector('#article-div');
        container.innerHTML += '<div class="row" style="margin:0px;margin-top:50px;margin-left:10px;">'+
                                    '<div class="col-md-2" style="padding:0;">'+
                                        '<div style="padding:5px;border:solid 1px;">'+
                                            '<div style="height:350px;width:100%;background-image:url(covers/'+book[1].cover+');background-size:cover;background-position:center;box-shadow: 1px;">'+
                                        '</div>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="col-md-3" style="margin-left:40px;">'+
                                        '<h2 style="color:#dc3545">'+book[1].title+'</h2>'+
                                        '<h4 style="color:#0070c9"><a href="profil.php?pseudo='+book[1].owner+'">'+book[1].owner+'</a></h4>'+
                                        '<br/>'+
                                        '<h6 class="text-muted">'+book[1].tag+'</h6>'+
                                        '<h6 class="text-muted">'+book[1].date+'</h6>'+
                                        '<div style="margin-top:100px;">'+
                                            '<a href="samples/'+book[1].sample+'" target="_blank"><button class="btn btn-info" id="get-sample" style="display:inline-flex;">Get sample &nbsp;<i class="material-icons">file_download</i></button></a>'+
                                        '</div>'+
                                        '<div style="margin-top:10px;">'+
                                                '<button class="btn btn-danger" data-toggle="modal" data-target="#payment" style="display:inline-flex;">Support me &nbsp;<i class="material-icons">favorite</i></button>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="col-md-6">'+
                                        '<h4>About</h4>'+
                                        '<hr style="border-width:1px;width:100%;background-color:#d3d3d3;">'+
                                        '<div style="overflow:auto;">'+
                                            '<div style="height:300px;padding:5px;">'+
                                                book[1].summary+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div><br/><br/><br/><br/>';
        
        document.querySelector('#price-info').textContent += ' '+book[1].price+' €.';
        
        
        document.querySelector('#owner').value = book[1].owner;
        document.querySelector('#name').value = book[1].title;
        document.querySelector('#id').value = book[1].book_id;
        document.querySelector('#type').value = 'book';
        document.querySelector('#price').value = book[1].price;
    }
</script>