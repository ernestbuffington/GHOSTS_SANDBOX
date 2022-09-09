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

/* mcp_front.html */
class __TwigTemplate_fec3b82e70bb4000ff2fafe13c7d93da9238f28e6a5f5811e419b2f409d5b302 extends \Twig\Template
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
        $location = "mcp_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("mcp_header.html", "mcp_front.html", 1)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<h2>";
        // line 3
        echo ($context["PAGE_TITLE"] ?? null);
        echo "</h2>

";
        // line 5
        // line 6
        echo "
";
        // line 7
        if (($context["S_SHOW_UNAPPROVED"] ?? null)) {
            // line 8
            echo "
\t<form id=\"mcp_queue\" method=\"post\" action=\"";
            // line 9
            echo ($context["S_MCP_QUEUE_ACTION"] ?? null);
            echo "\">

\t<div class=\"panel\">
\t\t<div class=\"inner\">

\t\t<h3>";
            // line 14
            echo $this->extensions['phpbb\template\twig\extension']->lang("LATEST_UNAPPROVED");
            echo "</h3>
\t\t<p>";
            // line 15
            echo $this->extensions['phpbb\template\twig\extension']->lang("UNAPPROVED_TOTAL");
            echo "</p>

\t\t";
            // line 17
            if (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "unapproved", [], "any", false, false, false, 17))) {
                // line 18
                echo "\t\t\t<ul class=\"topiclist missing-column\">
\t\t\t\t<li class=\"header\">
\t\t\t\t\t<dl>
\t\t\t\t\t\t<dt><div class=\"list-inner\">";
                // line 21
                echo $this->extensions['phpbb\template\twig\extension']->lang("VIEW_DETAILS");
                echo "</div></dt>
\t\t\t\t\t\t<dd class=\"moderation\"><span>";
                // line 22
                echo $this->extensions['phpbb\template\twig\extension']->lang("TOPIC");
                echo " &amp; ";
                echo $this->extensions['phpbb\template\twig\extension']->lang("FORUM");
                echo "</span></dd>
\t\t\t\t\t</dl>
\t\t\t\t</li>
\t\t\t</ul>
\t\t\t<ul class=\"topiclist cplist missing-column responsive-show-all\">

\t\t\t";
                // line 28
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "unapproved", [], "any", false, false, false, 28));
                foreach ($context['_seq'] as $context["_key"] => $context["unapproved"]) {
                    // line 29
                    echo "\t\t\t<li class=\"row";
                    if ((twig_get_attribute($this->env, $this->source, $context["unapproved"], "S_ROW_COUNT", [], "any", false, false, false, 29) % 2 != 0)) {
                        echo " bg1";
                    } else {
                        echo " bg2";
                    }
                    echo "\">
\t\t\t\t<dl>
\t\t\t\t\t<dt>
\t\t\t\t\t\t<div class=\"list-inner\">
\t\t\t\t\t\t\t<a href=\"";
                    // line 33
                    echo twig_get_attribute($this->env, $this->source, $context["unapproved"], "U_POST_DETAILS", [], "any", false, false, false, 33);
                    echo "\" class=\"topictitle\">";
                    echo twig_get_attribute($this->env, $this->source, $context["unapproved"], "SUBJECT", [], "any", false, false, false, 33);
                    echo "</a> ";
                    if (twig_get_attribute($this->env, $this->source, $context["unapproved"], "ATTACH_ICON_IMG", [], "any", false, false, false, 33)) {
                        echo "<i class=\"icon fa-paperclip fa-fw\" aria-hidden=\"true\"></i>";
                    }
                    echo " <br />
\t\t\t\t\t\t\t";
                    // line 34
                    echo $this->extensions['phpbb\template\twig\extension']->lang("POSTED");
                    echo " ";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("POST_BY_AUTHOR");
                    echo " ";
                    echo twig_get_attribute($this->env, $this->source, $context["unapproved"], "AUTHOR_FULL", [], "any", false, false, false, 34);
                    echo " &raquo; ";
                    echo twig_get_attribute($this->env, $this->source, $context["unapproved"], "POST_TIME", [], "any", false, false, false, 34);
                    echo "
\t\t\t\t\t\t</div>
\t\t\t\t\t</dt>
\t\t\t\t\t<dd class=\"moderation\"><span>
\t\t\t\t\t\t";
                    // line 38
                    echo $this->extensions['phpbb\template\twig\extension']->lang("TOPIC");
                    echo $this->extensions['phpbb\template\twig\extension']->lang("COLON");
                    echo " <a href=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["unapproved"], "U_TOPIC", [], "any", false, false, false, 38);
                    echo "\">";
                    echo twig_get_attribute($this->env, $this->source, $context["unapproved"], "TOPIC_TITLE", [], "any", false, false, false, 38);
                    echo "</a> [<a href=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["unapproved"], "U_MCP_TOPIC", [], "any", false, false, false, 38);
                    echo "\">";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("MODERATE");
                    echo "</a>]<br />
\t\t\t\t\t\t";
                    // line 39
                    echo $this->extensions['phpbb\template\twig\extension']->lang("FORUM");
                    echo $this->extensions['phpbb\template\twig\extension']->lang("COLON");
                    echo " ";
                    if (twig_get_attribute($this->env, $this->source, $context["unapproved"], "U_FORUM", [], "any", false, false, false, 39)) {
                        echo "<a href=\"";
                        echo twig_get_attribute($this->env, $this->source, $context["unapproved"], "U_FORUM", [], "any", false, false, false, 39);
                        echo "\">";
                        echo twig_get_attribute($this->env, $this->source, $context["unapproved"], "FORUM_NAME", [], "any", false, false, false, 39);
                        echo "</a>";
                    } else {
                        echo twig_get_attribute($this->env, $this->source, $context["unapproved"], "FORUM_NAME", [], "any", false, false, false, 39);
                    }
                    if (twig_get_attribute($this->env, $this->source, $context["unapproved"], "U_MCP_FORUM", [], "any", false, false, false, 39)) {
                        echo " [<a href=\"";
                        echo twig_get_attribute($this->env, $this->source, $context["unapproved"], "U_MCP_FORUM", [], "any", false, false, false, 39);
                        echo "\">";
                        echo $this->extensions['phpbb\template\twig\extension']->lang("MODERATE");
                        echo "</a>]";
                    }
                    echo "</span>
\t\t\t\t\t</dd>

\t\t\t \t\t<dd class=\"mark\"><input type=\"checkbox\" name=\"post_id_list[]\" value=\"";
                    // line 42
                    echo twig_get_attribute($this->env, $this->source, $context["unapproved"], "POST_ID", [], "any", false, false, false, 42);
                    echo "\" /></dd>
\t\t\t\t</dl>
\t\t\t</li>
\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['unapproved'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 46
                echo "\t\t\t</ul>
\t\t";
            }
            // line 48
            echo "
\t\t</div>
\t";
            // line 50
            echo ($context["S_FORM_TOKEN"] ?? null);
            echo "
\t</div>

\t";
            // line 53
            if (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "unapproved", [], "any", false, false, false, 53))) {
                // line 54
                echo "\t<fieldset class=\"display-actions\">
\t\t";
                // line 55
                echo ($context["S_HIDDEN_FIELDS"] ?? null);
                echo "
\t\t<input class=\"button2\" type=\"submit\" name=\"action[disapprove]\" value=\"";
                // line 56
                echo $this->extensions['phpbb\template\twig\extension']->lang("DISAPPROVE");
                echo "\" />&nbsp;
\t\t<input class=\"button1\" type=\"submit\" name=\"action[approve]\" value=\"";
                // line 57
                echo $this->extensions['phpbb\template\twig\extension']->lang("APPROVE");
                echo "\" />
\t\t<div><a href=\"#\" onclick=\"marklist('mcp_queue', 'post_id_list', true); return false;\">";
                // line 58
                echo $this->extensions['phpbb\template\twig\extension']->lang("MARK_ALL");
                echo "</a> :: <a href=\"#\" onclick=\"marklist('mcp_queue', 'post_id_list', false); return false;\">";
                echo $this->extensions['phpbb\template\twig\extension']->lang("UNMARK_ALL");
                echo "</a></div>
\t</fieldset>
\t";
            }
            // line 61
            echo "\t\t</form>
