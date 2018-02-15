<?php

    $form->divheader("tab-pane fade show active", 'id="album" role="tabpanel" aria-labelledby="album-tab"');

        $form->header($_SERVER['PHP_SELF'], "albumregister", "multipart/form-data", "albumregister");
            $form->divheader("form-group");
                $form->input("title", "Title", "text", "title", "Title");
            $form->divfooter();
             $form->divheader("form-group");
                echo "<div class=\"custom-file\">
                        <input type=\"file\" class=\"custom-file-input\" id=\"cover\" name=\"cover\" onchange=\"filename(this.value, 'coverlabel')\" aria-describedby=\"FormatHelp\"/>
                        <label class=\"custom-file-label\" for=\"cover\" id=\"coverlabel\">Add a cover</label>
                        <small id=\"FormatHelp\" class=\"form-text text-muted\">
                            jpeg, png / 200Ko
                        </small>
                    </div>";
            $form->divfooter();
            echo "<div class=\"form-group\">
                    <label for=\"tag\">Tag</label>
                    <select class=\"custom-select\" id=\"tag\" name=\"tag\">
                        <option value=\"Pop\">Pop</option><option value=\"Rock\">Rock</option><option value=\"Rap\">Rap</option><option value=\"Electro\">Electro</option><option value=\"RnB\">RnB</option>
                        <option value=\"J-Pop\">J-Pop</option><option value=\"K-Pop\">K-Pop</option><option value=\"Trap\">Trap</option><option value=\"Instru\">Instru</option><option value=\"Opera\">Opera</option>
                        <option value=\"Chorus\">Chorus</option><option value=\"Techno\">Techno</option><option value=\"Jazz\">Jazz</option><option value=\"Classic\">Classic</option><option value=\"Afro\">Afro</option>
                    </select>
                </div>";
            echo "<div class=\"form-goup\" style=\"width:10%;\">
                        <label for=\"albumprice\">Price</label>
                        <div class=\"input-group\">
                            <input type=\"text\" class=\"form-control\" id=\"albumprice\" name=\"albumprice\" pattern=\"[0-9]+([\.][0-9]{0,2})?\"/>
                            <div class=\"input-group-prepend\">
                                <label class=\"input-group-text\" for=\"albumprice\">€</label>
                            </div>
                    </div>
                 </div><br/>";
            echo "<h4>Tracklist</h4>";
            $form->divheader("form-row", "id=tracklist");
                $form->divheader("form-group col-md-6");
                    $form->input("trackstitle0", "Track title", "text", "trackstitle[]", "Track title");
                $form->divfooter();
                 $form->divheader("form-group col-md-6");
                    echo "<label for=\"tracks\">File</label>
                         <div class=\"custom-file\">
                            <input type=\"file\" class=\"custom-file-input\" id=\"tracks0\" name=\"tracks[]\" onchange=\"filename(this.value, 'labeltracks0')\" aria-describedby=\"FormatHelp\"/>
                            <label class=\"custom-file-label\" for=\"tracks\" id=\"labeltracks0\">Add a track</label>
                             <small id=\"FormatHelp\" class=\"form-text text-muted\">
                            mp3, aac, m4a / 15Mo
                        </small>
                        </div>";
                $form->divfooter();
            $form->divfooter();
            echo "<div id=\"form_body\"></div>";
            echo"<button type=\"button\" class=\"btn btn-info btn-sm\" id=\"add\" name=\"add\">+</button> <br/> <br/>";
            $form->button("btn btn-primary", "Submit", "submitbtn", "button");
        $form->footer();
    echo "<div id=\"album_error\"></div>";
    $form->divfooter();
?>

