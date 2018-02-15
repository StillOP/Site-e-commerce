<?php

class form
{
    public function header($action, $id="", $enctype="application/x-www-form-urlencoded", $name="")
    {
        echo "<form style=\"width:90%;\" class=\"ml-4 mt-5\" action=\"{$action}\" method=\"post\" enctype=\"{$enctype}\" id=\"{$id}\", name=\"{$name}\">";
    }
    
    public function footer() { echo "</form>"; }
    
    public function input($id, $label, $type, $name, $placeholder, $value=null, $hidden=null, $class="form-control", $additionnal=null)
    {
        echo "<label for=\"{$id}\">{$label}</label>";
        echo "<input type=\"{$type}\" class =\"{$class}\" id=\"{$id}\" name=\"{$name}\" placeholder=\"{$placeholder}\" value=\"{$value}\" {$hidden} {$additionnal} />";
    }
    
    public function button($class, $label, $id, $type="submit")
    {
        echo "<button type=\"{$type}\" class=\"{$class}\" id=\"{$id}\">{$label}</button>";
    }
    
    public function checkbox($id, $label, $name)
    {
        echo " <input class=\"form-check-input\" type=\"checkbox\" id=\"{$id}\" name=\"{$name}\">
      <label class=\"form-check-label\" for=\"{$id}\">
        {$label}
      </label>";
    }
    
    public function divheader($class, $additionnal=null)
    {
        echo "<div class=\"{$class}\" {$additionnal}>";
    }
    
    public function divfooter() { echo "</div>"; }
}

?>