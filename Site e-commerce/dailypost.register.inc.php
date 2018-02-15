<?php

$form->divheader("tab-pane fade", 'id="dailypost" role="tabpanel" aria-labelledby="dailypost-tab"');

        $form->header($_SERVER['PHP_SELF'], "dailypostregister", "application/x-www-form-urlencoded", "dailypostregister");
            $form->divheader("form-group");
                $form->input("dailyposttitle", "Title", "text", "dailyposttitle", "Title");
            $form->divfooter();
        echo "<div class=\"form-group\">
                        <label for=\"dailyposttag\">Tag</label>
                        <select class=\"custom-select\" id=\"dailyposttag\" name=\"dailyposttag\">
                            <option value=\"Adventure\">Adventure</option><option value=\"SF\">SF</option><option value=\"Thriller\">Thriller</option><option value=\"Investigation\">Investigation</option><option value=\"Teens\">Teens</option><option value=\"Love\">Love</option><option value=\"History\">History</option><option value=\"Science\">Science</option><option value=\"Humor\">Humor</option><option value=\"Tragedy\">Tragedy</option><option value=\"Light Novel\">Light Novel</option><option value=\"Comics\">Comics</option><option value=\"Mangas\">Mangas</option><option value=\"Photo\">Photo</option><option value=\"Theater\">Theater</option><option value=\"Novelette\">Novelette</option><option value=\"Fantastic\">Fantastic</option>
                        </select>
                 </div>";

            $form->button("btn btn-primary", "Submit", "submitbtn3", "button");
        $form->footer();
        echo "<div id=\"dailypost_error\"></div>";

$form->divfooter();

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
    
    document.querySelector('#submitbtn3').addEventListener('click', function() {
       
        var title=document.querySelector('#dailyposttitle').value;
        if(title !="")
        {
            document.querySelector('#dailypostregister').submit();
        }
        else
        {
            var errorDiv = document.createElement('div');
            errorDiv.id = 'error';
            document.querySelector('#dailypost_error').appendChild(errorDiv);
            
            if(title=="") { errorDiv.innerHTML += errorset('Please provide a title...'); }
        }
        
    });
</script>

<?php

if(isset($_POST['dailyposttitle']) && isset($_POST['dailyposttag']))
{
    $_SESSION['last_registered'] = "#dailypost";
    $error = 0;
    $error_id='#dailypost_error';
    
    $database = new database();
    $dailyposttittle = $database->format($_POST['dailyposttitle']);
    $request = "SELECT dailypost_id FROM dailypost WHERE title={$dailyposttittle} AND owner='{$_SESSION['pseudo']}'";
    $count = $database->select($request);
    if($count[0] > 0)
    {
        $error++;
        display_error("This post was already registered!", $error_id);
    }
    
    if($_POST['dailyposttitle'] == "" || strlen($_POST['dailyposttitle']) > 255)
    {
        $error++;
        display_error("Please add a title for the daily post!", $error_id);
    }
    
    $tag = array("Adventure", "SF", "Thriller", "Investigation", "Teens", "Love", "History", "Science", "Humor", "Tragedy", "Light Novel", "Comics", "Mangas", "Photo", "Theater", "Novelette", "Fantastic");
    if(array_search($_POST['dailyposttag'], $tag) === false)
    {
        $error++;
        display_error("An error has occuried!", $error_id);
    }
    if($error == 0)
    {
        $database = new database();
        $date = date("m.d.y");
        $title = $database->format($_POST['dailyposttitle']);
        $database->insert("INSERT INTO dailypost VALUES('\N', {$title}, '{$_SESSION['pseudo']}', '{$date}', '{$_POST['dailyposttag']}')");
        
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
                document.querySelector('#dailypost_error').appendChild(errorDiv);
                errorDiv.innerHTML += successset(\"Your product has been registered!!\");
            </script>";
    }
}

?>