<script>
    
    var i = 1;
    document.querySelector('#add').addEventListener('click', function() {
        
        if(i == 20) { return; }
        
        var input_id = 'tracks'+i;
        var label_id = 'labeltracks'+i;
        var title_id = 'trackstitle'+i;
        var tracksDiv = document.querySelector('#form_body');
        var newTrack = document.createElement('div');
        newTrack.innerHTML +='<div class="form-row">'+
                                    '<div class="form-group col-md-6">'+
                                        '<label for="trackstitle">Track title</label>'+
                                        '<input type="text" class ="form-control" id='+title_id+' name="trackstitle[]" placeholder="Track title"/>'+
                                    '</div>'+
                                    '<div class="form-group col-md-6">'+
                                        '<label for="tracks">File</label>'+
                                        '<div class="custom-file">'+
                                            '<input type="file" class="custom-file-input" id='+input_id+' name="tracks[]" onchange="filename(this.value,'+label_id+')" aria-describedby="FormatHelp"/>'+
                                            '<label class="custom-file-label" for="tracks" id='+label_id+'>Add a track</label>'+
                                            '<small id="FormatHelp" class="form-text text-muted">'+
                                                'mp3, aac, m4a / 15Mo'+
                                            '</small>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
        
        tracksDiv.appendChild(newTrack);
        i=i+1;
        
    });
    
    function filename(val, id)
    {
        var f_val = val.split("\\")[2];
        var f_id;
        if(typeof(id) === 'string') { f_id = '#'+id; }
        else { f_id = '#'+id.id; }
        
        document.querySelector(f_id).innerHTML= f_val;
    }
    
    function errorset(message)
    {
        return '<div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">'+
                                        '<strong>'+message+
                                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                                            '<span aria-hidden="true">&times;</span>'+
                                        '</button>'+
                                '</div>';
    }
    
    document.querySelector('#submitbtn').addEventListener('click', function() {
        
        
        var title = document.querySelector('#title').value;
        var cover = document.querySelector('#cover').value;
        var price = document.querySelector('#albumprice').value;
        var num_price = parseFloat(price);
        var trackstitle = [];
        var tracks = [];
        var cover_size;
        var tracks_size = [];
        
        var cover_max_size = 200000; //200Ko
        var track_max_size = 15000000; //15Mo
        
        if(cover !="")
        {
            cover_size=document.querySelector('#cover').files;
            cover_size=cover_size[0].size;
        }
        
        for(var j = 0; j < i; j++)
        {
            var tracktitle = document.querySelector('#trackstitle'+j).value;
            var track = document.querySelector('#tracks'+j).value;
            var size;
            if(track!="")
            {
                var files = document.querySelector('#tracks'+j).files;
                size = files[0].size;
            }
            else
            {
                track="";
                size=0;
            }
            
            trackstitle.push(tracktitle);
            tracks.push(track); 
            tracks_size.push(size);
        }
        
        var track_number = 0;
        var title_number = 0;
        for(var j = 0; j < i; j++)
        {
            if(tracks[j] != "") { track_number++; }
            if(trackstitle[j] != "") { title_number++; }
        }
        
        var track_title_validity = 0;
        
        for(var j = 0; j < i; j++)
        {
            if(tracks[j] != "" && trackstitle[j] != "") { track_title_validity++; }
        }
        
        var track_size_validity = 0;
        for(var j = 0; j < tracks_size.length; j++)
        {
            if(tracks_size[j] > track_max_size) { track_size_validity++; }
        }
        
        
        if(title!="" && cover!="" && track_number >=3 && title_number >= 3 && track_number==title_number && track_title_validity==track_number && cover_size <= cover_max_size && track_size_validity==0 && /^[0-9]*\.?[0-9]{0,2}?$/.test(price) && price !="" && num_price <= 3.0)
        {
            document.querySelector('#albumregister').submit();
        }
        else
        {
            var errorDiv = document.createElement('div');
            errorDiv.id = 'error';
            document.querySelector('#album_error').appendChild(errorDiv);
            
            if(title=="") { errorDiv.innerHTML += errorset('Please provide a title for your album!'); }
            if(cover=="") { errorDiv.innerHTML += errorset('Please provide a cover for your album!'); }
            if(track_number < 3) { errorDiv.innerHTML += errorset('Please provide at least three track!'); }
            if(track_size_validity > 0) { errorDiv.innerHTML += errorset('Tracks size is limited to 25Mo!'); }
            if(cover_size > cover_max_size) {errorDiv.innerHTML += errorset('Cover maximun size exceeded!'); }
            if(!/^[0-9]*\.?[0-9]{0,2}?$/.test(price)) { errorDiv.innerHTML += errorset('Please provide a correct price for your article!'); }
            if(price=="") { errorDiv.innerHTML += errorset('Please provide a price for your article!'); }
            if(num_price > 3) { errorDiv.innerHTML += errorset('The price must be between 0 and 3!'); }
            
             for(var j = 0; j < i; j++)
             {
                if(tracks[j] == "" && trackstitle[j] != "") { errorDiv.innerHTML += errorset('Some tracks doesn\'t have a file!'); }
                if(tracks[j] != "" && trackstitle[j] == "") { errorDiv.innerHTML += errorset('Some tracks doesn\'t have a title!'); }
             }
        }
        
    });
    
