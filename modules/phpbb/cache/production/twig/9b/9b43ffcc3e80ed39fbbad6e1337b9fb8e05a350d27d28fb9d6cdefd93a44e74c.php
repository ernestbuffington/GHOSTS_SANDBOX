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

/* viewonline_body.html */
class __TwigTemplate_e20b0511f34b566547a294a48e0b56a10c0f7a980a700df5e57aabbe6b767f37 extends \Twig\Template
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
        $this->loadTemplate("overall_header.html", "viewonline_body.html", 1)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<h2 class=\"viewonline-title\">";
        // line 3
        echo ($context["TOTAL_REGISTERED_USERS_ONLINE"] ?? null);
        echo "</h2>
<p>";
        // line 4
        echo ($context["TOTAL_GUEST_USERS_ONLINE"] ?? null);
        if (($context["S_SWITCH_GUEST_DISPLAY"] ?? null)) {
            echo " &bull; <a href=\"";
            echo ($context["U_SWITCH_GUEST_DISPLAY"] ?? null);
            echo "\">";
            echo $this->extensions['phpbb\template\twig\extension']->lang("SWITCH_GUEST_DISPLAY");
            echo "</a>";
        }
        echo "</p>

<div class=\"action-bar bar-top\">
\t<div class=\"pagination\">
\t\t";
        // line 8
        if (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "pagination", [], "any", false, false, false, 8))) {
            // line 9
            echo "\t\t\t";
            $location = "pagination.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->loadTemplate("pagination.html", "viewonline_body.html", 9)->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
            // line 10
            echo "\t\t";
        } else {
            // line 11
            echo "\t\t\t";
            echo ($context["PAGE_NUMBER"] ?? null);
            echo "
\t\t";
        }
        // line 13
        echo "\t</div>
</div>

<div class=\"forumbg forumbg-table\">
\t<div class=\"inner\">

\t<table class=\"table1\">

