<?php

session_start();
require_once("database.inc.php");
require_once("header.inc.php");
require_once("footer.inc.php");


$header = new header();
$footer = new footer();
$database = new database();

if(isset($_GET['key']))
{
    $key = $_GET['key'];
    
    $user_result = $database->select("SELECT pseudo FROM user WHERE pseudo LIKE '%{$key}%'");
    
    $album_name_result = $database->select("SELECT * FROM album WHERE name LIKE '%{$key}%'");
    $album_tag_result = $database->select("SELECT * FROM album WHERE tag LIKE '%{$key}%'");
    
    $book_name_result = $database->select("SELECT * FROM book WHERE title LIKE '%{$key}%'");
    $book_tag_result = $database->select("SELECT * FROM book WHERE tag LIKE '%{$key}%'");
    
    $dailypost_name_result = $database->select("SELECT * FROM dailypost WHERE title LIKE '%{$key}%'");
    $dailypost_tag_result = $database->select("SELECT * FROM dailypost WHERE tag LIKE '%{$key}%'");
    
    $goodies_name_result = $database->select("SELECT * FROM goodies WHERE title LIKE '%{$key}%'");
    $goodies_description_result = $database->select("SELECT * FROM goodies WHERE description LIKE '%{$key}%'");
}
else if(isset($_GET['forum_key']))
{
    $key = $_GET['forum_key'];
    
    $forum_user_result = $database->select("SELECT * FROM topic WHERE owner='{$key}' ORDER BY date DESC");
    $forum_topic_result = $database->select("SELECT * FROM topic WHERE title LIKE '%{$key}%' ORDER BY date DESC");
}
else
{
    header("Location:index.php");
}

$header->display("Search result", $_SESSION);

?>

<?php

echo "<style>a {color:#51acc7;}</style>";
echo "<br/>";
echo "<h3 style=\"margin-left:10px;\">Result</h3><br/>";