";
        }
        // line 63
        echo "
";
        // line 64
        // line 65
        echo "
";
        // line 66
        if (($context["S_SHOW_REPORTS"] ?? null)) {
            // line 67
            echo "\t<div class=\"panel\">
\t\t<div class=\"inner\">

\t\t<h3>";
            // line 70
            echo $this->extensions['phpbb\template\twig\extension']->lang("LATEST_REPORTED");
            echo "</h3>
\t\t<p>";
            // line 71
            echo $this->extensions['phpbb\template\twig\extension']->lang("REPORTS_TOTAL");
            echo "</p>

\t\t";
            // line 73
            if (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "report", [], "any", false, false, false, 73))) {
                // line 74
                echo "\t\t\t<ul class=\"topiclist two-long-columns\">
\t\t\t\t<li class=\"header\">
\t\t\t\t\t<dl>
\t\t\t\t\t\t<dt><div class=\"list-inner\">";
                // line 77
                echo $this->extensions['phpbb\template\twig\extension']->lang("VIEW_DETAILS");
                echo "</div></dt>
\t\t\t\t\t\t<dd class=\"moderation\"><span>";
                // line 78
                echo $this->extensions['phpbb\template\twig\extension']->lang("REPORTER");
                echo " &amp; ";
                echo $this->extensions['phpbb\template\twig\extension']->lang("FORUM");
                echo "</span></dd>
\t\t\t\t\t</dl>
\t\t\t\t</li>
\t\t\t</ul>
\t\t\t<ul class=\"topiclist cplist two-long-columns responsive-show-all\">

\t\t\t";
                // line 84
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "report", [], "any", false, false, false, 84));
                foreach ($context['_seq'] as $context["_key"] => $context["report"]) {
                    // line 85
                    echo "\t\t\t<li class=\"row";
                    if ((twig_get_attribute($this->env, $this->source, $context["report"], "S_ROW_COUNT", [], "any", false, false, false, 85) % 2 != 0)) {
                        echo " bg1";
                    } else {
                        echo " bg2";
                    }
                    echo "\">
\t\t\t\t<dl>
\t\t\t\t\t<dt>
\t\t\t\t\t\t<div class=\"list-inner\">
\t\t\t\t\t\t\t<a href=\"";
                    // line 89
                    echo twig_get_attribute($this->env, $this->source, $context["report"], "U_POST_DETAILS", [], "any", false, false, false, 89);
                    echo "#reports\" class=\"topictitle\">";
                    echo twig_get_attribute($this->env, $this->source, $context["report"], "SUBJECT", [], "any", false, false, false, 89);
                    echo "</a> ";
                    if (twig_get_attribute($this->env, $this->source, $context["report"], "ATTACH_ICON_IMG", [], "any", false, false, false, 89)) {
                        echo "<i class=\"icon fa-paperclip fa-fw\" aria-hidden=\"true\"></i>";
                    }
                    echo " <br />
\t\t\t\t\t\t\t<span>";
                    // line 90
                    echo $this->extensions['phpbb\template\twig\extension']->lang("POSTED");
                    echo " ";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("POST_BY_AUTHOR");
                    echo " ";
                    echo twig_get_attribute($this->env, $this->source, $context["report"], "AUTHOR_FULL", [], "any", false, false, false, 90);
                    echo " &raquo; ";
                    echo twig_get_attribute($this->env, $this->source, $context["report"], "POST_TIME", [], "any", false, false, false, 90);
                    echo "</span>
\t\t\t\t\t\t</div>
\t\t\t\t\t</dt>
\t\t\t\t\t<dd class=\"moderation\">
\t\t\t\t\t\t<span>";
                    // line 94
                    echo $this->extensions['phpbb\template\twig\extension']->lang("REPORTED");
                    echo " ";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("POST_BY_AUTHOR");
                    echo " ";
                    echo twig_get_attribute($this->env, $this->source, $context["report"], "REPORTER_FULL", [], "any", false, false, false, 94);
                    echo " ";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("REPORTED_ON_DATE");
                    echo " ";
                    echo twig_get_attribute($this->env, $this->source, $context["report"], "REPORT_TIME", [], "any", false, false, false, 94);
                    echo "<br />
\t\t\t\t\t\t";
                    // line 95
                    echo $this->extensions['phpbb\template\twig\extension']->lang("FORUM");
                    echo $this->extensions['phpbb\template\twig\extension']->lang("COLON");
                    echo " <a href=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["report"], "U_FORUM", [], "any", false, false, false, 95);
                    echo "\">";
                    echo twig_get_attribute($this->env, $this->source, $context["report"], "FORUM_NAME", [], "any", false, false, false, 95);
                    echo "</a></span>
\t\t\t\t\t</dd>
\t\t\t\t</dl>
\t\t\t</li>
\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['report'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 100
                echo "\t\t\t</ul>
\t\t";
            }
            // line 102
            echo "
\t\t</div>
\t</div>
";
        }
        // line 106
        echo "
