<script>
    var type = '<?php if(isset($_GET['type'])) { echo $_GET['type']; } else {echo "none"; } ?>';
    
    if(type == 'album')
    {
        var album = '<?php $album = $database->select("SELECT * FROM album WHERE album_id='{$_GET['article_id']}'"); echo json_encode($album); ?>';
        album = JSON.parse(album);
        
        var tracks = '<?php $tracks = $database->select("SELECT * FROM track WHERE album_id='{$album[1]->album_id}'"); echo json_encode($tracks); ?>';
        tracks = JSON.parse(tracks);
        
        var container = document.querySelector('#article-div');
        container.innerHTML += '<div class="row" style="margin:0";>'+
                                     '<div style="position:absolute;background-image:url(covers/'+album[1].cover+');filter:blur(25px);z-index:-2;height:415px;%;width:100%;"></div>'+
                                     '<div class="col-md-3" style="padding:0;margin-top:40px;margin-left:40px;">'+
                                        '<div style="border:solid 10px black;">'+
                                            '<div style="height:350px;width:100%;background-image:url(covers/'+album[1].cover+');background-size:cover;background-position:center;">'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="col-md-1"></div>'+
                                    '<div class="col-md-3" style="margin-top:40px;">'+ //9
                                        '<h2 style="color:#dc3545;background-color:black;">'+album[1].name+'</h2>'+
                                        '<h4 style="color:#0070c9;background-color:black;"><a href="profil.php?pseudo='+album[1].owner+'">'+album[1].owner+'</a></h4>'+
                                        '<br/>'+
                                        '<h6 class="text-muted"><strong>'+album[1].tag+'</strong></h6>'+
                                        '<h6 class="text-muted"><strong>'+album[1].date+'</strong></h6>'+
                                        '<div class="row">'+
                                            '<div class="col-md-3" style="margin-top:100px;">'+
                                                '<button class="btn btn-info" id="album-play" style="display:inline-flex;">Play<i class="material-icons">play_arrow</i></button>'+
                                            '</div>'+
                                            '<div class="col-md-3"></div>'+
                                            '<div class="col-md-3" style="margin-top:100px;">'+
                                                '<button class="btn btn-danger" data-toggle="modal" data-target="#payment" style="display:inline-flex;">Support me &nbsp;<i class="material-icons">favorite</i></button>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="row" style="margin:0;">'+
                                    '<div class="col-md-2" style="margin-top:100px;text-align:center;">'+
                                                '<audio id="player" ontimeupdate="update(this)"></audio>'+
                                                '<div>'+
                                                    '<button id="prev" style="background-color:white;border:0px;">'+
                                                        '<i class="material-icons" style="font-size:30px;">fast_rewind</i>'+
                                                    '</button>'+
                                                    '<button id="play" style="background-color:white;border:0px;">'+
                                                        '<i class="material-icons" style="font-size:30px;">play_arrow</i>'+
                                                    '</button>'+
                                                    '<button id="next" style="background-color:white;border:0px;">'+
                                                        '<i class="material-icons" style="font-size:30px;">fast_forward</i>'+
                                                    '</button>'+
                                                '</div>'+
                                    '</div>'+
                                    '<div class="col-md-10" style="margin-top:112px;">'+
                                                    '<div id="progress-container" class="progress" onclick="setprogess(this, event)" style="height:5px;">'+
                                                        '<div id="progressbar" class="progress-bar bg-info" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>'+
                                                    '</div>'+
                                    '</div>'
                                '</div>';
        
        var tracks_div = document.createElement('div');
        tracks_div.style = "margin-top:50px;margin-left:20px;color:#696969;";
        container.appendChild(tracks_div);
        
        for(var i = 1; i <= tracks[0]; i++)
        {
            var index;
            if(i < 10) { index = '0'+i; }
            else { index = i; }
            
            tracks_div.innerHTML += '<div class="row" style="margin:0;">'+
                                        '<div class="col-md-3" style="margin-left:10px;">'+
                                            '<button style="background-color:white;border:0px;" onclick="settrack('+i+')">'+
                                                '<h5 id="track-line'+i+'"><span style="color:#868e96">'+index+'.</span> '+tracks[i].name+'</h5>'+
                                            '</button></div>'+
                                    '</div>'+
                                    '<hr style="border-width:1px;width:100%;background-color:#d3d3d3;">';
        }
        
        tracks_div.innerHTML += '<br/>';
        
        var current = 0;
        document.querySelector('#album-play').addEventListener('click', function() {
            
            current = 1;
            player.src='albums/'+album[1].album_id+'-read/01 '+tracks[current].name+'.'+tracks[current].ext;
            player.play();
            
            var play = document.querySelector('#play');
            play.innerHTML= '<i class="material-icons" style="font-size:30px;">pause</i>';
            
            for(i = 1; i <= tracks[0]; i++)
            {
                var id = '#track-line'+i;
                document.querySelector(id).style= "";
            }
            document.querySelector('#track-line1').style= "color:#51acc7";
        });
        
        
        function update(player)
        {
            var duration = player.duration;
            var percent = (player.currentTime / duration) * 100;
            
            document.querySelector('#progressbar').style="width:"+percent+"%";
            
            if(current < tracks[0])
            {
                if(player.ended)
                {
                    current++;
                    
                    var str_current;
                    if(current < 10) { str_current = '0'+current; }
                    else { str_current = current; }
                    
                    player.src='albums/'+album[1].album_id+'-read/'+str_current+' '+tracks[current].name+'.'+tracks[current].ext;
                    player.play();
                    
                    for(i = 1; i <= tracks[0]; i++)
                    {
                        var id = '#track-line'+i;
                        document.querySelector(id).style= "";
                    }
                    var id = '#track-line'+current;
                    document.querySelector(id).style= "color:#51acc7";
                }
            }
        }
        
        function settrack(i)
        {
            current = i;
            var str_current;
            if(current < 10) { str_current = '0'+current; }
            else { str_current = current; }
            
            var player = document.querySelector('#player');
            player.src='albums/'+album[1].album_id+'-read/'+str_current+' '+tracks[current].name+'.'+tracks[current].ext;
            player.play();
            
            var play = document.querySelector('#play');
            play.innerHTML= '<i class="material-icons" style="font-size:30px;">pause</i>';
            
            for(i = 1; i <= tracks[0]; i++)
            {
                var id = '#track-line'+i;
                document.querySelector(id).style= "";
            }
            var id = '#track-line'+current;
            document.querySelector(id).style= "color:#51acc7";
        }
        
        document.querySelector('#prev').addEventListener('click', function() {
            
            if(current == 1 || current == 0) { return; }
            current--;
            
            var str_current;
            if(current < 10) { str_current = '0'+current; }
            else { str_current = current; }
            
            var player = document.querySelector('#player');
            player.src='albums/'+album[1].album_id+'-read/'+str_current+' '+tracks[current].name+'.'+tracks[current].ext;
            player.play();
            
            for(i = 1; i <= tracks[0]; i++)
            {
                var id = '#track-line'+i;
                document.querySelector(id).style= "";
            }
            var id = '#track-line'+current;
            document.querySelector(id).style= "color:#51acc7";
        });
        
        document.querySelector('#next').addEventListener('click', function() {
            
            if(current == tracks[0] || current == 0) { return; }
            current++;
            
            var str_current;
            if(current < 10) { str_current = '0'+current; }
            else { str_current = current; }
            
            var player = document.querySelector('#player');
            player.src='albums/'+album[1].album_id+'-read/'+str_current+' '+tracks[current].name+'.'+tracks[current].ext;
            player.play();
            
            for(i = 1; i <= tracks[0]; i++)
            {
                var id = '#track-line'+i;
                document.querySelector(id).style= "";
            }
            var id = '#track-line'+current;
            document.querySelector(id).style= "color:#51acc7";
        });
        
        document.querySelector('#play').addEventListener('click', function() {
            
            if(current == 0) { return; }
            var player = document.querySelector('#player');
            var play = document.querySelector('#play');
            if(player.paused) 
            { 
                player.play(); 
                play.innerHTML= '<i class="material-icons" style="font-size:30px;">pause</i>'; 
            }
            else 
            { 
                player.pause();
                play.innerHTML = '<i class="material-icons" style="font-size:30px;">play_arrow</i>';
            }
            
        });
        
        
        function getMousePosition(event) 
        {
            return { x: event.pageX, y: event.pageY };
        }
        
        function getPosition(element)
        {
            var top = 0, left = 0;
    
            do
            {
                top  += element.offsetTop;
                left += element.offsetLeft;
            } while (element = element.offsetParent);
            
            return { x: left, y: top };
        }
        
        function setprogess(progress, event)
        {
            if(current == 0) { return; }
            var progress_div_position = getPosition(progress);
            var mouse_position = getMousePosition(event);
            var player = document.querySelector('#player');
            var progress_width = document.querySelector('#progress-container').offsetWidth;
            
            var x = mouse_position.x - progress_div_position.x;
            var percent = Math.ceil((x / progress_width) * 100);
            
            var duration = player.duration;
            player.currentTime = (duration * percent) / 100;
            
            document.querySelector('#progressbar').style="width:"+percent+"%";
        }
        
        document.querySelector('#price-info').textContent += ' '+album[1].price+' €.';
    
        document.querySelector('#owner').value = album[1].owner;
        document.querySelector('#name').value = album[1].name;
        document.querySelector('#id').value = album[1].album_id;
        document.querySelector('#type').value = 'album';
        document.querySelector('#price').value = album[1].price;
    
    }
    
</script>