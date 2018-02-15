<?php

$form->divheader("tab-pane fade", 'id="goodies" role="tabpanel" aria-labelledby="goodies-tab"');

        $form->header($_SERVER['PHP_SELF'], "goodiesregister", "multipart/form-data", "goodiesregister");

        $form->divheader("form-group");
                $form->input("goodiestitle", "Title", "text", "goodiestitle", "Title");
            $form->divfooter();
             $form->divheader("form-group");
                echo "<div class=\"custom-file\">
                        <input type=\"file\" class=\"custom-file-input\" id=\"images\" name=\"images[]\" onchange=\"multiplefilename(this.value, 'imageslabel')\" aria-describedby=\"FormatHelp\" multiple/>
                        <label class=\"custom-file-label\" for=\"images\" id=\"imageslabel\">Add your images</label>
                        <small id=\"FormatHelp\" class=\"form-text text-muted\">
                            jpeg, png / 200Ko / you can add select multiple files(4 max)
                        </small>
                    </div>";
            $form->divfooter();
        echo "<div class=\"form-goup\" style=\"width:10%;\">
                        <label for=\"goodiesprice\">Price</label>
                        <div class=\"input-group\">
                            <input type=\"text\" class=\"form-control\" id=\"goodiesprice\" name=\"goodiesprice\" pattern=\"[0-9]+([\.][0-9]{0,2})?\"/>
                            <div class=\"input-group-prepend\">
                                <label class=\"input-group-text\" for=\"goodiesprice\">€</label>
                            </div>
                    </div>
                 </div><br/>";
        $form->divheader("form-group");
                echo "<label for=\"description\">Description</label>
                    <textarea class=\"form-control\" id=\"goodiesdescription\" name=\"goodiesdescription\" rows=\"5\"></textarea>";
        $form->divfooter();
        $form->button("btn btn-primary", "Submit", "submitbtn4", "button");


$form->footer();
echo "<div id=\"goodies_error\"></div>";

$form->divfooter();

//colorzilla
?>

<script>
    
    function multiplefilename(val, id)
    {
        var f_id;
        var f_val ="";
        if(typeof(id) === 'string') { f_id = '#'+id; }
        else { f_id = '#'+id.id; }
        
        if(window.FileReader && window.Blob)
        {
            if(val!="")
            {
                var m_files = document.querySelector('#images').files;
                console.log(m_files);
                for(var i = 0; i < m_files.length; i++)
                {
                    var name =m_files[i].name;
                    f_val += " " + name;
                }
            }
        }    
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
    
    
    document.querySelector('#submitbtn4').addEventListener('click', function() {
        
        var title = document.querySelector('#goodiestitle').value;
        var images = document.querySelector('#images').value;
        var price = document.querySelector('#goodiesprice').value;
        var description = document.querySelector('#goodiesdescription').value;
        var f_images = document.querySelector('#images').files;
        
        var images_size_validity=0;
        for(var i = 0; i < f_images.length; i++)
        {
            if(f_images[i].size > 200000) { images_size_validity++; }
        }
        
        if(title!="" && images!="" && images_size_validity==0 && /^[0-9]*\.?[0-9]{0,2}?$/.test(price) && price !="" && description!="" && f_images.length <=4)
        {
            document.querySelector('#goodiesregister').submit();
        }
        else
        {
            var errorDiv = document.createElement('div');
            errorDiv.id = 'error';
            document.querySelector('#goodies_error').appendChild(errorDiv);
            
            if(title=="") { errorDiv.innerHTML += errorset('Please provide a title for your article!'); }
            if(images=="") { errorDiv.innerHTML += errorset('Please provide at least 1 image!'); }
            if(images_size_validity > 0) { errorDiv.innerHTML += errorset('Image maximum size exceeded!'); }
            if(price=="") { errorDiv.innerHTML += errorset('Please provide a price for your article!'); }
            if(description=="") { errorDiv.innerHTML += errorset('Please provide a description!'); }
            if(!/^[0-9]*\.?[0-9]{0,2}?$/.test(price)) { errorDiv.innerHTML += errorset('Please provide a correct price for your article!'); }
            if(f_images.length >4) { errorDiv.innerHTML += errorset('You can only upload 4 images for your article!'); }
        }
        
        
    });
    
</script>

<?php

if(isset($_FILES['images']))
{
    $_SESSION['last_registered'] = "#goodies";
    $error = 0;
    $error_id='#goodies_error';
    
    $database = new database();
    $goodiestitle = $database->format($_POST['goodiestitle']);
    $request = "SELECT goodies_id FROM goodies WHERE title={$goodiestitle} AND owner='{$_SESSION['pseudo']}'";
    $count = $database->select($request);
    if($count[0] > 0)
    {
        $error++;
        display_error("This article was already registered!", $error_id);
    }
    
    if($_POST['goodiestitle'] == "" || strlen($_POST['goodiestitle']) > 255)
    {
        $error++;
        display_error("Please add a title for your goodies", $error_id);
    }
    
    if($_POST['goodiesdescription'] == "" || strlen($_POST['goodiesdescription']) > 65000)
    {
        $error++;
        display_error("Please add a description for your goodies", $error_id);
    }
    
    $price = intval($_POST['goodiesprice']);
    if($price > 3 || $_POST['goodiesprice'] =="" || !preg_match("/^[0-9]*\.?[0-9]{0,2}?$/", trim($_POST['goodiesprice'])))
    {
        $error++;
        display_error("Please provide a correct price for your article! He must be between 0 and 3", $error_id);
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $files_count = 0;
    for($i = 0; $i < sizeof($_FILES['images']['tmp_name']); $i++)
    {
        if(file_exists($_FILES['images']['tmp_name'][$i]))
        {
            $files_count++;
            if (false === $ext = array_search($finfo->file($_FILES['images']['tmp_name'][$i]), array('png' => 'image/png', 'jpeg' => 'image/jpeg'), true))
            {
                $error++;
                display_error("Images files have a incorrect format!", $error_id);
            }
            
            if(filesize($_FILES['images']['tmp_name'][$i]) > 200000)
            {
                $error++;
                display_error("Images files maximum size exceeded!", $error_id);
            }
        }
    }
    if($files_count == 0)
    {
        $error++;
        display_error("Please add one or more descriptive images!", $error_id);
    }
    if($files_count > 4)
    {
        $error++;
        display_error("4 files maximum allowed!", $error_id);
    }
    
    if($error==0)
    {
        $database = new database();
        $date = date("m.d.y");
        $imagestab = array();
        
        foreach($_FILES['images']['name'] as $key => $value)
        {
            $tmp_name = $_FILES['images']['tmp_name'][$key];
            $fileInfo = pathinfo($_FILES['images']['name'][$key]);
            $name=uniqid('', true). '.'.$fileInfo['extension'];
            $imagestab[] = $name;
            
            move_uploaded_file($tmp_name, 'covers/'.$name);
        }
        
        $images=implode(",", $imagestab);
        $goodiestitle = $database->format($_POST['goodiestitle']);
        $description = $database->format($_POST['goodiesdescription']);
        $database->insert("INSERT INTO goodies VALUES('\N', {$goodiestitle}, '{$_SESSION['pseudo']}', '{$_POST['goodiesprice']}', {$description}, '{$images}', '{$date}')");
        
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
                document.querySelector('#goodies_error').appendChild(errorDiv);
                errorDiv.innerHTML += successset(\"Your article has been registered!\");
            </script>";
    }
}

?>