";
        // line 107
        // line 108
        echo "
";
        // line 109
        if (($context["S_SHOW_PM_REPORTS"] ?? null)) {
            // line 110
            echo "\t<div class=\"panel\">
\t\t<div class=\"inner\">

\t\t<h3>";
            // line 113
            echo $this->extensions['phpbb\template\twig\extension']->lang("LATEST_REPORTED_PMS");
            echo "</h3>
\t\t<p>";
            // line 114
            echo $this->extensions['phpbb\template\twig\extension']->lang("PM_REPORTS_TOTAL");
            echo "</p>

\t\t";
            // line 116
            if (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "pm_report", [], "any", false, false, false, 116))) {
                // line 117
                echo "\t\t\t<ul class=\"topiclist two-long-columns\">
\t\t\t\t<li class=\"header\">
\t\t\t\t\t<dl>
\t\t\t\t\t\t<dt><div class=\"list-inner\">";
                // line 120
                echo $this->extensions['phpbb\template\twig\extension']->lang("VIEW_DETAILS");
                echo "</div></dt>
\t\t\t\t\t\t<dd class=\"moderation\"><span>";
                // line 121
                echo $this->extensions['phpbb\template\twig\extension']->lang("REPORTER");
                echo "</span></dd>
\t\t\t\t\t</dl>
\t\t\t\t</li>
\t\t\t</ul>
\t\t\t<ul class=\"topiclist cplist two-long-columns responsive-show-all\">

\t\t\t";
                // line 127
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "pm_report", [], "any", false, false, false, 127));
                foreach ($context['_seq'] as $context["_key"] => $context["pm_report"]) {
                    // line 128
                    echo "\t\t\t<li class=\"row";
                    if ((twig_get_attribute($this->env, $this->source, $context["pm_report"], "S_ROW_COUNT", [], "any", false, false, false, 128) % 2 != 0)) {
                        echo " bg1";
                    } else {
                        echo " bg2";
                    }
                    echo "\">
\t\t\t\t<dl>
\t\t\t\t\t<dt>
\t\t\t\t\t\t<div class=\"list-inner\">
\t\t\t\t\t\t\t<a href=\"";
                    // line 132
                    echo twig_get_attribute($this->env, $this->source, $context["pm_report"], "U_PM_DETAILS", [], "any", false, false, false, 132);
                    echo "\" class=\"topictitle\">";
                    echo twig_get_attribute($this->env, $this->source, $context["pm_report"], "PM_SUBJECT", [], "any", false, false, false, 132);
                    echo "</a> ";
                    if (twig_get_attribute($this->env, $this->source, $context["pm_report"], "ATTACH_ICON_IMG", [], "any", false, false, false, 132)) {
                        echo "<i class=\"icon fa-paperclip fa-fw\" aria-hidden=\"true\"></i>";
                    }
                    echo " <br />
\t\t\t\t\t\t\t<span>";
                    // line 133
                    echo $this->extensions['phpbb\template\twig\extension']->lang("MESSAGE_BY_AUTHOR");
                    echo " ";
                    echo twig_get_attribute($this->env, $this->source, $context["pm_report"], "PM_AUTHOR_FULL", [], "any", false, false, false, 133);
                    echo " &raquo; ";
                    echo twig_get_attribute($this->env, $this->source, $context["pm_report"], "PM_TIME", [], "any", false, false, false, 133);
                    echo "</span><br />
\t\t\t\t\t\t\t<span>";
                    // line 134
                    echo $this->extensions['phpbb\template\twig\extension']->lang("MESSAGE_TO");
                    echo " ";
                    echo twig_get_attribute($this->env, $this->source, $context["pm_report"], "RECIPIENTS", [], "any", false, false, false, 134);
                    echo "</span>
\t\t\t\t\t\t</div>
\t\t\t\t\t</dt>
\t\t\t\t\t<dd class=\"moderation\">
\t\t\t\t\t\t<span>";
                    // line 138
                    echo $this->extensions['phpbb\template\twig\extension']->lang("REPORTED");
                    echo " ";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("POST_BY_AUTHOR");
                    echo " ";
                    echo twig_get_attribute($this->env, $this->source, $context["pm_report"], "REPORTER_FULL", [], "any", false, false, false, 138);
                    echo " ";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("REPORTED_ON_DATE");
                    echo " ";
                    echo twig_get_attribute($this->env, $this->source, $context["pm_report"], "REPORT_TIME", [], "any", false, false, false, 138);
                    echo "</span>
\t\t\t\t\t</dd>
\t\t\t\t</dl>
\t\t\t</li>
\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['pm_report'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 143
                echo "\t\t\t</ul>
\t\t";
            }
            // line 145
            echo "
\t\t</div>
\t</div>
";
        }
        // line 149
        echo "
