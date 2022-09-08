<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* acp_icons.html */
class __TwigTemplate_fccc70ec54b63a5cb6cd58e550a4283bd380b5698887c89d66e89aaa8c10d73e extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        $location = "overall_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("overall_header.html", "acp_icons.html", 1)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<a id=\"maincontent\"></a>

";
        // line 5
        if (($context["S_EDIT"] ?? null)) {
            // line 6
            echo "
\t<script>
\t// <![CDATA[
\t";
            // line 9
            if (($context["S_ADD_CODE"] ?? null)) {
                // line 10
                echo "
\t\t\tvar smiley = Array();
\t\t\t";
                // line 12
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "smile", [], "any", false, false, false, 12));
                foreach ($context['_seq'] as $context["_key"] => $context["smile"]) {
                    // line 13
                    echo "\t\t\t\tsmiley['";
                    echo twig_get_attribute($this->env, $this->source, $context["smile"], "SMILEY_URL", [], "any", false, false, false, 13);
                    echo "'] = Array();
\t\t\t\tsmiley['";
                    // line 14
                    echo twig_get_attribute($this->env, $this->source, $context["smile"], "SMILEY_URL", [], "any", false, false, false, 14);
                    echo "']['code'] = '";
                    echo twig_get_attribute($this->env, $this->source, $context["smile"], "CODE", [], "any", false, false, false, 14);
                    echo "';
\t\t\t\tsmiley['";
                    // line 15
                    echo twig_get_attribute($this->env, $this->source, $context["smile"], "SMILEY_URL", [], "any", false, false, false, 15);
                    echo "']['emotion'] = '";
                    echo twig_get_attribute($this->env, $this->source, $context["smile"], "EMOTION", [], "any", false, false, false, 15);
                    echo "';
\t\t\t\tsmiley['";
                    // line 16
                    echo twig_get_attribute($this->env, $this->source, $context["smile"], "SMILEY_URL", [], "any", false, false, false, 16);
                    echo "']['width'] = ";
                    echo twig_get_attribute($this->env, $this->source, $context["smile"], "WIDTH", [], "any", false, false, false, 16);
                    echo ";
\t\t\t\tsmiley['";
                    // line 17
                    echo twig_get_attribute($this->env, $this->source, $context["smile"], "SMILEY_URL", [], "any", false, false, false, 17);
                    echo "']['height'] = ";
                    echo twig_get_attribute($this->env, $this->source, $context["smile"], "HEIGHT", [], "any", false, false, false, 17);
                    echo ";
\t\t\t\tsmiley['";
                    // line 18
                    echo twig_get_attribute($this->env, $this->source, $context["smile"], "SMILEY_URL", [], "any", false, false, false, 18);
                    echo "']['order'] = ";
                    echo twig_get_attribute($this->env, $this->source, $context["smile"], "ORDER", [], "any", false, false, false, 18);
                    echo ";
\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['smile'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 20
                echo "
\t\t\tfunction update_image(newimage)
\t\t\t{
\t\t\t\tvar use_element = smiley[newimage];

\t\t\t\tdocument.getElementById('add_image_src').src = '";
                // line 25
                echo ($context["ROOT_PATH"] ?? null);
                echo ($context["IMG_PATH"] ?? null);
                echo "/' + encodeURI(newimage);
\t\t\t\tdocument.getElementById('add_code').value = use_element['code'];
\t\t\t\tdocument.getElementById('add_emotion').value = use_element['emotion'];
\t\t\t\tdocument.getElementById('add_width').value = use_element['width'];
\t\t\t\tdocument.getElementById('add_height').value = use_element['height'];

\t\t\t\telement = document.getElementById('add_order');
\t\t\t\tfor (var i = 0; i < element.length; i++)
\t\t\t\t{
\t\t\t\t\tif (element.options[i].value == use_element['order'])
\t\t\t\t\t{
\t\t\t\t\t\tdocument.getElementById('add_order').options.selectedIndex = i;
\t\t\t\t\t}
\t\t\t\t}
\t\t\t}

\t";
            }
            // line 42
            echo "

\tfunction toggle_select(icon, display, select)
\t{
\t\tvar disp = document.getElementById('order_disp_' + select);
\t\tvar nodisp = document.getElementById('order_no_disp_' + select);
\t\tdisp.disabled = !display;
\t\tnodisp.disabled = display;
\t\tif (display)
\t\t{
\t\t\tdocument.getElementById('order_' + select).selectedIndex = 0;
\t\t\tnodisp.className = 'disabled-options';
\t\t\tdisp.className = '';
\t\t}
\t\telse
\t\t{
\t\t\tdocument.getElementById('order_' + select).selectedIndex = ";
            // line 58
            echo ($context["S_ORDER_LIST_DISPLAY_COUNT"] ?? null);
            echo ";
\t\t\tdisp.className = 'disabled-options';
\t\t\tnodisp.className = '';
\t\t}
\t}
\t// ]]>
\t</script>

\t<a href=\"";
            // line 66
            echo ($context["U_BACK"] ?? null);
            echo "\" style=\"float: ";
            echo ($context["S_CONTENT_FLOW_END"] ?? null);
            echo ";\">&laquo; ";
            echo $this->extensions['phpbb\template\twig\extension']->lang("BACK");
            echo "</a>

\t<h1>";
            // line 68
            echo $this->extensions['phpbb\template\twig\extension']->lang("TITLE");
            echo "</h1>

\t<p>";
            // line 70
            echo $this->extensions['phpbb\template\twig\extension']->lang("EXPLAIN");
            echo "</p>

\t<form id=\"acp_icons\" method=\"post\" action=\"";
            // line 72
            echo ($context["U_ACTION"] ?? null);
            echo "\">

\t<fieldset class=\"tabulated\">
\t<legend>";
            // line 75
            echo $this->extensions['phpbb\template\twig\extension']->lang("TITLE");
            echo "</legend>

\t<table class=\"table1 zebra-table\" id=\"smilies\">
\t<thead>
\t<tr>
\t\t<th colspan=\"";
            // line 80
            echo ($context["COLSPAN"] ?? null);
            echo "\">";
            echo $this->extensions['phpbb\template\twig\extension']->lang("CONFIG");
            echo "</th>
\t</tr>
\t";
            // line 82
            if ((twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "items", [], "any", false, false, false, 82)) || ($context["S_ADD_CODE"] ?? null))) {
                // line 83
                echo "\t<tr class=\"row3\">
\t\t<td>";
                // line 84
                echo $this->extensions['phpbb\template\twig\extension']->lang("URL");
                echo "</td>
\t\t<td>";
                // line 85
                echo $this->extensions['phpbb\template\twig\extension']->lang("LOCATION");
                echo "</td>
\t";
                // line 86
                if (($context["S_SMILIES"] ?? null)) {
                    // line 87
                    echo "\t\t<td>";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("SMILIES_CODE");
                    echo "</td>
\t\t<td>";
                    // line 88
                    echo $this->extensions['phpbb\template\twig\extension']->lang("SMILIES_EMOTION");
                    echo "</td>
\t";
                }
                // line 90
                echo "\t\t<td>";
                echo $this->extensions['phpbb\template\twig\extension']->lang("WIDTH");
                echo "</td>
\t\t<td>";
                // line 91
                echo $this->extensions['phpbb\template\twig\extension']->lang("HEIGHT");
                echo "</td>
\t";
                // line 92
                if ( !($context["S_SMILIES"] ?? null)) {
                    // line 93
                    echo "\t\t<td>";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("ALT_TEXT");
                    echo "</td>
\t";
                }
                // line 95
                echo "\t\t<td>";
                echo $this->extensions['phpbb\template\twig\extension']->lang("DISPLAY_ON_POSTING");
                echo "</td>
\t";
                // line 96
                if ((($context["ID"] ?? null) || ($context["S_ADD"] ?? null))) {
                    // line 97
                    echo "\t\t<td>";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("ORDER");
                    echo "</td>
\t";
                }
                // line 99
                echo "\t";
                if (($context["S_ADD"] ?? null)) {
                    // line 100
                    echo "\t\t<td>";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("ADD");
                    echo " <a href=\"#\" onclick=\"marklist('smilies', 'add_img', true); return false;\">(";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("MARK_ALL");
                    echo ")</a></td>
\t";
                }
                // line 102
                echo "\t</tr>
\t</thead>
\t<tbody>
\t";
                // line 105
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "items", [], "any", false, false, false, 105));
                foreach ($context['_seq'] as $context["_key"] => $context["items"]) {
                    // line 106
                    echo "\t\t<tr>

\t\t<td style=\"text-align: center;\"><img src=\"";
                    // line 108
                    echo twig_get_attribute($this->env, $this->source, $context["items"], "IMG_SRC", [], "any", false, false, false, 108);
                    echo "\" alt=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["items"], "TEXT_ALT", [], "any", false, false, false, 108);
                    echo "\" title=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["items"], "TEXT_ALT", [], "any", false, false, false, 108);
                    echo "\" style=\"max-width: 160px;\"><input type=\"hidden\" name=\"image[";
                    echo twig_get_attribute($this->env, $this->source, $context["items"], "IMG", [], "any", false, false, false, 108);
                    echo "]\" value=\"1\" /></td>
\t\t<td style=\"vertical-align: top;\">[";
                    // line 109
                    echo twig_get_attribute($this->env, $this->source, $context["items"], "IMG", [], "any", false, false, false, 109);
                    echo "]</td>
\t\t";
                    // line 110
                    if (($context["S_SMILIES"] ?? null)) {
                        // line 111
                        echo "\t\t\t<td><input class=\"text post\" type=\"text\" name=\"code[";
                        echo twig_get_attribute($this->env, $this->source, $context["items"], "IMG", [], "any", false, false, false, 111);
                        echo "]\" value=\"";
                        echo twig_get_attribute($this->env, $this->source, $context["items"], "CODE", [], "any", false, false, false, 111);
                        echo "\" size=\"10\" maxlength=\"50\" /></td>
\t\t\t<td><input class=\"text post\" type=\"text\" name=\"emotion[";
                        // line 112
                        echo twig_get_attribute($this->env, $this->source, $context["items"], "IMG", [], "any", false, false, false, 112);
                        echo "]\" value=\"";
                        echo twig_get_attribute($this->env, $this->source, $context["items"], "EMOTION", [], "any", false, false, false, 112);
                        echo "\" size=\"10\" maxlength=\"50\" /></td>
\t\t";
                    }
                    // line 114
                    echo "\t\t<td><input class=\"text post\" type=\"number\" min=\"0\" max=\"999\" name=\"width[";
                    echo twig_get_attribute($this->env, $this->source, $context["items"], "IMG", [], "any", false, false, false, 114);
                    echo "]\" value=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["items"], "WIDTH", [], "any", false, false, false, 114);
                    echo "\" /></td>
\t\t<td><input class=\"text post\" type=\"number\" min=\"0\" max=\"999\" name=\"height[";
                    // line 115
                    echo twig_get_attribute($this->env, $this->source, $context["items"], "IMG", [], "any", false, false, false, 115);
                    echo "]\" value=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["items"], "HEIGHT", [], "any", false, false, false, 115);
                    echo "\" /></td>
\t\t";
                    // line 116
                    if ( !($context["S_SMILIES"] ?? null)) {
                        // line 117
                        echo "\t\t\t<td><input class=\"text post\" type=\"text\" name=\"alt[";
                        echo twig_get_attribute($this->env, $this->source, $context["items"], "IMG", [], "any", false, false, false, 117);
                        echo "]\" value=\"";
                        echo twig_get_attribute($this->env, $this->source, $context["items"], "ALT", [], "any", false, false, false, 117);
                        echo "\" size=\"10\" maxlength=\"50\" /></td>
\t\t";
                    }
                    // line 119
                    echo "\t\t<td>
\t\t\t<input type=\"checkbox\" class=\"radio\" name=\"display_on_posting[";
                    // line 120
                    echo twig_get_attribute($this->env, $this->source, $context["items"], "IMG", [], "any", false, false, false, 120);
                    echo "]\"";
                    echo twig_get_attribute($this->env, $this->source, $context["items"], "POSTING_CHECKED", [], "any", false, false, false, 120);
                    echo " onclick=\"toggle_select('";
                    echo twig_get_attribute($this->env, $this->source, $context["items"], "A_IMG", [], "any", false, false, false, 120);
                    echo "', this.checked, '";
                    echo twig_get_attribute($this->env, $this->source, $context["items"], "S_ROW_COUNT", [], "any", false, false, false, 120);
                    echo "');\"/>
\t\t\t";
                    // line 121
                    if (twig_get_attribute($this->env, $this->source, $context["items"], "S_ID", [], "any", false, false, false, 121)) {
                        // line 122
                        echo "\t\t\t\t<input type=\"hidden\" name=\"id[";
                        echo twig_get_attribute($this->env, $this->source, $context["items"], "IMG", [], "any", false, false, false, 122);
                        echo "]\" value=\"";
                        echo twig_get_attribute($this->env, $this->source, $context["items"], "ID", [], "any", false, false, false, 122);
                        echo "\" />
\t\t\t";
                    }
                    // line 124
                    echo "\t\t</td>
\t\t";
                    // line 125
                    if ((($context["ID"] ?? null) || ($context["S_ADD"] ?? null))) {
                        // line 126
                        echo "\t\t\t<td><select id=\"order_";
                        echo twig_get_attribute($this->env, $this->source, $context["items"], "S_ROW_COUNT", [], "any", false, false, false, 126);
                        echo "\" name=\"order[";
                        echo twig_get_attribute($this->env, $this->source, $context["items"], "IMG", [], "any", false, false, false, 126);
                        echo "]\">
\t\t\t\t<optgroup id=\"order_disp_";
                        // line 127
                        echo twig_get_attribute($this->env, $this->source, $context["items"], "S_ROW_COUNT", [], "any", false, false, false, 127);
                        echo "\" label=\"";
                        echo $this->extensions['phpbb\template\twig\extension']->lang("DISPLAY_POSTING");
                        echo "\" ";
                        if ( !twig_get_attribute($this->env, $this->source, $context["items"], "POSTING_CHECKED", [], "any", false, false, false, 127)) {
                            echo "disabled=\"disabled\" class=\"disabled-options\" ";
                        }
                        echo ">";
                        echo ($context["S_ORDER_LIST_DISPLAY"] ?? null);
                        echo "</optgroup>
\t\t\t\t<optgroup id=\"order_no_disp_";
                        // line 128
                        echo twig_get_attribute($this->env, $this->source, $context["items"], "S_ROW_COUNT", [], "any", false, false, false, 128);
                        echo "\" label=\"";
                        echo $this->extensions['phpbb\template\twig\extension']->lang("DISPLAY_POSTING_NO");
                        echo "\" ";
                        if (twig_get_attribute($this->env, $this->source, $context["items"], "POSTING_CHECKED", [], "any", false, false, false, 128)) {
                            echo "disabled=\"disabled\" class=\"disabled-options\" ";
                        }
                        echo ">";
                        echo ($context["S_ORDER_LIST_UNDISPLAY"] ?? null);
                        echo "</optgroup>
\t\t\t</select></td>
\t\t";
                    }
                    // line 131
                    echo "\t\t";
                    if (($context["S_ADD"] ?? null)) {
                        // line 132
                        echo "\t\t\t<td><input type=\"checkbox\" class=\"radio\" name=\"add_img[";
                        echo twig_get_attribute($this->env, $this->source, $context["items"], "IMG", [], "any", false, false, false, 132);
                        echo "]\" value=\"1\" /></td>
\t\t";
                    }
                    // line 134
                    echo "\t\t</tr>
\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['items'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 136
                echo "\t";
                if (($context["S_ADD_CODE"] ?? null)) {
                    // line 137
                    echo "\t<tr>
\t\t<th colspan=\"";
                    // line 138
                    echo ($context["COLSPAN"] ?? null);
                    echo "\">";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("ADD_SMILEY_CODE");
                    echo "</th>
\t</tr>
\t<tr class=\"row1\">
\t\t<td style=\"text-align: center;\"><select name=\"add_image\" id=\"add_image\" onchange=\"update_image(this.options[selectedIndex].value);\">";
                    // line 141
                    echo ($context["S_IMG_OPTIONS"] ?? null);
                    echo "</select></td>
\t\t<td style=\"vertical-align: top;\"><img src=\"";
                    // line 142
                    echo ($context["IMG_SRC"] ?? null);
                    echo "\" id=\"add_image_src\" alt=\"\" title=\"\" /></td>
\t\t<td><input class=\"text post\" type=\"text\" name=\"add_code\" id=\"add_code\" value=\"";
                    // line 143
                    echo ($context["CODE"] ?? null);
                    echo "\" size=\"10\" maxlength=\"50\" /></td>
\t\t<td><input class=\"text post\" type=\"text\" name=\"add_emotion\" id=\"add_emotion\" value=\"";
                    // line 144
                    echo ($context["EMOTION"] ?? null);
                    echo "\" size=\"10\" maxlength=\"50\" /></td>
\t\t<td><input class=\"text post\" type=\"number\" min=\"0\" max=\"999\" name=\"add_width\" id=\"add_width\" value=\"";
                    // line 145
                    echo ($context["WIDTH"] ?? null);
                    echo "\" /></td>
\t\t<td><input class=\"text post\" type=\"number\" min=\"0\" max=\"999\" name=\"add_height\" id=\"add_height\" value=\"";
                    // line 146
                    echo ($context["HEIGHT"] ?? null);
                    echo "\" /></td>
\t\t<td><input type=\"checkbox\" class=\"radio\" name=\"add_display_on_posting\" checked=\"checked\" onclick=\"toggle_select('add', this.checked, 'add_order');\"/></td>
 \t\t<td><select id=\"order_add_order\" name=\"add_order\">
\t\t\t\t<optgroup id=\"order_disp_add_order\" label=\"";
                    // line 149
                    echo $this->extensions['phpbb\template\twig\extension']->lang("DISPLAY_POSTING");
                    echo "\">";
                    echo ($context["S_ADD_ORDER_LIST_DISPLAY"] ?? null);
                    echo "</optgroup>
\t\t\t\t<optgroup id=\"order_no_disp_add_order\" label=\"";
                    // line 150
                    echo $this->extensions['phpbb\template\twig\extension']->lang("DISPLAY_POSTING_NO");
                    echo "\" disabled=\"disabled\" class=\"disabled-options\" >";
                    echo ($context["S_ADD_ORDER_LIST_UNDISPLAY"] ?? null);
                    echo "</optgroup>
\t\t</select></td>
\t\t<td><input type=\"checkbox\" class=\"radio\" name=\"add_additional_code\" value=\"1\" /></td>
\t</tr>
\t";
                }
                // line 155
                echo "\t";
            } else {
                // line 156
                echo "\t</thead>
\t<tbody>
\t<tr class=\"row3\">
\t\t<td colspan=\"";
                // line 159
                echo ($context["COLSPAN"] ?? null);
                echo "\">";
                echo $this->extensions['phpbb\template\twig\extension']->lang("NO_ICONS");
                echo "</td>
\t</tr>
\t";
            }
            // line 162
            echo "\t</tbody>
\t</table>

\t<p class=\"submit-buttons\">
\t\t<input class=\"button1\" type=\"submit\" id=\"submit\" name=\"submit\" value=\"";
            // line 166
            echo $this->extensions['phpbb\template\twig\extension']->lang("SUBMIT");
            echo "\" />&nbsp;
\t\t<input class=\"button2\" type=\"reset\" id=\"reset\" name=\"reset\" value=\"";
            // line 167
            echo $this->extensions['phpbb\template\twig\extension']->lang("RESET");
            echo "\" />
\t</p>
\t";
            // line 169
            echo ($context["S_FORM_TOKEN"] ?? null);
            echo "
\t</fieldset>
\t</form>

";
        } elseif (        // line 173
($context["S_CHOOSE_PAK"] ?? null)) {
            // line 174
            echo "
\t<a href=\"";
            // line 175
            echo ($context["U_BACK"] ?? null);
            echo "\" style=\"float: ";
            echo ($context["S_CONTENT_FLOW_END"] ?? null);
            echo ";\">&laquo; ";
            echo $this->extensions['phpbb\template\twig\extension']->lang("BACK");
            echo "</a>

\t<h1>";
            // line 177
            echo $this->extensions['phpbb\template\twig\extension']->lang("TITLE");
            echo "</h1>

\t<p>";
            // line 179
            echo $this->extensions['phpbb\template\twig\extension']->lang("EXPLAIN");
            echo "</p>

\t<form id=\"acp_icons\" method=\"post\" action=\"";
            // line 181
            echo ($context["U_ACTION"] ?? null);
            echo "\">

\t<fieldset>
\t\t<legend>";
            // line 184
            echo $this->extensions['phpbb\template\twig\extension']->lang("IMPORT");
            echo "</legend>

\t";
            // line 186
            if ( !($context["S_PAK_OPTIONS"] ?? null)) {
                // line 187
                echo "\t\t<p>";
                echo $this->extensions['phpbb\template\twig\extension']->lang("NO_PAK_OPTIONS");
                echo "</p>

\t";
            } else {
                // line 190
                echo "\t\t<dl>
\t\t\t<dt><label for=\"pak\">";
                // line 191
                echo $this->extensions['phpbb\template\twig\extension']->lang("SELECT_PACKAGE");
                echo "</label></dt>
\t\t\t<dd><select id=\"pak\" name=\"pak\">";
                // line 192
                echo ($context["S_PAK_OPTIONS"] ?? null);
                echo "</select></dd>
\t\t</dl>
\t\t\t<dt><label for=\"current\">";
                // line 194
                echo $this->extensions['phpbb\template\twig\extension']->lang("CURRENT");
                echo "</label><br /><span>";
                echo $this->extensions['phpbb\template\twig\extension']->lang("CURRENT_EXPLAIN");
                echo "</span></dt>
\t\t\t<dd><label><input type=\"radio\" class=\"radio\" id=\"current\" name=\"current\" value=\"keep\" checked=\"checked\" /> ";
                // line 195
                echo $this->extensions['phpbb\template\twig\extension']->lang("KEEP_ALL");
                echo "</label>
\t\t\t\t<label><input type=\"radio\" class=\"radio\" name=\"current\" value=\"replace\" /> ";
                // line 196
                echo $this->extensions['phpbb\template\twig\extension']->lang("REPLACE_MATCHES");
                echo "</label>
\t\t\t\t<label><input type=\"radio\" class=\"radio\" name=\"current\" value=\"delete\" /> ";
                // line 197
                echo $this->extensions['phpbb\template\twig\extension']->lang("DELETE_ALL");
                echo "</label></dd>
\t\t</dl>

\t<p class=\"quick\">
\t\t<input class=\"button1\" type=\"submit\" id=\"import\" name=\"import\" value=\"";
                // line 201
                echo $this->extensions['phpbb\template\twig\extension']->lang("IMPORT_SUBMIT");
                echo "\" />
\t</p>
\t";
            }
            // line 204
            echo "\t";
            echo ($context["S_FORM_TOKEN"] ?? null);
            echo "
\t</fieldset>
\t</form>

";
        } else {
            // line 209
            echo "
\t<h1>";
            // line 210
            echo $this->extensions['phpbb\template\twig\extension']->lang("TITLE");
            echo "</h1>

\t<p>";
            // line 212
            echo $this->extensions['phpbb\template\twig\extension']->lang("EXPLAIN");
            echo "</p>

\t";
            // line 214
            if (($context["NOTICE"] ?? null)) {
                // line 215
                echo "\t\t<div class=\"successbox\">
\t\t\t<h3>";
                // line 216
                echo $this->extensions['phpbb\template\twig\extension']->lang("NOTIFY");
                echo "</h3>
\t\t\t<p>";
                // line 217
                echo ($context["NOTICE"] ?? null);
                echo "</p>
\t\t</div>
\t";
            }
            // line 220
            echo "
\t<form id=\"acp_icons\" method=\"post\" action=\"";
            // line 221
            echo ($context["U_ACTION"] ?? null);
            echo "\">

\t<div style=\"text-align: right;\"><a href=\"";
            // line 223
            echo ($context["U_IMPORT"] ?? null);
            echo "\">";
            echo $this->extensions['phpbb\template\twig\extension']->lang("IMPORT");
            echo "</a> | <a href=\"";
            echo ($context["U_EXPORT"] ?? null);
            echo "\">";
            echo $this->extensions['phpbb\template\twig\extension']->lang("EXPORT");
            echo "</a></div>

\t<fieldset class=\"tabulated\">

\t<legend>";
            // line 227
            echo $this->extensions['phpbb\template\twig\extension']->lang("TITLE");
            echo "</legend>

\t<table class=\"table1 zebra-table\">
\t<thead>
\t<tr>
\t\t<th>";
            // line 232
            echo $this->extensions['phpbb\template\twig\extension']->lang("TITLE");
            echo "</th>
\t\t";
            // line 233
            if (($context["S_SMILIES"] ?? null)) {
                // line 234
                echo "\t\t<th>";
                echo $this->extensions['phpbb\template\twig\extension']->lang("CODE");
                echo "</th>
\t\t<th>";
                // line 235
                echo $this->extensions['phpbb\template\twig\extension']->lang("EMOTION");
                echo "</th>
\t\t";
            }
            // line 237
            echo "\t\t<th>";
            echo $this->extensions['phpbb\template\twig\extension']->lang("OPTIONS");
            echo "</th>
\t</tr>
\t</thead>
\t<tbody>
\t";
            // line 241
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "items", [], "any", false, false, false, 241));
            $context['_iterated'] = false;
            foreach ($context['_seq'] as $context["_key"] => $context["items"]) {
                // line 242
                echo "\t\t";
                if (twig_get_attribute($this->env, $this->source, $context["items"], "S_SPACER", [], "any", false, false, false, 242)) {
                    // line 243
                    echo "\t\t\t<tr>
\t\t\t\t<td class=\"row3\" colspan=\"";
                    // line 244
                    echo ($context["COLSPAN"] ?? null);
                    echo "\" style=\"text-align: center;\">";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("NOT_DISPLAYED");
                    echo "</td>
\t\t\t</tr>
\t\t";
                }
                // line 247
                echo "\t\t<tr>
\t\t\t<td style=\"width: 85%; text-align: center;\"><img src=\"";
                // line 248
                echo twig_get_attribute($this->env, $this->source, $context["items"], "IMG_SRC", [], "any", false, false, false, 248);
                echo "\" width=\"";
                echo twig_get_attribute($this->env, $this->source, $context["items"], "WIDTH", [], "any", false, false, false, 248);
                echo "\" height=\"";
                echo twig_get_attribute($this->env, $this->source, $context["items"], "HEIGHT", [], "any", false, false, false, 248);
                echo "\" alt=\"";
                echo twig_get_attribute($this->env, $this->source, $context["items"], "ALT_TEXT", [], "any", false, false, false, 248);
                echo "\" title=\"";
                echo twig_get_attribute($this->env, $this->source, $context["items"], "ALT_TEXT", [], "any", false, false, false, 248);
                echo "\" /></td>
\t\t\t";
                // line 249
                if (($context["S_SMILIES"] ?? null)) {
                    // line 250
                    echo "\t\t\t\t<td style=\"text-align: center;\">";
                    echo twig_get_attribute($this->env, $this->source, $context["items"], "CODE", [], "any", false, false, false, 250);
                    echo "</td>
\t\t\t\t<td style=\"text-align: center;\">";
                    // line 251
                    echo twig_get_attribute($this->env, $this->source, $context["items"], "EMOTION", [], "any", false, false, false, 251);
                    echo "</td>
\t\t\t";
                }
                // line 253
                echo "\t\t\t<td class=\"actions\" style=\"text-align: right;\">
\t\t\t\t<span class=\"up-disabled\" style=\"display:none;\">";
                // line 254
                echo ($context["ICON_MOVE_UP_DISABLED"] ?? null);
                echo "</span>
\t\t\t\t<span class=\"up\"><a href=\"";
                // line 255
                echo twig_get_attribute($this->env, $this->source, $context["items"], "U_MOVE_UP", [], "any", false, false, false, 255);
                echo "\" data-ajax=\"row_up\">";
                echo ($context["ICON_MOVE_UP"] ?? null);
                echo "</a></span>
\t\t\t\t<span class=\"down-disabled\" style=\"display:none;\">";
                // line 256
                echo ($context["ICON_MOVE_DOWN_DISABLED"] ?? null);
                echo "</span>
\t\t\t\t<span class=\"down\"><a href=\"";
                // line 257
                echo twig_get_attribute($this->env, $this->source, $context["items"], "U_MOVE_DOWN", [], "any", false, false, false, 257);
                echo "\" data-ajax=\"row_down\">";
                echo ($context["ICON_MOVE_DOWN"] ?? null);
                echo "</a></span>
\t\t\t\t<a href=\"";
                // line 258
                echo twig_get_attribute($this->env, $this->source, $context["items"], "U_EDIT", [], "any", false, false, false, 258);
                echo "\">";
                echo ($context["ICON_EDIT"] ?? null);
                echo "</a> <a href=\"";
                echo twig_get_attribute($this->env, $this->source, $context["items"], "U_DELETE", [], "any", false, false, false, 258);
                echo "\" data-ajax=\"row_delete\">";
                echo ($context["ICON_DELETE"] ?? null);
                echo "</a>
\t\t\t</td>
\t\t</tr>
\t";
                $context['_iterated'] = true;
            }
            if (!$context['_iterated']) {
                // line 262
                echo "\t\t<tr class=\"row3\">
\t\t\t<td colspan=\"";
                // line 263
                echo ($context["COLSPAN"] ?? null);
                echo "\">";
                echo $this->extensions['phpbb\template\twig\extension']->lang("ACP_NO_ITEMS");
                echo "</td>
\t\t</tr>
\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['items'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 266
            echo "\t</tbody>
\t</table>
\t<div class=\"pagination\">
\t";
            // line 269
            if (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "pagination", [], "any", false, false, false, 269))) {
                // line 270
                echo "\t\t";
                $location = "pagination.html";
                $namespace = false;
                if (strpos($location, '@') === 0) {
                    $namespace = substr($location, 1, strpos($location, '/') - 1);
                    $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                    $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
                }
                $this->loadTemplate("pagination.html", "acp_icons.html", 270)->display($context);
                if ($namespace) {
                    $this->env->setNamespaceLookUpOrder($previous_look_up_order);
                }
                // line 271
                echo "\t";
            }
            // line 272
            echo "\t</div>
\t<p class=\"quick\">
\t\t<input class=\"button2\" name=\"add\" type=\"submit\" value=\"";
            // line 274
            echo $this->extensions['phpbb\template\twig\extension']->lang("ICON_ADD");
            echo "\" />&nbsp; &nbsp;<input class=\"button2\" type=\"submit\" name=\"edit\" value=\"";
            echo $this->extensions['phpbb\template\twig\extension']->lang("ICON_EDIT");
            echo "\" />
\t</p>
\t";
            // line 276
            echo ($context["S_FORM_TOKEN"] ?? null);
            echo "
\t</fieldset>
\t</form>

";
        }
        // line 281
        echo "
";
        // line 282
        $location = "overall_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("overall_footer.html", "acp_icons.html", 282)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "acp_icons.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  826 => 282,  823 => 281,  815 => 276,  808 => 274,  804 => 272,  801 => 271,  788 => 270,  786 => 269,  781 => 266,  770 => 263,  767 => 262,  752 => 258,  746 => 257,  742 => 256,  736 => 255,  732 => 254,  729 => 253,  724 => 251,  719 => 250,  717 => 249,  705 => 248,  702 => 247,  694 => 244,  691 => 243,  688 => 242,  683 => 241,  675 => 237,  670 => 235,  665 => 234,  663 => 233,  659 => 232,  651 => 227,  638 => 223,  633 => 221,  630 => 220,  624 => 217,  620 => 216,  617 => 215,  615 => 214,  610 => 212,  605 => 210,  602 => 209,  593 => 204,  587 => 201,  580 => 197,  576 => 196,  572 => 195,  566 => 194,  561 => 192,  557 => 191,  554 => 190,  547 => 187,  545 => 186,  540 => 184,  534 => 181,  529 => 179,  524 => 177,  515 => 175,  512 => 174,  510 => 173,  503 => 169,  498 => 167,  494 => 166,  488 => 162,  480 => 159,  475 => 156,  472 => 155,  462 => 150,  456 => 149,  450 => 146,  446 => 145,  442 => 144,  438 => 143,  434 => 142,  430 => 141,  422 => 138,  419 => 137,  416 => 136,  409 => 134,  403 => 132,  400 => 131,  386 => 128,  374 => 127,  367 => 126,  365 => 125,  362 => 124,  354 => 122,  352 => 121,  342 => 120,  339 => 119,  331 => 117,  329 => 116,  323 => 115,  316 => 114,  309 => 112,  302 => 111,  300 => 110,  296 => 109,  286 => 108,  282 => 106,  278 => 105,  273 => 102,  265 => 100,  262 => 99,  256 => 97,  254 => 96,  249 => 95,  243 => 93,  241 => 92,  237 => 91,  232 => 90,  227 => 88,  222 => 87,  220 => 86,  216 => 85,  212 => 84,  209 => 83,  207 => 82,  200 => 80,  192 => 75,  186 => 72,  181 => 70,  176 => 68,  167 => 66,  156 => 58,  138 => 42,  117 => 25,  110 => 20,  100 => 18,  94 => 17,  88 => 16,  82 => 15,  76 => 14,  71 => 13,  67 => 12,  63 => 10,  61 => 9,  56 => 6,  54 => 5,  49 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "acp_icons.html", "");
    }
}
