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

/* ucp_notifications.html */
class __TwigTemplate_c9f5e82344ede35d11e4a49f79af844e73ad712ef227da754282d6a85aaf75c7 extends \Twig\Template
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
        $location = "ucp_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("ucp_header.html", "ucp_notifications.html", 1)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<form id=\"ucp\" method=\"post\" action=\"";
        // line 3
        echo ($context["S_UCP_ACTION"] ?? null);
        echo "\"";
        echo ($context["S_FORM_ENCTYPE"] ?? null);
        echo ">

<h2>";
        // line 5
        echo ($context["TITLE"] ?? null);
        echo "</h2>
<div class=\"panel\">
\t<div class=\"inner\">

\t\t<p>";
        // line 9
        echo ($context["TITLE_EXPLAIN"] ?? null);
        echo "</p>

\t\t";
        // line 11
        if ((($context["MODE"] ?? null) == "notification_options")) {
            // line 12
            echo "\t\t\t<table class=\"table1\">
\t\t\t\t<thead>
\t\t\t\t\t<tr>
\t\t\t\t\t\t<th>";
            // line 15
            echo $this->extensions['phpbb\template\twig\extension']->lang("NOTIFICATION_TYPE");
            echo "</th>
\t\t\t\t\t\t";
            // line 16
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "notification_methods", [], "any", false, false, false, 16));
            foreach ($context['_seq'] as $context["_key"] => $context["notification_methods"]) {
                // line 17
                echo "\t\t\t\t\t\t\t<th class=\"mark\">";
                echo twig_get_attribute($this->env, $this->source, $context["notification_methods"], "NAME", [], "any", false, false, false, 17);
                echo "</th>
\t\t\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notification_methods'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 19
            echo "\t\t\t\t\t</tr>
\t\t\t\t</thead>
\t\t\t\t<tbody>
\t\t\t\t";
            // line 22
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "notification_types", [], "any", false, false, false, 22));
            foreach ($context['_seq'] as $context["_key"] => $context["notification_types"]) {
                // line 23
                echo "\t\t\t\t\t";
                if (twig_get_attribute($this->env, $this->source, $context["notification_types"], "GROUP_NAME", [], "any", false, false, false, 23)) {
                    // line 24
                    echo "\t\t\t\t\t\t<tr class=\"bg3\">
\t\t\t\t\t\t\t<td colspan=\"";
                    // line 25
                    echo ($context["NOTIFICATION_TYPES_COLS"] ?? null);
                    echo "\">";
                    echo twig_get_attribute($this->env, $this->source, $context["notification_types"], "GROUP_NAME", [], "any", false, false, false, 25);
                    echo "</td>
\t\t\t\t\t\t</tr>
\t\t\t\t\t";
                } else {
                    // line 28
                    echo "\t\t\t\t\t\t<tr class=\"";
                    if ((twig_get_attribute($this->env, $this->source, $context["notification_types"], "S_ROW_COUNT", [], "any", false, false, false, 28) % 2 != 0)) {
                        echo "bg1";
                    } else {
                        echo "bg2";
                    }
                    echo "\">
\t\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t\t";
                    // line 30
                    echo twig_get_attribute($this->env, $this->source, $context["notification_types"], "NAME", [], "any", false, false, false, 30);
                    echo "
\t\t\t\t\t\t\t\t";
                    // line 31
                    if (twig_get_attribute($this->env, $this->source, $context["notification_types"], "EXPLAIN", [], "any", false, false, false, 31)) {
                        echo "<br />&nbsp; &nbsp;";
                        echo twig_get_attribute($this->env, $this->source, $context["notification_types"], "EXPLAIN", [], "any", false, false, false, 31);
                    }
                    // line 32
                    echo "\t\t\t\t\t\t\t</td>
\t\t\t\t\t\t\t";
                    // line 33
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["notification_types"], "notification_methods", [], "any", false, false, false, 33));
                    foreach ($context['_seq'] as $context["_key"] => $context["notification_methods"]) {
                        // line 34
                        echo "\t\t\t\t\t\t\t\t<td class=\"mark\"><input type=\"checkbox\" name=\"";
                        echo twig_get_attribute($this->env, $this->source, $context["notification_types"], "TYPE", [], "any", false, false, false, 34);
                        echo "_";
                        echo twig_get_attribute($this->env, $this->source, $context["notification_methods"], "METHOD", [], "any", false, false, false, 34);
                        echo "\"";
                        if (twig_get_attribute($this->env, $this->source, $context["notification_methods"], "SUBSCRIBED", [], "any", false, false, false, 34)) {
                            echo " checked=\"checked\"";
                        }
                        if ( !twig_get_attribute($this->env, $this->source, $context["notification_methods"], "AVAILABLE", [], "any", false, false, false, 34)) {
                            echo " disabled=\"disabled\"";
                        }
                        echo " /></td>
\t\t\t\t\t\t\t";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notification_methods'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 36
                    echo "\t\t\t\t\t\t</tr>
\t\t\t\t\t";
                }
                // line 38
                echo "\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notification_types'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 39
            echo "\t\t\t\t</tbody>