";
        // line 150
        // line 151
        echo "
";
        // line 152
        if (($context["S_SHOW_LOGS"] ?? null)) {
            // line 153
            echo "\t<div class=\"panel\">
\t\t<div class=\"inner\">

\t\t<h3>";
            // line 156
            echo $this->extensions['phpbb\template\twig\extension']->lang("LATEST_LOGS");
            echo "</h3>

\t\t<table class=\"table1\">
\t\t<thead>
\t\t<tr>
\t\t\t<th class=\"name\">";
            // line 161
            echo $this->extensions['phpbb\template\twig\extension']->lang("ACTION");
            echo "</th>
\t\t\t<th class=\"name\">";
            // line 162
            echo $this->extensions['phpbb\template\twig\extension']->lang("USERNAME");
            echo "</th>
\t\t\t<th class=\"name\">";
            // line 163
            echo $this->extensions['phpbb\template\twig\extension']->lang("IP");
            echo "</th>
\t\t\t<th class=\"name\">";
            // line 164
            echo $this->extensions['phpbb\template\twig\extension']->lang("VIEW_TOPIC");
            echo "</th>
\t\t\t<th class=\"name\">";
            // line 165
            echo $this->extensions['phpbb\template\twig\extension']->lang("VIEW_TOPIC_LOGS");
            echo "</th>
\t\t\t<th class=\"name\">";
            // line 166
            echo $this->extensions['phpbb\template\twig\extension']->lang("TIME");
            echo "</th>
\t\t</tr>
\t\t</thead>
\t\t<tbody>
\t";
            // line 170
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["loops"] ?? null), "log", [], "any", false, false, false, 170));
            $context['_iterated'] = false;
            foreach ($context['_seq'] as $context["_key"] => $context["log"]) {
                // line 171
                echo "\t\t<tr class=\"";
                if ((twig_get_attribute($this->env, $this->source, $context["log"], "S_ROW_COUNT", [], "any", false, false, false, 171) % 2 == 0)) {
                    echo "bg1";
                } else {
                    echo "bg2";
                }
                echo "\">
\t\t\t<td>";
                // line 172
                echo twig_get_attribute($this->env, $this->source, $context["log"], "ACTION", [], "any", false, false, false, 172);
                echo "</td>
\t\t\t<td><span>";
                // line 173
                echo twig_get_attribute($this->env, $this->source, $context["log"], "USERNAME", [], "any", false, false, false, 173);
                echo "</span></td>
\t\t\t<td><span>";
                // line 174
                echo twig_get_attribute($this->env, $this->source, $context["log"], "IP", [], "any", false, false, false, 174);
                echo "</span></td>
\t\t\t<td><span>";
                // line 175
                if (twig_get_attribute($this->env, $this->source, $context["log"], "U_VIEW_TOPIC", [], "any", false, false, false, 175)) {
                    echo "<a href=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["log"], "U_VIEW_TOPIC", [], "any", false, false, false, 175);
                    echo "\" title=\"";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("VIEW_TOPIC");
                    echo "\">";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("VIEW_TOPIC");
                    echo "</a>";
                }
                echo "&nbsp;</span></td>
\t\t\t<td><span>";
                // line 176
                if (twig_get_attribute($this->env, $this->source, $context["log"], "U_VIEWLOGS", [], "any", false, false, false, 176)) {
                    echo "<a href=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["log"], "U_VIEWLOGS", [], "any", false, false, false, 176);
                    echo "\">";
                    echo $this->extensions['phpbb\template\twig\extension']->lang("VIEW_TOPIC_LOGS");
                    echo "</a>";
                }
                echo "&nbsp;</span></td>
\t\t\t<td><span>";
                // line 177
                echo twig_get_attribute($this->env, $this->source, $context["log"], "TIME", [], "any", false, false, false, 177);
                echo "</span></td>
\t\t</tr>
\t";
                $context['_iterated'] = true;
            }
            if (!$context['_iterated']) {
                // line 180
                echo "\t\t<tr>
\t\t\t<td colspan=\"6\">";
                // line 181
                echo $this->extensions['phpbb\template\twig\extension']->lang("NO_ENTRIES");
                echo "</td>
\t\t</tr>
\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['log'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 184
            echo "\t\t</tbody>
\t\t</table>

\t\t</div>
\t</div>
";
        }
        // line 190
        echo "
";
        // line 191
        // line 192
        echo "
";
        // line 193
        $location = "mcp_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("mcp_footer.html", "mcp_front.html", 193)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "mcp_front.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  603 => 193,  600 => 192,  599 => 191,  596 => 190,  588 => 184,  579 => 181,  576 => 180,  568 => 177,  558 => 176,  546 => 175,  542 => 174,  538 => 173,  534 => 172,  525 => 171,  520 => 170,  513 => 166,  509 => 165,  505 => 164,  501 => 163,  497 => 162,  493 => 161,  485 => 156,  480 => 153,  478 => 152,  475 => 151,  474 => 150,  471 => 149,  465 => 145,  461 => 143,  442 => 138,  433 => 134,  425 => 133,  415 => 132,  403 => 128,  399 => 127,  390 => 121,  386 => 120,  381 => 117,  379 => 116,  374 => 114,  370 => 113,  365 => 110,  363 => 109,  360 => 108,  359 => 107,  356 => 106,  350 => 102,  346 => 100,  330 => 95,  318 => 94,  305 => 90,  295 => 89,  283 => 85,  279 => 84,  268 => 78,  264 => 77,  259 => 74,  257 => 73,  252 => 71,  248 => 70,  243 => 67,  241 => 66,  238 => 65,  237 => 64,  234 => 63,  230 => 61,  222 => 58,  218 => 57,  214 => 56,  210 => 55,  207 => 54,  205 => 53,  199 => 50,  195 => 48,  191 => 46,  181 => 42,  157 => 39,  144 => 38,  131 => 34,  121 => 33,  109 => 29,  105 => 28,  94 => 22,  90 => 21,  85 => 18,  83 => 17,  78 => 15,  74 => 14,  66 => 9,  63 => 8,  61 => 7,  58 => 6,  57 => 5,  52 => 3,  49 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "mcp_front.html", "");
    }
}