</script>

<?php

if(isset($_FILES['tracks']) && isset($_FILES['cover']) && isset($_POST['trackstitle']) && isset($_POST['title']) && isset($_POST['tag']) && isset($_POST['albumprice']))
{
    $_SESSION['last_registered'] = "#album";
    $error = 0;
    
    $database = new database();
    $title = $database->format($_POST['title']);
    $request = "SELECT album_id FROM album WHERE name={$title} AND owner='{$_SESSION['pseudo']}'";
    $count = $database->select($request);
    if($count[0] > 0)
    {
        $error++;
        display_error("This album was already registered!");
    }
    if($_POST['title'] == "" || strlen($_POST['title']) > 255)
    {
        $error++;
        display_error("Please add a title for your album!");
    }
    
    $tag = array("Pop", "Rock", "Rap", "Electro", "RnB", "J-Pop", "K-Pop", "Trap", "Instru", "Opera", "Chorus", "Techno", "Jazz", "Classic", "Afro");
    if(array_search($_POST['tag'], $tag) === false)
    {
        $error++;
        display_error("An error has occuried!");
    }
    
    $price = intval($_POST['albumprice']);
    if($price > 3 || $_POST['albumprice'] =="" || !preg_match("/^[0-9]*\.?[0-9]{0,2}?$/", trim($_POST['albumprice'])))
    {
        $error++;
        display_error("Please provide a correct price for your article! He must be between 0 and 3");
    }
    //200000; //200Ko
    //15000000; //25Mo
    if(file_exists($_FILES['cover']['tmp_name']))
    {
        if(filesize($_FILES['cover']['tmp_name']) > 200000)
        {
            $error++;
            display_error("Cover maximun size exceeded!");
        }
    }
    else
    {
        $error++;
        display_error("Please add a cover for your album!");
    }
    
    for($i = 0; $i < sizeof($_FILES['tracks']['tmp_name']); $i++)
    {
        if(file_exists($_FILES['tracks']['tmp_name'][$i]))
        {
            if(filesize($_FILES['tracks']['tmp_name'][$i]) > 15000000)
            {
                $error++;
                display_error("Tracks size is limited to 15Mo!");
            }
        }
    }
    $title_number = 0;
    $track_number = 0;
    for($i = 0; $i < sizeof($_FILES['tracks']['tmp_name']); $i++)
    {
        if(file_exists($_FILES['tracks']['tmp_name'][$i]))
        {
            $track_number++;
        }
    }
    for($i = 0; $i < sizeof($_POST['trackstitle']); $i++)
    {
        if($_POST['trackstitle'][$i] != "")
        {
            $title_number++;
        }
    }
    if($track_number < 3 || $title_number < 3)
    {
        $error++;
        display_error("Please provide at least 3 tracks!");
    }
    if($track_number != $title_number)
    {
        $error++;
        display_error("Some files don't have a title!");
    }
    
    for($i = 0; $i < sizeof($_POST['trackstitle']); $i++)
    {
        if($_POST['trackstitle'][$i] != "" && file_exists($_FILES['tracks']['tmp_name'][$i]) === false)
        {
            $error++;
            display_error("Some files don't have a title!");
        }
        if($_POST['trackstitle'][$i] == "" && file_exists($_FILES['tracks']['tmp_name'][$i]) === true)
        {
            $error++;
            display_error("Some files don't have a title!");
        }
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    for($i = 0; $i < sizeof($_FILES['tracks']['tmp_name']); $i++)
    {
        if(file_exists($_FILES['tracks']['tmp_name'][$i]))
        {
            if (false === $ext = array_search($finfo->file($_FILES['tracks']['tmp_name'][$i]), array('mp3' => 'audio/mpeg', '3-mp3' => 'audio/mpeg3', 'x-mp3' => 'audio/x-mpeg-3', 'aac' => 'audio/aac', 'm4a' => 'audio/m4a', 'x-m4a' => 'audio/x-m4a'), true))
            {
                $error++;
                display_error("Tracks files have a incorrect format!");
            }
        }
    }
    
    if(file_exists($_FILES['cover']['tmp_name']))
    {
        if(false === $ext = array_search($finfo->file($_FILES['cover']['tmp_name']), array('jpeg' => 'image/jpeg', 'jpg' => 'image/jpeg', 'png' => 'image/png'), true))
        {
            $error++;
            display_error("Cover file have a incorrect format!");
        }
    }
    
    if($error==0)
    {
        $database = new database();
        $date = date("m.d.y");
        $coverfileInfo = pathinfo($_FILES['cover']['name']);
        $coverName= uniqid('', true). '.'.$coverfileInfo['extension'];
        $bdd_title = $database->format($_POST['title']);
        $title = htmlspecialchars($_POST['title']);
        move_uploaded_file($_FILES['cover']['tmp_name'], 'covers/'.$coverName);
        $id = $database->insert("INSERT INTO album VALUES('\N', {$bdd_title}, '{$_SESSION['pseudo']}', '$coverName','{$date}','{$_POST['tag']}', '{$_POST['albumprice']}')");
        
        $zip = new ZipArchive();
        if(!$zip->open("albums/{$id}.zip", ZipArchive::CREATE)) { echo "Une erreur est survenu"; }
        else { $zip->addEmptyDir("$title"); }
        
        mkdir("albums/{$id}-read");
        $number = 1;
        foreach($_FILES['tracks']['name'] as $key => $value)
        {
            if($value==""){ continue; }
            if($_POST['trackstitle'][$key]=="") { continue; }
            $str_number;
            if($number < 10) { $str_number = '0'."$number"; }
            else {$str_number = "$number"; }
            
            $tmp_name = $_FILES['tracks']['tmp_name'][$key];
            $fileInfo = pathinfo($_FILES['tracks']['name'][$key]);
            $name= $str_number.' '.$_POST['trackstitle'][$key].'.'.$fileInfo['extension'];
            $path = 'albums/'.$id.'-read/'.$name;
            $tracktitle = $database->format($_POST['trackstitle'][$key]);
            $database->insert("INSERT INTO track VALUES('\N', {$tracktitle}, '{$id}','{$fileInfo['extension']}')");
            move_uploaded_file($tmp_name, $path);
            $number++;
            
            $zip_path = $title.'/'.$name;
            $zip->addFile($path, $zip_path);
        }
        $zip->close(); 
        
        echo "<script> 
                function successset(message)
                {
                    return '<div class=\"alert alert-success alert-dismissible fade show mt-2\" role=\"alert\">'+
                                        '<strong>'+message+
                                        '<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">'+
                                            '<span aria-hidden=\"true\">&times;</span>'+
                                        '</button>'+
                                '</div>';
                }
                
                var errorDiv = document.createElement('div');
                errorDiv.id = 'error';
                document.querySelector('#album_error').appendChild(errorDiv);
                errorDiv.innerHTML += successset(\"Your product has been registered!\");
            </script>";
    }
}

?>