<script>
    var type = '<?php if(isset($_GET['type'])) { echo $_GET['type']; } else {echo "none"; } ?>';
    
    if(type == "goodies")
    {
        var goodies = '<?php $goodies = $database->select("SELECT * FROM goodies WHERE goodies_id='{$_GET['article_id']}'"); echo json_encode($goodies); ?>';
        goodies = goodies.replace(/(\r\n|\n|\r|\x0B)/gm,"<br/>");
        goodies = JSON.parse(goodies);
        
        var images = goodies[1].images.split(',');
        var images_number = images.length;
        
        var container = document.querySelector('#article-div');    
        var row = document.createElement('div');
        row.className = "row";
        row.style = "margin:0;padding-top:50px;";
        container.appendChild(row);
        
        var carousel_container = document.createElement('div');
        carousel_container.className = "col-md-4";
        carousel_container.style="padding:10px;margin-left:10px;"
        row.appendChild(carousel_container);
        
        var carousel = document.createElement('div');
        carousel.className = "carousel slide";
        carousel.setAttribute("data-ride", "carousel");
        carousel.id = "goodies-carousel";
        carousel_container.appendChild(carousel);
        
        var carousel_ol = document.createElement('ol');
        carousel_ol.className = "carousel-indicators";
        carousel.appendChild(carousel_ol);
        
        for(var i = 0; i < images_number; i++)
        {
            if(i == 0) { carousel_ol.innerHTML += '<li data-target="#goodies-carousel" data-slide-to="'+i+'" class="active"></li>'; }
            else
            {
                carousel_ol.innerHTML += '<li data-target="#goodies-carousel" data-slide-to="'+i+'"></li>';
            }
        }
        
        var carousel_inner = document.createElement('div');
        carousel_inner.className = "carousel-inner";
        carousel.appendChild(carousel_inner);
        
        for(var i = 0; i < images_number; i++)
        {
            if(i == 0)
            {
                carousel_inner.innerHTML += '<div class="carousel-item active" style="height:400px;background-image:url(covers/'+images[0]+');background-size:cover;background-position:center;border:solid 20px #212529;">'+
                                                
                                            '</div>';
            }
            else
            {
                carousel_inner.innerHTML += '<div class="carousel-item" style="height:400px;background-image:url(covers/'+images[i]+');background-size:cover;background-position:center;border:solid 20px #212529;">'+
                                            '</div>';
            }
        }
        
        carousel.innerHTML += '<a class="carousel-control-prev" href="#goodies-carousel" role="button" data-slide="prev">'+
                                    '<span class="carousel-control-prev-icon" aria-hidden="true"></span>'+
                                    '<span class="sr-only">Previous</span>'+
                                '</a>'+
                                '<a class="carousel-control-next" href="#goodies-carousel" role="button" data-slide="next">'+
                                    '<span class="carousel-control-next-icon" aria-hidden="true"></span>'+
                                    '<span class="sr-only">Next</span>'+
                                '</a>';
        
        row.innerHTML += '<div class="col-md-2">'+
                            '<h2 style="color:#dc3545">'+goodies[1].title+'</h2><br/>'+
                            '<h4 style="color:#0070c9"><a href="profil.php?pseudo='+goodies[1].owner+'">'+goodies[1].owner+'</a></h4><br/>'+
                            '<h6 class="text-muted">'+goodies[1].date+'</h6><br/>'+
                            '<button class="btn btn-danger" data-toggle="modal" data-target="#payment" style="display:inline-flex;">Support me &nbsp;<i class="material-icons">favorite</i></button>'+
                         '</div>';
        
        row.innerHTML += '<div class="col-md-5">'+
                            '<h4>About</h4>'+
                            '<hr style="border-width:1px;width:100%;background-color:#d3d3d3;">'+
                            '<div style="overflow:auto;">'+
                                '<div style="height:300px;padding:5px;">'+
                                    goodies[1].description+
                                '</div>'+
                            '</div>'+
                        '</div>';
        
        document.querySelector('#price-info').textContent += ' '+goodies[1].price+' €.';
        
        
        document.querySelector('#owner').value = goodies[1].owner;
        document.querySelector('#name').value = goodies[1].title;
        document.querySelector('#id').value = goodies[1].goodies_id;
        document.querySelector('#type').value = 'goodies';
        document.querySelector('#price').value = goodies[1].price;
        
        document.querySelector('#additional-input').innerHTML += '<div class="form-group">'+
                                                                    '<label for="address">Shipping address</label>'+
                                                                    '<textarea class="form-control" name="address" id="address" rows="10" aria-describedby="addressHelpBlock"></textarea>'+
                                                                    '<small id="addressHelpBlock" class="form-text text-muted">Full name, number, street, city, country, tel(optinal). No accent or specials chars allowed!</small>'+
                                                                 '</div>';
    }
    
</script>