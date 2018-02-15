<?php
    
    $form->divheader("tab-pane fade", 'id="book" role="tabpanel" aria-labelledby="book-tab"');

        $form->header($_SERVER['PHP_SELF'], "bookregister", "multipart/form-data", "bookregister");
             echo "<button type=\"button\" class=\"btn btn-link\" id=\"link\" style=\"color:#51acc7;padding:0;\">Your book isn't finish yet? Add a daily post!</button><br/><br/>";
            $form->divheader("form-group");
                $form->input("booktitle", "Title", "text", "booktitle", "Title");
            $form->divfooter();
             $form->divheader("form-group");
                echo "<div class=\"custom-file\">
                        <input type=\"file\" class=\"custom-file-input\" id=\"bookcover\" name=\"bookcover\" onchange=\"filename(this.value, 'bookcoverlabel')\" aria-describedby=\"FormatHelp\"/>
                        <label class=\"custom-file-label\" for=\"bookcover\" id=\"bookcoverlabel\">Add a cover</label>
                        <small id=\"FormatHelp\" class=\"form-text text-muted\">
                            jpeg, png / 500Ko
                        </small>
                    </div>";
            $form->divfooter();
            $form->divheader("form-group");
                echo "<div class=\"custom-file\">
                        <input type=\"file\" class=\"custom-file-input\" id=\"bookfile\" name=\"bookfile\" onchange=\"filename(this.value, 'bookfilelabel')\" aria-describedby=\"FormatHelp\"/>
                        <label class=\"custom-file-label\" for=\"bookfile\" id=\"bookfilelabel\">Upload your file</label>
                        <small id=\"FormatHelp\" class=\"form-text text-muted\">
                            pdf / 5Mo
                        </small>
                    </div>";
            $form->divfooter();
            $form->divheader("form-group");
                echo "<div class=\"custom-file\">
                        <input type=\"file\" class=\"custom-file-input\" id=\"booksample\" name=\"booksample\" onchange=\"filename(this.value, 'booksamplelabel')\" aria-describedby=\"FormatHelp\"/>
                        <label class=\"custom-file-label\" for=\"booksample\" id=\"booksamplelabel\">Add a sample</label>
                        <small id=\"FormatHelp\" class=\"form-text text-muted\">
                            pdf / 1Mo
                        </small>
                    </div>";
            $form->divfooter();
            echo "<div class=\"form-group\">
                        <label for=\"booktag\">Tag</label>
                        <select class=\"custom-select\" id=\"booktag\" name=\"booktag\">
                            <option value=\"Adventure\">Adventure</option><option value=\"SF\">SF</option><option value=\"Thriller\">Thriller</option><option value=\"Investigation\">Investigation</option><option value=\"Teens\">Teens</option><option value=\"Love\">Love</option><option value=\"History\">History</option><option value=\"Science\">Science</option><option value=\"Humor\">Humor</option><option value=\"Tragedy\">Tragedy</option><option value=\"Light Novel\">Light Novel</option><option value=\"Comics\">Comics</option><option value=\"Mangas\">Mangas</option><option value=\"Photo\">Photo</option><option value=\"Theater\">Theater</option><option value=\"Novelette\">Novelette</option><option value=\"Fantastic\">Fantastic</option>
                        </select>
                 </div>";
            echo "<div class=\"form-goup\" style=\"width:10%;\">
                        <label for=\"bookprice\">Price</label>
                        <div class=\"input-group\">
                            <input type=\"text\" class=\"form-control\" id=\"bookprice\" name=\"bookprice\" pattern=\"[0-9]+([\.][0-9]{0,2})?\"/>
                            <div class=\"input-group-prepend\">
                                <label class=\"input-group-text\" for=\"bookprice\">€</label>
                            </div>
                    </div>
                 </div><br/>";
            $form->divheader("form-group");
                echo "<label for=\"description\">Summary, digest, description...</label>
                    <textarea class=\"form-control\" id=\"description\" name=\"description\" rows=\"5\"></textarea>";
            $form->divfooter();
            $form->button("btn btn-primary", "Submit", "submitbtn2", "button");
        $form->footer();

    echo "<div id=\"book_error\"></div>";
    $form->divfooter();

//$footer->display();

?>