if(isset($_GET['key']))
{
if($user_result[0] > 0 )
{
    echo "<div class=\"col-xs-12\" style=\"margin-left:10px;\">
            <h4>User</h4>
         <hr style=\"border-width:1px;background-color:#d3d3d3;width:90%;\">";
    
    for($i = 1; $i <= $user_result[0]; $i++)
    {
            echo "<div class=\"col-md-3\">
                    <a href=\"profil.php?pseudo={$user_result[$i]->pseudo}\">
                        <h6>{$user_result[$i]->pseudo}</h6>
                    </a>
                </div>";
    }
    
    echo "</div><br/>";
}

if($album_name_result[0] > 0 || $album_tag_result[0] > 0)
{
    echo "<div class=\"col-xs-12\" style=\"margin-left:10px;\">
            <h4>Album</h4>
            <hr style=\"border-width:1px;background-color:#d3d3d3;width:90%;\">";
    if($album_name_result[0] > 0)
    {
        for($i = 1; $i <= $album_name_result[0]; $i++)
        {
            echo "<div class=\"col-md-3\" style=\"margin:10px;\">
                    <div class=\"card\" style=\"width:18rem;height:20rem;\">
                        <a href=\"article.php?type=album&article_id={$album_name_result[$i]->album_id}\">
                            <div style=\"width:18rem;height:15rem;background-image:url(covers/{$album_name_result[$i]->cover});background-size:cover;background-position:center;\">
                            </div>
                        </a>
                        <div class=\"card-body\">
                            <h6 class=\"card-title\">{$album_name_result[$i]->name}</h6>
                            <h6 class=\"card-subtitle text-muted\">{$album_name_result[$i]->owner}</h6>
                        </div>
                    </div>
                </div><br/>";
        }
    }
    if($album_tag_result[0] > 0)
    {
        for($i = 1; $i <= $album_tag_result[0]; $i++)
        {
            echo "<div class=\"col-md-3\" style=\"margin:10px;\">
                    <div class=\"card\" style=\"width:18rem;height:20rem;\">
                        <a href=\"article.php?type=album&article_id={$album_tag_result[$i]->album_id}\">
                            <div style=\"width:18rem;height:15rem;background-image:url(covers/{$album_tag_result[$i]->cover});background-size:cover;background-position:center;\">
                            </div>
                        </a>
                        <div class=\"card-body\">
                            <h6 class=\"card-title\">{$album_tag_result[$i]->name}</h6>
                            <h6 class=\"card-subtitle text-muted\">{$album_tag_result[$i]->owner}</h6>
                        </div>
                    </div>
                </div><br/>";
        }
    }
    
    echo "</div><br/>";
}

if($book_name_result[0] > 0 || $book_tag_result[0] > 0)
{
    echo "<div class=\"col-xs-12\" style=\"margin-left:10px;\">
            <h4>Book</h4>
            <hr style=\"border-width:1px;background-color:#d3d3d3;width:90%;\">";
    if($book_name_result[0] > 0)
    {
        for($i = 1; $i <= $book_name_result[0]; $i++)
        {
            echo "<div class=\"col-md-3\" style=\"margin:10px;\">
                    <div class=\"card\" style=\"width:18rem;height:20rem;\">
                        <a href=\"article.php?type=album&article_id={$book_name_result[$i]->book_id}\">
                            <div style=\"width:18rem;height:15rem;background-image:url(covers/{$book_name_result[$i]->cover});background-size:cover;background-position:center;\">
                            </div>
                        </a>
                        <div class=\"card-body\">
                            <h6 class=\"card-title\">{$book_name_result[$i]->title}</h6>
                            <h6 class=\"card-subtitle text-muted\">{$book_name_result[$i]->owner}</h6>
                        </div>
                    </div>
                </div><br/>";
        }
    }
    if($book_tag_result[0] > 0)
    {
        for($i = 1; $i <= $book_tag_result[0]; $i++)
        {
            echo "<div class=\"col-md-3\" style=\"margin:10px;\">
                    <div class=\"card\" style=\"width:18rem;height:20rem;\">
                        <a href=\"article.php?type=album&article_id={$book_tag_result[$i]->book_id}\">
                            <div style=\"width:18rem;height:15rem;background-image:url(covers/{$book_tag_result[$i]->cover});background-size:cover;background-position:center;\">
                            </div>
                        </a>
                        <div class=\"card-body\">
                            <h6 class=\"card-title\">{$book_tag_result[$i]->title}</h6>
                            <h6 class=\"card-subtitle text-muted\">{$book_tag_result[$i]->owner}</h6>
                        </div>
                    </div>
                </div><br/>";
        }
    }
    
    echo "</div><br/>";
}

if($dailypost_name_result[0] > 0 || $dailypost_tag_result[0] > 0)
{
    echo "<div class=\"col-xs-12\" style=\"margin-left:10px;\">
            <h4>Daily post</h4>
         <hr style=\"border-width:1px;background-color:#d3d3d3;width:90%;\">";
    if($dailypost_name_result[0] > 0)
    {
        for($i = 1; $i <= $dailypost_name_result[0]; $i++)
        {
            echo "<div class=\"col-md-3\">
                    <a href=\"article.php?type=dailypost&article_id={$dailypost_name_result[$i]->dailypost_id}\">
                        <h6>{$dailypost_name_result[$i]->title}</h6>
                    </a>
                </div>";
        }
    }
    if($dailypost_tag_result[0] > 0)
    {
        for($i = 1; $i <= $dailypost_tag_result[0]; $i++)
        {
            echo "<div class=\"col-md-3\">
                    <a href=\"article.php?type=dailypost&article_id={$dailypost_tag_result[$i]->dailypost_id}\">
                        <h6>{$dailypost_tag_result[$i]->title}</h6>
                    </a>
                </div>";
        }
    }
    
    echo "</div><br/>";
}

if($goodies_name_result[0] > 0 || $goodies_description_result[0] > 0)
{
    echo "<div class=\"col-xs-12\" style=\"margin-left:10px;\">
            <h4>Goodies</h4>
            <hr style=\"border-width:1px;background-color:#d3d3d3;width:90%;\">";
    if($goodies_name_result[0] > 0)
    {
        for($i = 1; $i <= $goodies_name_result[0]; $i++)
        {
            $images = explode(",", $goodies_name_result[$i]->images);
            
            echo "<div class=\"col-md-3\" style=\"margin:10px;\">
                    <div class=\"card\" style=\"width:18rem;height:20rem;\">
                        <a href=\"article.php?type=album&article_id={$goodies_name_result[$i]->goodies_id}\">
                            <div style=\"width:18rem;height:15rem;background-image:url(covers/{$images[0]});background-size:cover;background-position:center;\">
                            </div>
                        </a>
                        <div class=\"card-body\">
                            <h6 class=\"card-title\">{$goodies_name_result[$i]->title}</h6>
                            <h6 class=\"card-subtitle text-muted\">{$goodies_name_result[$i]->owner}</h6>
                        </div>
                    </div>
                </div><br/>";
        }
    }
    if($goodies_description_result[0] > 0)
    {
        for($i = 1; $i <= $goodies_description_result[0]; $i++)
        {
            echo "<div class=\"col-md-3\" style=\"margin:10px;\">
                    <div class=\"card\" style=\"width:18rem;height:20rem;\">
                        <a href=\"article.php?type=album&article_id={$goodies_description_result[$i]->goodies_id}\">
                            <div style=\"width:18rem;height:15rem;background-image:url(covers/{$goodies_description_result[$i]->cover});background-size:cover;background-position:center;\">
                            </div>
                        </a>
                        <div class=\"card-body\">
                            <h6 class=\"card-title\">{$goodeis_description_result[$i]->title}</h6>
                            <h6 class=\"card-subtitle text-muted\">{$goodies_description_result[$i]->owner}</h6>
                        </div>
                    </div>
                </div><br/>";
        }
    }
    
    echo "</div><br/>";
}
}

