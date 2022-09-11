<?php 
if (!defined('MODULE_FILE')) { 
   die('You can\'t access this file directly...');
}
#################################################[ SandBox Example Source Code ]###########################################################################################
print "<a href=\"#\" onClick = \"tag=this.nextElementSibling; tag.style.display=tag.style.display == 'none' ? 'block':'none'\"><strong>Click here to show | hide source code</strong></a>";
print "<div id=\"php_source\" style=\"display:none\">";
OpenTableCode();
highlight_file("modules/Titanium_SandBox/x-clean_slate_template.php");
CloseTableCode();
print "</div><hr />";
#################################################[ SandBox Example Source Code ]###########################################################################################
# TEST CODE GOES HERE - START
###########################################################################################################################################################################

require_once './includes/classes/class.modules.php';
use Control\Module;
$nuke_module = new \Control\Module();

###########################################################################################################################################################################			
# TEST CODE GOES HERE - END
#################################################[ SandBox Example Source Code ]###########################################################################################
echo "<hr /></fieldset>"; 
#################################################[ SandBox Example Source Code ]###########################################################################################
?> 