<script>
    
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
    
    document.querySelector('#link').addEventListener('click', function() {
        
         $('#tab a[href="#dailypost"]').tab('show');
    });
    
    document.querySelector('#submitbtn2').addEventListener('click', function() {
        
        var title = document.querySelector('#booktitle').value;
        var cover = document.querySelector('#bookcover').value;
        var file = document.querySelector('#bookfile').value;
        var sample = document.querySelector('#booksample').value;
        var price = document.querySelector('#bookprice').value;
        var num_price = parseFloat(price);
        var description = document.querySelector('#description').value;
        var cover_size; //200Ko
        var file_size; //5Mo
        var sample_size; //1Mo
        
        var cover_max_size = 200000;
        var file_max_size = 5000000;
        var sample_max_size = 1000000;
        
        if(window.FileReader && window.Blob)
        {
            if(cover !="" && file !="" && sample!="")
            {
                cover_size=document.querySelector('#bookcover').files;
                cover_size=cover_size[0].size;
            
                file_size=document.querySelector('#bookfile').files;
                file_size=file_size[0].size;
                
                sample_size=document.querySelector('#booksample').files;
                sample_size=sample_size[0].size;
            }
            else 
            {
                cover_size=0;
                file_size=0;
                sample_size=0;
            }
        }
        
        if(title !="" && cover !="" && file !="" && sample !="" && description!="" && cover_size <= cover_max_size && file_size <= file_max_size && sample_size <= sample_max_size && /^[0-9]*\.?[0-9]{0,2}?$/.test(price) && price !="" && num_price <= 3.0)
        {
            document.querySelector('#bookregister').submit();
        }
        else
        {
            var errorDiv = document.createElement('div');
            errorDiv.id = 'error';
            document.querySelector('#book_error').appendChild(errorDiv);
            
            if(title=="") { errorDiv.innerHTML += errorset('Please provide a title for your book!'); }
            if(cover=="") { errorDiv.innerHTML += errorset('Please provide a cover for your book!'); }
            if(file=="") { errorDiv.innerHTML += errorset('Please upload your book!'); }
            if(sample=="") { errorDiv.innerHTML += errorset('Please upload a sample!'); }
            if(description=="") { errorDiv.innerHTML += errorset('Please provide a description!'); }
            if(cover_size > cover_max_size ) { errorDiv.innerHTML += errorset('Cover maximum size exceeded!'); }
            if(file_size > file_max_size ) { errorDiv.innerHTML += errorset('File maximun size exceeded!'); }
            if(sample_size > sample_max_size) {errorDiv.innerHTML += errorset('Sample maximun size exceeded!');}
            if(!/^[0-9]*\.?[0-9]{0,2}?$/.test(price)) { errorDiv.innerHTML += errorset('Please provide a correct price for your article!'); }
            if(price=="") { errorDiv.innerHTML += errorset('Please provide a price for your article!'); }
            if(num_price > 3) { errorDiv.innerHTML += errorset('The price must be between 0 and 3!'); }
        }
        
    });
    
</script>

<?php