\t\t\t</table>
\t\t";
        } else {
            // line 42
            echo "\t\t\t";
            if (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "notification_list", [], "any", false, false, false, 42))) {
                // line 43
                echo "\t\t\t\t<div class=\"action-bar bar-top\">
\t\t\t\t\t<div class=\"pagination\">
\t\t\t\t\t\t";
                // line 45
                if (($context["U_MARK_ALL"] ?? null)) {
                    echo "<a href=\"";
                    echo ($context["U_MARK_ALL"] ?? null);
                    echo "\" class=\"mark\">";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("NOTIFICATIONS_MARK_ALL_READ");
                    echo "</a> &bull; ";
                }
                // line 46
                echo "\t\t\t\t\t\t";
                echo $this->extensions['phpbb\template\twig\extension']->lang("NOTIFICATIONS");
                echo " [<strong>";
                echo ($context["TOTAL_COUNT"] ?? null);
                echo "</strong>]
\t\t\t\t\t\t";
                // line 47
                if (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "pagination", [], "any", false, false, false, 47))) {
                    // line 48
                    echo "\t\t\t\t\t\t\t";
                    $location = "pagination.html";
                    $namespace = false;
                    if (strpos($location, '@') === 0) {
                        $namespace = substr($location, 1, strpos($location, '/') - 1);
                        $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                        $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
                    }
                    $this->loadTemplate("pagination.html", "ucp_notifications.html", 48)->display($context);
                    if ($namespace) {
                        $this->env->setNamespaceLookUpOrder($previous_look_up_order);
                    }
                    // line 49
                    echo "\t\t\t\t\t\t";
                } else {
                    // line 50
                    echo "\t\t\t\t\t\t\t &bull; ";
                    echo ($context["PAGE_NUMBER"] ?? null);
                    echo "
\t\t\t\t\t\t";
                }
                // line 52
                echo "\t\t\t\t\t</div>
\t\t\t\t</div>

\t\t\t<div class=\"notification_list\">
\t\t\t\t<ul class=\"topiclist two-columns\">
\t\t\t\t\t<li class=\"header\">
\t\t\t\t\t\t<dl>
\t\t\t\t\t\t\t<dt><div class=\"list-inner\">";
                // line 59
                echo $this->extensions['phpbb\template\twig\extension']->lang("NOTIFICATIONS");
                echo "</div></dt>
\t\t\t\t\t\t\t<dd class=\"mark\">";
                // line 60
                echo $this->extensions['phpbb\template\twig\extension']->lang("MARK_READ");
                echo "</dd>