\t";
        // line 21
        if (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "user_row", [], "any", false, false, false, 21))) {
            // line 22
            echo "\t\t<thead>
\t\t<tr>
\t\t\t<th class=\"name\"><a href=\"";
            // line 24
            echo ($context["U_SORT_USERNAME"] ?? null);
            echo "\">";
            echo $this->extensions['phpbb\template\twig\extension']->lang("USERNAME");
            echo "</a></th>
\t\t\t<th class=\"info\"><a href=\"";
            // line 25
            echo ($context["U_SORT_LOCATION"] ?? null);
            echo "\">";
            echo $this->extensions['phpbb\template\twig\extension']->lang("FORUM_LOCATION");
            echo "</a></th>
\t\t\t<th class=\"active\"><a href=\"";
            // line 26
            echo ($context["U_SORT_UPDATED"] ?? null);
            echo "\">";
            echo $this->extensions['phpbb\template\twig\extension']->lang("LAST_UPDATED");
            echo "</a></th>
\t\t</tr>
\t\t</thead>
\t\t<tbody>
\t\t";
            // line 30
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "user_row", [], "any", false, false, false, 30));
            foreach ($context['_seq'] as $context["_key"] => $context["user_row"]) {
                // line 31
                echo "\t\t<tr class=\"";
                if ((twig_get_attribute($this->env, $this->source, $context["user_row"], "S_ROW_COUNT", [], "any", false, false, false, 31) % 2 != 0)) {
                    echo "bg1";
                } else {
                    echo "bg2";
                }
                echo "\">
\t\t\t<td>";
                // line 32
                echo twig_get_attribute($this->env, $this->source, $context["user_row"], "USERNAME_FULL", [], "any", false, false, false, 32);
                if (twig_get_attribute($this->env, $this->source, $context["user_row"], "USER_IP", [], "any", false, false, false, 32)) {
                    echo " <span style=\"float: ";
                    echo ($context["S_CONTENT_FLOW_END"] ?? null);
                    echo ";\">";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("IP");
                    echo $this->extensions['phpbb\template\twig\extension']->lang("COLON");
                    echo " <a href=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["user_row"], "U_USER_IP", [], "any", false, false, false, 32);
                    echo "\">";
                    echo twig_get_attribute($this->env, $this->source, $context["user_row"], "USER_IP", [], "any", false, false, false, 32);
                    echo "</a> &#187; <a href=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["user_row"], "U_WHOIS", [], "any", false, false, false, 32);
                    echo "\" onclick=\"popup(this.href, 750, 500); return false;\">";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("WHOIS");
                    echo "</a></span>";
                }
                // line 33
                echo "\t\t\t\t";
                if (twig_get_attribute($this->env, $this->source, $context["user_row"], "USER_BROWSER", [], "any", false, false, false, 33)) {
                    echo "<br />";
                    echo twig_get_attribute($this->env, $this->source, $context["user_row"], "USER_BROWSER", [], "any", false, false, false, 33);
                }
                echo "</td>
\t\t\t<td class=\"info\"><a href=\"";
                // line 34
                echo twig_get_attribute($this->env, $this->source, $context["user_row"], "U_FORUM_LOCATION", [], "any", false, false, false, 34);
                echo "\">";
                echo twig_get_attribute($this->env, $this->source, $context["user_row"], "FORUM_LOCATION", [], "any", false, false, false, 34);
                echo "</a></td>
\t\t\t<td class=\"active\">";
                // line 35
                echo twig_get_attribute($this->env, $this->source, $context["user_row"], "LASTUPDATE", [], "any", false, false, false, 35);
                echo "</td>
\t\t</tr>
\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['user_row'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 38
            echo "\t";
        } else {
            // line 39
            echo "\t\t<tbody>
\t\t<tr class=\"bg1\">
\t\t\t<td colspan=\"3\">";
            // line 41
            echo $this->extensions['phpbb\template\twig\extension']->lang("NO_ONLINE_USERS");
            if (($context["S_SWITCH_GUEST_DISPLAY"] ?? null)) {
                echo " &bull; <a href=\"";
                echo ($context["U_SWITCH_GUEST_DISPLAY"] ?? null);
                echo "\">";
                echo $this->extensions['phpbb\template\twig\extension']->lang("SWITCH_GUEST_DISPLAY");
                echo "</a>";
            }
            echo "</td>
\t\t</tr>
\t";
        }
        // line 44
        echo "\t</tbody>
\t</table>

\t</div>
</div>

";
        // line 50
        if (($context["LEGEND"] ?? null)) {
            echo "<p><em>";
            echo $this->extensions['phpbb\template\twig\extension']->lang("LEGEND");
            echo $this->extensions['phpbb\template\twig\extension']->lang("COLON");
            echo " ";
            echo ($context["LEGEND"] ?? null);
            echo "</em></p>";
        }
        // line 51
        echo "
<div class=\"action-bar bar-bottom\">
\t<div class=\"pagination\">
\t\t";
        // line 54
        if (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "pagination", [], "any", false, false, false, 54))) {
            // line 55
            echo "\t\t\t";
            $location = "pagination.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->loadTemplate("pagination.html", "viewonline_body.html", 55)->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
            // line 56
            echo "\t\t";
        } else {
            // line 57
            echo "\t\t\t";
            echo ($context["PAGE_NUMBER"] ?? null);
            echo "
\t\t";
        }
        // line 59
        echo "\t</div>
</div>

";
        // line 62
        $location = "jumpbox.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("jumpbox.html", "viewonline_body.html", 62)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 63
        $location = "overall_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("overall_footer.html", "viewonline_body.html", 63)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "viewonline_body.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  268 => 63,  256 => 62,  251 => 59,  245 => 57,  242 => 56,  229 => 55,  227 => 54,  222 => 51,  213 => 50,  205 => 44,  192 => 41,  188 => 39,  185 => 38,  176 => 35,  170 => 34,  162 => 33,  144 => 32,  135 => 31,  131 => 30,  122 => 26,  116 => 25,  110 => 24,  106 => 22,  104 => 21,  94 => 13,  88 => 11,  85 => 10,  72 => 9,  70 => 8,  56 => 4,  52 => 3,  49 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "viewonline_body.html", "");
    }
}