if(isset($_FILES['bookfile']) && isset($_FILES['bookcover']) && isset($_FILES['booksample']) && isset($_POST['booktitle']) && isset($_POST['bookprice']) && isset($_POST['booktag']) && isset($_POST['description']))
{
    $_SESSION['last_registered'] = "#book";
    $error = 0;
    $error_id='#book_error';
    
    $database = new database();
    $booktitle = $database($_POST['booktitle']);
    $request = "SELECT book_id FROM book WHERE title={$booktitle} AND owner='{$_SESSION['pseudo']}'";
    $count = $database->select($request);
    if($count[0] > 0)
    {
        $error++;
        display_error("This book was already registered!", $error_id);
    }
    
    if($_POST['booktitle'] == "" || strlen($_POST['booktitle']) > 255)
    {
        $error++;
        display_error("Please add a title for your book!", $error_id);
    }
    
    if($_POST['description'] == "" || strlen($_POST['description']) > 65000)
    {
        $error++;
        display_error("Please add a description for your book", $error_id);
    }
    
    $tag = array("Adventure", "SF", "Thriller", "Investigation", "Teens", "Love", "History", "Science", "Humor", "Tragedy", "Light Novel", "Comics", "Mangas", "Photo", "Theater", "Novelette", "Fantastic");
    if(array_search($_POST['booktag'], $tag) === false)
    {
        $error++;
        display_error("An error has occuried!", $error_id);
    }
    
    $price = intval($_POST['bookprice']);
    if($price > 3 || $_POST['bookprice'] =="" || !preg_match("/^[0-9]*\.?[0-9]{0,2}?$/", trim($_POST['bookprice'])))
    {
        $error++;
        display_error("Please provide a correct price for your article! He must be between 0 and 3", $error_id);
    }
    //200000; //200Ko
    //5000000; //5Mo
    //1000000; //1Mo
    if(file_exists($_FILES['bookcover']['tmp_name']))
    {
        if(filesize($_FILES['bookcover']['tmp_name']) > 200000)
        {
            $error++;
            display_error("Cover maximun size exceeded!", $error_id);
        }
    }
    else
    {
        $error++;
        display_error("Please add a cover for your book!");
    }
    if(file_exists($_FILES['bookfile']['tmp_name']))
    {
        if(filesize($_FILES['bookfile']['tmp_name']) > 5000000)
        {
            $error++;
            display_error("Book file maximun size exceeded!", $error_id);
        }
    }
    else
    {
        $error++;
        display_error("Please add a file for your book!");
    }
    if(file_exists($_FILES['booksample']['tmp_name']))
    {
        if(filesize($_FILES['booksample']['tmp_name']) > 1000000)
        {
            $error++;
            display_error("Book sample maximun size exceeded!", $error_id);
        }
    }
    else
    {
        $error++;
        display_error("Please add a sample for your book!", $error_id);
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if(file_exists($_FILES['bookfile']['tmp_name']))
    {
        if(false === $book_ext = array_search($finfo->file($_FILES['bookfile']['tmp_name']), array('pdf' => 'application/pdf'), true))
        {
            $error++;
            display_error("Some files have a incorrect format!", $error_id);
        }
    }
    if(file_exists($_FILES['bookcover']['tmp_name']))
    {
        if(false === $book_ext = array_search($finfo->file($_FILES['bookcover']['tmp_name']), array('jpeg' => 'image/jpeg', 'jpg' => 'image/jpeg', 'png' => 'image/png'), true))
        {
            $error++;
            display_error("Book cover has a incorrect format!", $error_id);
        }
    }
    
    if(file_exists($_FILES['booksample']['tmp_name']))
    {
        if(false === $sample_ext = array_search($finfo->file($_FILES['booksample']['tmp_name']), array('pdf' => 'application/pdf'), true))
        {
            $error++;
            display_error("Some files have a incorrect format!", $error_id);
        }
    }
    
    if($error == 0)
    {
        $database = new database();
        
        $coverfileInfo = pathinfo($_FILES['bookcover']['name']);
        $coverName= uniqid('', true). '.'.$coverfileInfo['extension'];
        $bookfileInfo = pathinfo($_FILES['bookfile']['name']);
        $fileName = uniqid('', true). '.'.$bookfileInfo['extension'];
        $samplefileInfo = pathinfo($_FILES['booksample']['name']);
        $sampleName = uniqid('', true). '.'.$samplefileInfo['extension'];
        
        $date = date("m.d.y");
        $title = $database->format($_POST['booktitle']);
        $pre_description = $database->format($_POST['description']);
        $order = array("\r\n", "\n", "\r");
        $replace = '<br/>';
        $description = str_replace($order, $replace, $pre_description);
        
        $database->insert("INSERT INTO book VALUES('\N', {$title}, '{$_SESSION['pseudo']}', '{$coverName}', '{$fileName}', '{$sampleName}', {$description}, '{$date}', '{$_POST['booktag']}', '{$_POST['bookprice']}')");
            
        move_uploaded_file($_FILES['bookcover']['tmp_name'], 'covers/'.$coverName);
        move_uploaded_file($_FILES['bookfile']['tmp_name'], 'books/'.$fileName);
        move_uploaded_file($_FILES['booksample']['tmp_name'], 'samples/'.$sampleName);
        
        
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
                document.querySelector('#book_error').appendChild(errorDiv);
                errorDiv.innerHTML += successset(\"Your product has been registered!!\");
            </script>";
    }
}

?>