\t\t\t\t\t\t</dl>
\t\t\t\t\t</li>
\t\t\t\t</ul>
\t\t\t\t<ul class=\"topiclist cplist two-columns\">
\t\t\t\t\t";
                // line 65
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "notification_list", [], "any", false, false, false, 65));
                foreach ($context['_seq'] as $context["_key"] => $context["notification_list"]) {
                    // line 66
                    echo "\t\t\t\t\t\t<li class=\"row";
                    if (twig_get_attribute($this->env, $this->source, $context["notification_list"], "UNREAD", [], "any", false, false, false, 66)) {
                        echo " bg3";
                    } else {
                        if ((twig_get_attribute($this->env, $this->source, $context["notification_list"], "S_ROW_COUNT", [], "any", false, false, false, 66) % 2 != 0)) {
                            echo " bg1";
                        } else {
                            echo " bg2";
                        }
                    }
                    if (twig_get_attribute($this->env, $this->source, $context["notification_list"], "STYLING", [], "any", false, false, false, 66)) {
                        echo " ";
                        echo twig_get_attribute($this->env, $this->source, $context["notification_list"], "STYLING", [], "any", false, false, false, 66);
                    }
                    echo "\">
\t\t\t\t\t\t\t<dl>
\t\t\t\t\t\t\t\t<dt>
\t\t\t\t\t\t\t\t\t<div class=\"list-inner\">
\t\t\t\t\t\t\t\t\t\t";
                    // line 70
                    if (twig_get_attribute($this->env, $this->source, $context["notification_list"], "AVATAR", [], "any", false, false, false, 70)) {
                        echo twig_get_attribute($this->env, $this->source, $context["notification_list"], "AVATAR", [], "any", false, false, false, 70);
                    } else {
                        echo "<img src=\"";
                        echo ($context["T_THEME_PATH"] ?? null);
                        echo "/images/no_avatar.gif\" alt=\"\" />";
                    }
                    // line 71
                    echo "\t\t\t\t\t\t\t\t\t\t<div class=\"notifications\">
\t\t\t\t\t\t\t\t\t\t\t";
                    // line 72
                    if (twig_get_attribute($this->env, $this->source, $context["notification_list"], "URL", [], "any", false, false, false, 72)) {
                        echo "<a href=\"";
                        if (twig_get_attribute($this->env, $this->source, $context["notification_list"], "UNREAD", [], "any", false, false, false, 72)) {
                            echo twig_get_attribute($this->env, $this->source, $context["notification_list"], "U_MARK_READ", [], "any", false, false, false, 72);
                        } else {
                            echo twig_get_attribute($this->env, $this->source, $context["notification_list"], "URL", [], "any", false, false, false, 72);
                        }
                        echo "\">";
                    }
                    // line 73
                    echo "\t\t\t\t\t\t\t\t\t\t\t<p class=\"notifications_title\">";
                    echo twig_get_attribute($this->env, $this->source, $context["notification_list"], "FORMATTED_TITLE", [], "any", false, false, false, 73);
                    if (twig_get_attribute($this->env, $this->source, $context["notification_list"], "REFERENCE", [], "any", false, false, false, 73)) {
                        echo " ";
                        echo twig_get_attribute($this->env, $this->source, $context["notification_list"], "REFERENCE", [], "any", false, false, false, 73);
                    }
                    echo "</p>
\t\t\t\t\t\t\t\t\t\t\t";
                    // line 74
                    if (twig_get_attribute($this->env, $this->source, $context["notification_list"], "URL", [], "any", false, false, false, 74)) {
                        echo "</a>";
                    }
                    // line 75
                    echo "\t\t\t\t\t\t\t\t\t\t\t";
                    if (twig_get_attribute($this->env, $this->source, $context["notification_list"], "FORUM", [], "any", false, false, false, 75)) {
                        echo "<p class=\"notifications_forum\">";
                        echo twig_get_attribute($this->env, $this->source, $context["notification_list"], "FORUM", [], "any", false, false, false, 75);
                        echo "</p>";
                    }
                    // line 76
                    echo "\t\t\t\t\t\t\t\t\t\t\t";
                    if (twig_get_attribute($this->env, $this->source, $context["notification_list"], "REASON", [], "any", false, false, false, 76)) {
                        echo "<p class=\"notifications_reason\">";
                        echo twig_get_attribute($this->env, $this->source, $context["notification_list"], "REASON", [], "any", false, false, false, 76);
                        echo "</p>";
                    }
                    // line 77
                    echo "\t\t\t\t\t\t\t\t\t\t\t<p class=\"notifications_time\">";
                    echo twig_get_attribute($this->env, $this->source, $context["notification_list"], "TIME", [], "any", false, false, false, 77);
                    echo "</p>
\t\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t</dt>

\t\t\t\t\t\t\t\t<dd class=\"mark\">&nbsp;<input type=\"checkbox\" name=\"mark[]\" value=\"";
                    // line 82
                    echo twig_get_attribute($this->env, $this->source, $context["notification_list"], "NOTIFICATION_ID", [], "any", false, false, false, 82);
                    echo "\"";
                    if ( !twig_get_attribute($this->env, $this->source, $context["notification_list"], "UNREAD", [], "any", false, false, false, 82)) {
                        echo " disabled=\"disabled\"";
                    }
                    echo " /> <dfn>";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("MARK_READ");
                    echo "</dfn>&nbsp;</dd>
\t\t\t\t\t\t\t</dl>
\t\t\t\t\t\t</li>
\t\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notification_list'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 86
                echo "\t\t\t\t</ul>
\t\t\t</div>

\t\t\t<div class=\"action-bar bar-bottom\">
\t\t\t\t<div class=\"pagination\">
\t\t\t\t\t";
                // line 91
                echo $this->extensions['phpbb\template\twig\extension']->lang("NOTIFICATIONS");
                echo " [<strong>";
                echo ($context["TOTAL_COUNT"] ?? null);
                echo "</strong>]
\t\t\t\t\t";
                // line 92
                if (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "pagination", [], "any", false, false, false, 92))) {
                    // line 93
                    echo "\t\t\t\t\t\t";
                    $location = "pagination.html";
                    $namespace = false;
                    if (strpos($location, '@') === 0) {
                        $namespace = substr($location, 1, strpos($location, '/') - 1);
                        $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                        $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
                    }
                    $this->loadTemplate("pagination.html", "ucp_notifications.html", 93)->display($context);
                    if ($namespace) {
                        $this->env->setNamespaceLookUpOrder($previous_look_up_order);
                    }
                    // line 94
                    echo "\t\t\t\t\t";
                } else {
                    // line 95
                    echo "\t\t\t\t\t\t  &bull; ";
                    echo ($context["PAGE_NUMBER"] ?? null);
                    echo "
\t\t\t\t\t";
                }
                // line 97
                echo "\t\t\t\t</div>