if(isset($_GET['forum_key']))
{
if($forum_user_result[0] > 0 || $forum_topic_result[0] > 0)
{
    echo "<form class=\"form-inline my-2 my-lg-0\" method=\"get\" action=\"search.php\" style=\"margin-left:10px;\">
             <input class=\"form-control mr-sm-2\" type=\"search\" name=\"forum_key\" placeholder=\"Search\" aria-label=\"Search\">
             <button class=\"btn my-2 my-sm-0\" type=\"submit\" style=\"background-color:white;color:rgba(0,0,0,.5);\">
                 <i class=\"material-icons\">search</i>
             </button>
        </form><br/>";
                 
    echo "<div class=\"col-md-9\"><table class=\"table table-sm table-hover\">
            <thead>
                <tr>
                    <th scope=\"col\"><h5>TOPIC</h5></th>
                    <th scope=\"col\"><h5>AUTHOR</h5></th>
                    <th scope=\"col\"><h5>NB</h5></th>
                    <th scope=\"col\"><h5>LAST POST</h5></th>
                </tr>
            </thead>
            <tbody>";
        
        
    if($forum_user_result[0] > 0)
    {
        for($i = 1; $i <= $forum_user_result[0]; $i++)
        {
            $style = "color:#51acc7;";
            $statut = $database->select("SELECT statut FROM topic WHERE topic_id='{$forum_user_result[$i]->topic_id}'");
            if($statut[1]->statut == "pin") { $style = "color:#3cb371;"; }
            
            $owner_style="color:#007bff;";
            $owner_statut = $database->select("SELECT statut FROM user WHERE pseudo='{$forum_user_result[$i]->owner}'");
            if($owner_statut[1]->statut) { $owner_statut = "color:#dc3545;"; }
            
            $nbs = $database->select("SELECT response_id FROM response WHERE topic_id='{$forum_user_result[$i]->topic_id}'");
            $nbs  = $nbs[0] - 1;
            if($nbs >= 20) { $style = "color:red;"; }
            
            echo "<tr>
                    <th scope=\"row\">
                        <span style=\"display:inline-flex;\">
                            <h6 style=\"color:orange;\"><i class=\"material-icons\">question_answer</i> &nbsp;</h6>
                            <a href=\"topic.php?id={$forum_user_result[$i]->topic_id}&title={$forum_user_result[$i]->title}&topic_page=1\">
                                <h6 style=\"color:#212529\">{$forum_user_result[$i]->title}</h6>
                            </a>
                        </span>
                    </th>
                    <td>
                        <a href=\"profil.php?pseudo={$forum_user_result[$i]->owner}\">
                            <h6>{$forum_user_result[$i]->owner}</h6>
                        </a>
                    </td>
                    <td>
                        <h6>{$nbs}</h6>
                    </td>
                </tr>";
        }
    }
    if($forum_topic_result[0] > 0)
    {
        for($i = 1; $i <= $forum_topic_result[0]; $i++)
        {
            $style = "color:#51acc7;";
            $statut = $database->select("SELECT statut FROM topic WHERE topic_id='{$forum_topic_result[$i]->topic_id}'");
            if($statut[1]->statut == "pin") { $style = "color:#3cb371;"; }
            
            $owner_style="color:#007bff;";
            $owner_statut = $database->select("SELECT statut FROM user WHERE pseudo='{$forum_topic_result[$i]->owner}'");
            if($owner_statut[1]->statut) { $owner_statut = "color:#dc3545;"; }
            
            $nbs = $database->select("SELECT response_id FROM response WHERE topic_id='{$forum_topic_result[$i]->topic_id}'");
            $nbs  = $nbs[0] - 1;
            if($nbs >= 20) { $style = "color:red;"; }
            
            echo "<tr>
                    <th scope=\"row\">
                        <span style=\"display:inline-flex;\">
                            <h6 style=\"color:orange;\"><i class=\"material-icons\" style={$style}>question_answer</i> &nbsp;</h6>
                            <a href=\"topic.php?id={$forum_topic_result[$i]->topic_id}&title={$forum_topic_result[$i]->title}&topic_page=1\">
                                <h6 style=\"color:#212529\">{$forum_topic_result[$i]->title}</h6>
                            </a>
                        </span>
                    </th>
                    <td>
                        <a href=\"profil.php?pseudo={$forum_topic_result[$i]->owner}\">
                            <h6 style={$owner_style}>{$forum_topic_result[$i]->owner}</h6>
                        </a>
                    </td>
                    <td>
                        <h6 style=\"color:#868e96\">{$nbs}</h6>
                    </td>
                    <td>
                        <h6 style=\"color:#868e96\">{$forum_topic_result[$i]->last_message_date}</h6>
                    </td>
                </tr>";
        }
    }
    
    echo "</tbody></table></div>";
}
}

if(isset($_GET['key']))
{
    if($user_result[0] == 0 && $album_name_result[0] == 0 && $album_tag_result[0] == 0 && $book_name_result[0] == 0 && $book_tag_result[0] == 0 && $dailypost_name_result[0] == 0 && $dailypost_tag_result[0] == 0 && $goodies_name_result[0] == 0 && $goodies_description_result[0] == 0)
    {
        echo "<div class=\"col-md-3\" style=\"margin-left:10px;\">
                <h5>Nothing to show!</h5>
            </div>";
    }
}
if(isset($_GET['forum_key']))
{
    if($forum_user_result[0] == 0 && $forum_topic_result[0] == 0)
    {
        echo "<div class=\"col-md-3\" style=\"margin-left:10px;\">
                <h5>Nothing to show!</h5>
            </div>";
    }
}


?>

<?php

$footer->display();

?>
