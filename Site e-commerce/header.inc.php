<?php

class header
{
    public function display($title, $session)
    {
        $log;
        if(isset($session['connected']) && $session['connected']=="true")
        {
             $log= "<li class=\"nav-item dropdown\">
                        <a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"navbarDropdown2\" role=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                            {$session['pseudo']}
                        </a>
                        <div class=\"dropdown-menu\" aria-labelledby=\"navbarDropdown2\">
                            <a class=\"dropdown-item\" href=\"account.php\">Account</a>
                            <a class=\"dropdown-item\" href=\"register.php\">Add an article</a>
                            <form action=\"index.php\" method=\"post\" enctype=\"application/x-www-form-urlencoded\">
                                <button class=\"dropdown-item\" type=\"submit\" name=\"logout\">Log out </button>
                            </form>
                        </div>
                    </li>";
        }
        else
        {
            $log= "<li class=\"nav-item\">
                        <a class=\"nav-link\" href=\"connexion.php\">
                            Log in
                        </a>
                    </li>";
        }
           
        echo "<!DOCTYPE html>
            <html lang=\"fr | en\">
                <head>
                    <meta charset=\"utf-8\">
                    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">
                    <link href=\"https://fonts.googleapis.com/icon?family=Material+Icons\"
      rel=\"stylesheet\">
                    <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css\" integrity=\"sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy\" crossorigin=\"anonymous\">
                    <title>{$title}</title>
                </head>
                <body>
                    <nav class=\"navbar navbar-expand-lg navbar-light bg-light\" style=\"z-index:100;position:fixed;width:100%;\">
                        <a class=\"navbar-brand\" href=\"index.php\">
                            Kreativ
                        </a>
                        <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarSupportedContent\" aria-controls=\"navbarSupportedContent\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
                            <span class=\"navbar-toggler-icon\"></span>
                        </button>
                        
                        <div class=\"collapse navbar-collapse\" id=\"navbarSupportedContent\">
                            <ul class=\"navbar-nav mr-auto\">
                            </ul>
                            <ul class=\"navbar-nav\" style=\"margin-right:6%;\">
                               <form class=\"form-inline my-2 my-lg-0\"method=\"get\" action=\"search.php\">
                                    <input class=\"form-control mr-sm-2\" type=\"search\" name=\"key\" placeholder=\"Search\" aria-label=\"Search\" style=\"width:400px;\">
                                    <button class=\"btn my-2 my-sm-0\" type=\"submit\" style=\"background-color:#f8f9fa;color:rgba(0,0,0,.5);\"><i class=\"material-icons\">search</i></button>
                                </form>
                                <li class=\"nav-item\">
                                    <a class=\"nav-link\" href=\"forum.php?page=1\">
                                        Forum
                                    </a>
                                </li>
                                {$log}
                            </ul>
                        </div>
                    </nav><br/><br/>";
    }
}
//<meta name="description" content="Portfolio de KOMBA BETAMBO Gaston Pierre. Etudaint en école d'ingénieur informatique"/>
?>