\t\t\t</div>

\t\t\t";
            } else {
                // line 101
                echo "\t\t\t\t<p><strong>";
                echo $this->extensions['phpbb\template\twig\extension']->lang("NO_NOTIFICATIONS");
                echo "</strong></p>
\t\t\t";
            }
            // line 103
            echo "
\t\t";
        }
        // line 105
        echo "\t</div>
</div>

";
        // line 108
        if ((twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "notification_types", [], "any", false, false, false, 108)) || twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "notification_list", [], "any", false, false, false, 108)))) {
            // line 109
            echo "<fieldset class=\"display-actions\">
\t<input type=\"hidden\" name=\"form_time\" value=\"";
            // line 110
            echo ($context["FORM_TIME"] ?? null);
            echo "\" />
\t";
            // line 111
            echo ($context["S_HIDDEN_FIELDS"] ?? null);
            echo "
\t<input type=\"submit\" name=\"submit\" value=\"";
            // line 112
            if ((($context["MODE"] ?? null) == "notification_options")) {
                echo $this->extensions['phpbb\template\twig\extension']->lang("SUBMIT");
            } else {
                echo $this->extensions['phpbb\template\twig\extension']->lang("MARK_READ");
            }
            echo "\" class=\"button1\" />
\t<div><a href=\"#\" onclick=\"\$('#ucp input:checkbox').prop('checked', true); return false;\">";
            // line 113
            echo $this->extensions['phpbb\template\twig\extension']->lang("MARK_ALL");
            echo "</a> &bull; <a href=\"#\" onclick=\"\$('#ucp input:checkbox').prop('checked', false); return false;\">";
            echo $this->extensions['phpbb\template\twig\extension']->lang("UNMARK_ALL");
            echo "</a></div>
\t";
            // line 114
            echo ($context["S_FORM_TOKEN"] ?? null);
            echo "
</fieldset>
";
        }
        // line 117
        echo "
</form>

";
        // line 120
        $location = "ucp_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("ucp_footer.html", "ucp_notifications.html", 120)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "ucp_notifications.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  437 => 120,  432 => 117,  426 => 114,  420 => 113,  412 => 112,  408 => 111,  404 => 110,  401 => 109,  399 => 108,  394 => 105,  390 => 103,  384 => 101,  378 => 97,  372 => 95,  369 => 94,  356 => 93,  354 => 92,  348 => 91,  341 => 86,  325 => 82,  316 => 77,  309 => 76,  302 => 75,  298 => 74,  289 => 73,  279 => 72,  276 => 71,  268 => 70,  248 => 66,  244 => 65,  236 => 60,  232 => 59,  223 => 52,  217 => 50,  214 => 49,  201 => 48,  199 => 47,  192 => 46,  184 => 45,  180 => 43,  177 => 42,  172 => 39,  166 => 38,  162 => 36,  144 => 34,  140 => 33,  137 => 32,  132 => 31,  128 => 30,  118 => 28,  110 => 25,  107 => 24,  104 => 23,  100 => 22,  95 => 19,  86 => 17,  82 => 16,  78 => 15,  73 => 12,  71 => 11,  66 => 9,  59 => 5,  52 => 3,  49 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "ucp_notifications.html", "");
    }
}
