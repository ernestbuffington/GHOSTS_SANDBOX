<?php
/*======================================================================= 
  PHP-Nuke Titanium | Nuke-Evolution Xtreme : PHP-Nuke Web Portal System
 =======================================================================*/


$now = explode('-', formatTimestamp(time(),'d-m-Y'));
$nowdate = $now[0];
$nowmonth = $now[1];
$nowyear = $now[2];

function Stats_Main() {
    global $prefix, $nuke_db, $startdate, $sitename, $ThemeSel, $nuke_user_prefix, $nuke_module_name, $nuke_cache;
    $result  = $nuke_db->sql_query('SELECT `type`, `var`, `count` FROM `'.$prefix.'_counter` ORDER BY `count` DESC, var');
    $browser = $os = array();
    $totalos = $totalbr = 0;
    while (list($type, $var, $count) = $nuke_db->sql_fetchrow($result)) {
        if ($type == 'browser') {
            $browser[$var] = $count;
        } elseif ($type == 'os') {
            if ($var == 'OS/2') { $var = 'OS2'; }
            $os[$var] = $count;
            $totalos += $count;
        }
    }
    list($totalbr) = $nuke_db->sql_fetchrow($nuke_db->sql_query('SELECT SUM(hits) FROM `'.$prefix.'_stats_hour`'));
    $nuke_db->sql_freeresult($result);
    if ((($m_size = $nuke_cache->load('m_size', 'config')) === false) || empty($m_size)) {
        $m_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/mainbar.gif');
        $nuke_cache->save('m_size', 'config', $m_size);
    }
    OpenTable();
    echo '<table class="forumline" cellspacing="1" width="100%">
    <tr>
        <td colspan="3" class="cat"><div class="cattitle" align="center">'.$sitename.' '._STATS.'</div></td>
    </tr><tr>
        <td colspan="3" class="row1">
            <div class="gen" align="center">'._WERECEIVED.' <strong>'.$totalbr.'</strong> '._PAGESVIEWS.' '.$startdate.'<br /><br />
            <img src="modules/'.$nuke_module_name.'/images/logo.png" alt="" /><br /><br />
                [ <a href="modules.php?name='.$nuke_module_name.'&amp;op=stats">'._VIEWDETAILED.'</a> ] [ <a href="modules.php?name=Forums&amp;file=statistics">'._VIEWFORUMSTATS.'</a> ]</div><br />
        </td>
    </tr><tr>
        <td class="cat" colspan="3"><div class="cattitle" align="center">'._BROWSERS.'</div></td>
    </tr>';
    $totalbr = 100 / $totalbr;
// Browsers
    if (is_array($browser)) {
        foreach ($browser AS $var => $count) {
            $perc = @round(($totalbr * $count), 2);
            echo '<tr align="left">
            <td class="row1"><div class="gen"><img src="modules/'.$nuke_module_name.'/images/'.strtolower($var).'.png" alt="" />&nbsp;'.$var.'</div></td>
            <td class="row2"><img src="themes/'.$ThemeSel.'/images/leftbar.gif" alt="" /><img src="themes/'.$ThemeSel.'/images/mainbar.gif" alt="" height="'.$m_size[1].'" width="'.$perc.'" /><img src="themes/'.$ThemeSel.'/images/rightbar.gif" alt="" /></td>
            <td class="row3"><div class="gen">'.$perc.' % ('.$count.')</div></td>
        </tr>';
        }
    }
// Operating System
    $totalos = 100 / $totalos;
    echo '<tr>
        <td class="cat" colspan="3"><div class="cattitle" align="center">'._OPERATINGSYS.'</div></td>
    </tr>';
    if (is_array($os)) {
        foreach ($os AS $var => $count) {
            $perc = @round(($totalos * $count), 2);
            echo '<tr align="left">
            <td class="row1"><div class="gen"><img src="modules/'.$nuke_module_name.'/images/'.strtolower($var).'.png" alt="" />&nbsp;'.$var.':</div></td>
            <td class="row2"><img src="themes/'.$ThemeSel.'/images/leftbar.gif" alt="" /><img src="themes/'.$ThemeSel.'/images/mainbar.gif" alt="" height="'.$m_size[1].'" width="'.$perc.'" /><img src="themes/'.$ThemeSel.'/images/rightbar.gif" alt="" /></td>
            <td class="row3"><div class="gen">'.$perc.' % ('.$count.')</div></td>
        </tr>';
        }
    }
// Miscellaneous Stats
    list($unum) = $nuke_db->sql_ufetchrow('SELECT COUNT(*) FROM `'.$nuke_user_prefix.'_users` WHERE `user_id` > 1');
    list($snum) = $nuke_db->sql_ufetchrow('SELECT COUNT(*) FROM `'.$prefix.'_stories`');
    list($cnum) = $nuke_db->sql_ufetchrow('SELECT COUNT(*) FROM `'.$prefix.'_comments`');
    list($subnum) = $nuke_db->sql_ufetchrow('SELECT COUNT(*) FROM `'.$prefix.'_queue`');
    $evover = ucfirst(EVO_EDITION);
    echo '<tr>
        <td colspan="3" class="cat"><div class="cattitle" align="center">'._MISCSTATS.'</div></td>
    </tr><tr align="left">
        <td class="row1" colspan="2"><span class="gen"><img src="modules/'.$nuke_module_name.'/images/users.gif" alt="" />&nbsp;'._REGUSERS.'</span></td><td class="row3"><span class="gen">'.$unum.'</span></td>
    </tr><tr align="left">
        <td class="row1" colspan="2"><span class="gen"><img src="modules/'.$nuke_module_name.'/images/news.gif" alt="" />&nbsp;'._STORIESPUBLISHED.'</span></td><td class="row3"><span class="gen">'.$snum.'</span></td>
    </tr>';
    if (is_active('Topics')) {
        list($tnum) = $nuke_db->sql_ufetchrow("SELECT COUNT(*) FROM `".$prefix."_topics`");
        echo '<tr align="left">
        <td class="row1" colspan="2"><span class="gen"><img src="modules/'.$nuke_module_name.'/images/topics.gif" alt="" />&nbsp;'._SACTIVETOPICS.'</span></td><td class="row3"><span class="gen">'.$tnum.'</span></td>
        </tr>';
    }
    echo '<tr align="left">
        <td class="row1" colspan="2"><span class="gen"><img src="modules/'.$nuke_module_name.'/images/comments.gif" alt="" />&nbsp;'._COMMENTSPOSTED.'</span></td><td class="row3"><span class="gen">'.$cnum.'</span></td>
        </tr>';
    if (is_active('Web_Links')) {
        list($links) = $nuke_db->sql_ufetchrow('SELECT COUNT(*) FROM `'.$prefix.'_links_links`');
        list($cat) = $nuke_db->sql_ufetchrow('SELECT COUNT(*) FROM `'.$prefix.'_links_categories`');
        echo '<tr align="left">
        <td class="row1" colspan="2"><span class="gen"><img src="modules/'.$nuke_module_name.'/images/topics.gif" alt="" />&nbsp;'._LINKSINLINKS.'</span></td><td class="row3"><span class="gen">'.$links.'</span></td>
    </tr><tr align="left">
        <td class="row1" colspan="2"><span class="gen"><img src="modules/'.$nuke_module_name.'/images/news.gif" alt="" />&nbsp;'._LINKSCAT.'</span></td><td class="row3"><span class="gen">'.$cat.'</span></td>
    </tr>';
    }
    echo '<tr align="left">
        <td class="row1" colspan="2"><span class="gen"><img src="modules/'.$nuke_module_name.'/images/waiting.gif" alt="" />&nbsp;'._NEWSWAITING.'</span></td><td class="row3"><span class="gen">'.$subnum.'</span></td>
    </tr>';
    echo '<tr align="left">
        <td class="row1" colspan="2"><span class="gen"><img src="modules/'.$nuke_module_name.'/images/sections.gif" alt="" />&nbsp;'._EVOVER.'</span></td><td class="row3"><span class="gen">'.ucfirst($evover).'</span></td>
    </tr></table>';
    CloseTable();
}

function Stats() {
    global $nowyear, $nowmonth, $nowdate, $sitename, $startdate, $prefix, $nuke_db, $now, $nuke_module_name;

    list($total) = $nuke_db->sql_ufetchrow('SELECT SUM(hits) FROM `'.$prefix."_stats_hour`");
    OpenTable();
    echo '<table class="forumline" cellspacing="1" width="100%">
    <tr>
        <td class="cat"><div class="cattitle" align="center">'.$sitename.' '._STATS.'</div></td>
    </tr><tr>
        <td class="row1" align="center"><span class="gen">'._WERECEIVED.' <strong>'.$total.'</strong> '._PAGESVIEWS.' '.$startdate.'<br /><br />
        <img src="modules/'.$nuke_module_name.'/images/logo.png" alt="" /><br /><br />'._TODAYIS.": $now[0]/$now[1]/$now[2]<br />";

    list($year, $month, $hits) = $nuke_db->sql_ufetchrow("SELECT `year`, `month`, SUM(hits) as hits FROM `".$prefix."_stats_hour` GROUP BY `month`, `year` ORDER BY `hits` DESC LIMIT 0,1");
    echo _MOSTMONTH.": ".getmonth($month)." $year ($hits "._HITS.")<br />";

    list($year, $month, $date, $hits) = $nuke_db->sql_ufetchrow("SELECT `year`, `month`, `date`, SUM(hits) as hits FROM `".$prefix."_stats_hour` GROUP BY `date`, `month`, `year` ORDER BY `hits` DESC LIMIT 0,1");
    echo _MOSTDAY.": $date ".getmonth($month)." $year ($hits "._HITS.")<br />";

    list($year, $month, $date, $hour, $hits) = $nuke_db->sql_ufetchrow("SELECT `year`, `month`, `date`, `hour`, `hits` from `".$prefix."_stats_hour` ORDER BY `hits` DESC LIMIT 0,1");
    if ($hour < 10) {
        $hour = "0$hour:00 - 0$hour:59";
    } else {
        $hour = "$hour:00 - $hour:59";
    }
    echo _MOSTHOUR.": $hour "._ON." ".getmonth($month)." $date, $year ($hits "._HITS.")<br /><br />[ <a href=\"modules.php?name=".$nuke_module_name."\">"._RETURNBASICSTATS.'</a> ]</span><br />&nbsp;</td>
    </tr></table><br />';

    showYearStats($nowyear);
    echo '<br />';
    showMonthStats($nowyear,$nowmonth);
    echo '<br />';
    showDailyStats($nowyear,$nowmonth,$nowdate);
    echo '<br />';
    showHourlyStats($nowyear,$nowmonth,$nowdate);
    CloseTable();
}

function YearlyStats($year) {
    global $nowmonth, $sitename, $nuke_module_name;
    OpenTable();
    showMonthStats($year,$nowmonth);
    echo '<br />';
    echo "<center>[ <a href=\"modules.php?name=".$nuke_module_name."\">"._BACKTOMAIN."</a> | <a href=\"modules.php?name=$nuke_module_name&amp;op=stats\">"._BACKTODETSTATS."</a> ]</center>";
    CloseTable();
}

function MonthlyStats($year, $month) {
    global $sitename, $nowdate, $nuke_module_name;
    OpenTable();
    showDailyStats($year,$month,$nowdate);
    echo '<br />';
    echo "<center>[ <a href=\"modules.php?name=".$nuke_module_name."\">"._BACKTOMAIN."</a> | <a href=\"modules.php?name=".$nuke_module_name."&amp;op=stats\">"._BACKTODETSTATS."</a> ]</center>";
    CloseTable();
}

function DailyStats($year, $month, $date) {
    global $sitename, $nuke_module_name;
    OpenTable();
    showHourlyStats($year,$month,$date);
    echo '<br />';
    echo "<center>[ <a href=\"modules.php?name=".$nuke_module_name."\">"._BACKTOMAIN."</a> | <a href=\"modules.php?name=".$nuke_module_name."&amp;op=stats\">"._BACKTODETSTATS."</a> ]</center>";
    CloseTable();
}

function showYearStats($nowyear) {
    global $prefix, $nuke_db, $ThemeSel, $nuke_module_name, $nuke_cache;
    if ((($m_size = $nuke_cache->load('m_size', 'config')) === false) || empty($m_size)) {
        $m_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/mainbar.gif');
        $nuke_cache->save('m_size', 'config', $m_size);
    }
    if ((($l_size = $nuke_cache->load('l_size', 'config')) === false) || empty($l_size)) {
        $l_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/leftbar.gif');
        $nuke_cache->save('l_size', 'config', $l_size);
    }
    if ((($r_size = $nuke_cache->load('r_size', 'config')) === false) || empty($r_size)) {
        $r_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/rightbar.gif');
        $nuke_cache->save('r_size', 'config', $r_size);
    }
    list($TotalHitsYear) = $nuke_db->sql_ufetchrow("SELECT SUM(hits) AS TotalHitsYear FROM `".$prefix."_stats_hour`");
    $result = $nuke_db->sql_query("SELECT `year`, SUM(hits) FROM `".$prefix."_stats_hour` GROUP BY `year` ORDER BY year");
    echo '<table class="forumline" cellspacing="1" width="100%">
    <tr>
        <td colspan="3" class="cat"><div class="cattitle" align="center">'._YEARLYSTATS.'</div></td>
    </tr><tr>
        <td width="25%" class="row2"><span class="gen"><strong>'._YEAR.'</strong></span></td><td colspan="2" class="row2"><span class="gen"><strong>'._SPAGESVIEWS.'</strong></span></td>
    </tr>';
    while (list($year,$hits) = $nuke_db->sql_fetchrow($result)){
        echo '<tr>
        <td class="row1"><span class="gen">';
        if ($year != $nowyear) {
            echo '<a href="modules.php?name='.$nuke_module_name.'&amp;op=yearly&amp;year='.$year.'">'.$year.'</a>';
        } else {
            echo $year;
        }
        echo '</span></td><td class="row1" nowrap="nowrap">';
        $WidthIMG = @round(100 * $hits/$TotalHitsYear,0);
        echo "<img src=\"themes/$ThemeSel/images/leftbar.gif\" alt=\"\" width=\"".$l_size[0]."\" height=\"".$l_size[1]."\" />";
        echo "<img src=\"themes/$ThemeSel/images/mainbar.gif\" height=\"".$m_size[1]."\" width=\"".($WidthIMG * 2)."\" alt=\"\" />";
        echo "<img src=\"themes/$ThemeSel/images/rightbar.gif\" alt=\"\" width=\"".$r_size[0]."\" height=\"".$r_size[1]."\" /></td><td class=\"row1\"><span class=\"gen\">$hits</span></td>
    </tr>";
    }
    $nuke_db->sql_freeresult($result);
    echo '</table>';
}

function showMonthStats($nowyear, $nowmonth) {
    global $prefix, $nuke_db, $ThemeSel, $nuke_module_name, $nuke_cache;
    if ((($m_size = $nuke_cache->load('m_size', 'config')) === false) || empty($m_size)) {
        $m_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/mainbar.gif');
        $nuke_cache->save('m_size', 'config', $m_size);
    }
    if ((($l_size = $nuke_cache->load('l_size', 'config')) === false) || empty($l_size)) {
        $l_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/leftbar.gif');
        $nuke_cache->save('l_size', 'config', $l_size);
    }
    if ((($r_size = $nuke_cache->load('r_size', 'config')) === false) || empty($r_size)) {
        $r_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/rightbar.gif');
        $nuke_cache->save('r_size', 'config', $r_size);
    }
    list($TotalHitsMonth) = $nuke_db->sql_ufetchrow("SELECT sum(hits) AS TotalHitsMonth FROM `".$prefix."_stats_hour` WHERE `year`='$nowyear'");
    echo '<table class="forumline" cellspacing="1" width="100%">
    <tr>
        <td colspan="3" class="cat"><div class="cattitle" align="center">'._MONTLYSTATS.' '.$nowyear.'</div></td>
    </tr><tr>
        <td width="25%" class="row2"><span class="gen"><strong>'._UMONTH.'</strong></span></td><td class="row2" colspan="2"><span class="gen"><strong>'._SPAGESVIEWS.'</strong></span></td>
    </tr>';
    $result = $nuke_db->sql_query("SELECT month, SUM(hits) FROM ".$prefix."_stats_hour WHERE year='$nowyear' GROUP BY month ORDER BY month");
    while (list($month,$hits) = $nuke_db->sql_fetchrow($result)){
        echo '<tr>
        <td class="row1"><span class="gen">';
        if ($month != $nowmonth) {
            echo "<a href=\"modules.php?name=".$nuke_module_name."&amp;op=monthly&amp;year=$nowyear&amp;month=$month\">".getmonth($month)."</a>";
        } else {
            echo getmonth($month);
        }
        echo '</span></td><td class="row1" nowrap="nowrap">';
        $WidthIMG = @round(100 * $hits/$TotalHitsMonth,0);
        echo "<img src=\"themes/$ThemeSel/images/leftbar.gif\" alt=\"\" width=\"".$l_size[0]."\" height=\"$l_size[1]\" />";
        echo "<img src=\"themes/$ThemeSel/images/mainbar.gif\" height=\"".$m_size[1]."\" width=\"".($WidthIMG * 2)."\" alt=\"\" />";
        echo "<img src=\"themes/$ThemeSel/images/rightbar.gif\" alt=\"\" width=\"".$r_size[0]."\" height=\"".$r_size[1]."\" /></td><td class=\"row1\"><span class=\"gen\">$hits</span></td>
    </tr>";
    }
    $nuke_db->sql_freeresult($result);
    echo '</table>';
}

function showDailyStats($year, $month, $nowdate) {
    global $prefix, $nuke_db, $ThemeSel, $nuke_module_name, $nuke_cache;
    if ((($m_size = $nuke_cache->load('m_size', 'config')) === false) || empty($m_size)) {
        $m_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/mainbar.gif');
        $nuke_cache->save('m_size', 'config', $m_size);
    }
    if ((($l_size = $nuke_cache->load('l_size', 'config')) === false) || empty($l_size)) {
        $l_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/leftbar.gif');
        $nuke_cache->save('l_size', 'config', $l_size);
    }
    if ((($r_size = $nuke_cache->load('r_size', 'config')) === false) || empty($r_size)) {
        $r_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/rightbar.gif');
        $nuke_cache->save('r_size', 'config', $r_size);
    }

    $result = $nuke_db->sql_query("SELECT `date`, SUM(hits) as `hits` FROM `".$prefix."_stats_hour` WHERE `year`='$year' AND `month`='$month' GROUP BY `date` ORDER BY `date`");
    $TotalHitsDate = $date = 0;
    $days = array();
    while ($row = $nuke_db->sql_fetchrow($result)) {
        $TotalHitsDate = $TotalHitsDate + $row['hits'];
        $date++;
        while ($date < $row['date']) {
            $days[] = array('date'=>$date, 'hits'=>0);
            $date++;
        }
        $days[] = $row;
    }
    $nuke_db->sql_freeresult($result);
    echo '<table class="forumline" cellspacing="1" width="100%">
    <tr>
        <td colspan="3" class="cat"><div class="cattitle" align="center">'._DAILYSTATS.' '.getmonth($month).', '.$year.'</div></td>
    </tr><tr>
        <td width="25%" class="row2"><span class="gen"><strong>'._DATE.'</strong></span></td><td class="row2" colspan="2"><span class="gen"><strong>'._SPAGESVIEWS.'</strong></span></td>
    </tr>';
    foreach ($days as $day) {
        $date = $day['date'];
        $hits = $day['hits'];
        echo '<tr>
        <td class="row1"><span class="gen">';
        if ($date != $nowdate && $hits > 0 ) {
            echo '<a href="modules.php?name='.$nuke_module_name.'&amp;op=daily&amp;year='.$year.'&amp;month='.$month.'&amp;date='.$date.'">'.$date.'</a>';
        } else {
            echo $date;
        }
        echo '</span></td><td class="row1" nowrap="nowrap">';
        if ($hits == 0) {
            $WidthIMG = 0;
            $d_percent = 0;
        } else {
            $WidthIMG = @round(100 * $hits/$TotalHitsDate,0);
            $d_percent = @substr(100 * $hits / $TotalHitsDate, 0, 5);
        }
        echo "<img src=\"themes/$ThemeSel/images/leftbar.gif\" alt=\"\" width=\"".$l_size[0]."\" height=\"".$l_size[1]."\" />";
        echo "<img src=\"themes/$ThemeSel/images/mainbar.gif\" height=\"".$m_size[1]."\" width=\"".($WidthIMG * 2)."\" alt=\"\" />";
        echo "<img src=\"themes/$ThemeSel/images/rightbar.gif\" alt=\"\" width=\"".$r_size[0]."\" height=\"".$r_size[1]."\" /></td><td class=\"row1\"><span class=\"gen\">$hits ($d_percent%)</span></td>
    </tr>";
    }
    echo '</table>';
}

function showHourlyStats($year, $month, $date) {
    global $prefix, $nuke_db, $ThemeSel, $nuke_module_name, $nuke_cache;
    if ((($m_size = $nuke_cache->load('m_size', 'config')) === false) || empty($m_size)) {
        $m_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/mainbar.gif');
        $nuke_cache->save('m_size', 'config', $m_size);
    }
    if ((($l_size = $nuke_cache->load('l_size', 'config')) === false) || empty($l_size)) {
        $l_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/leftbar.gif');
        $nuke_cache->save('l_size', 'config', $l_size);
    }
    if ((($r_size = $nuke_cache->load('r_size', 'config')) === false) || empty($r_size)) {
        $r_size = @getimagesize(NUKE_THEMES_DIR.$ThemeSel.'/images/rightbar.gif');
        $nuke_cache->save('r_size', 'config', $r_size);
    }
    list($TotalHitsHour) = $nuke_db->sql_ufetchrow('SELECT SUM(hits) AS TotalHitsHour FROM `'.$prefix."_stats_hour` WHERE `year`='$year' AND `month`='$month' AND `date`='$date'");
    $nowdate = date('d-m-Y');
    $nowdate_arr = explode('-', $nowdate);
    echo '<table class="forumline" cellspacing="1" width="100%">
    <tr>
        <td colspan="3" class="cat"><div class="cattitle" align="center">'._HOURLYSTATS.' '.getmonth($month).' '.$date.', '.$year.'</div></td>
    </tr><tr>
        <td width="25%" class="row2"><span class="gen"><strong>'._HOUR.'</strong></span></td>
        <td class="row2" colspan="2"><span class="gen"><strong>'._SPAGESVIEWS.'</strong></span></td>
    </tr>';
    for ($k = 0;$k<=23;$k++) {
    $result = $nuke_db->sql_query("SELECT hour, hits FROM ".$prefix."_stats_hour WHERE year='$year' AND month='$month' AND date='$date' AND hour='$k'");
    if ($nuke_db->sql_numrows($result) == 0){
        $hits=0;
    } else {
        list($hour,$hits) = $nuke_db->sql_fetchrow($result);
    }
    $nuke_db->sql_freeresult($result);
    $a = ($k < 10) ? "0$k" : $k;
    echo '<tr>
        <td class="row1"><span class="gen">';
    echo "$a:00 - $a:59";
    $a = '';
    echo '</span></td><td class="row1" nowrap="nowrap">';
    if ($hits == 0) {
        $WidthIMG = $d_percent = 0;
    } else {
        $WidthIMG = @round(100 * $hits/$TotalHitsHour,0);
        $d_percent = @substr(100 * $hits / $TotalHitsHour, 0, 5);
    }
    echo "<img src=\"themes/$ThemeSel/images/leftbar.gif\" alt=\"\" width=\"".$l_size[0]."\" height=\"".$l_size[1]."\" />";
    echo "<img src=\"themes/$ThemeSel/images/mainbar.gif\" height=\"".$m_size[1]."\" width=\"".($WidthIMG * 2)."\" alt=\"\" />";
    echo "<img src=\"themes/$ThemeSel/images/rightbar.gif\" alt=\"\" width=\"".$r_size[0]."\" height=\"".$r_size[1]."\" /></td><td class=\"row1\"><span class=\"gen\">$hits ($d_percent%)</span></td></tr>";
    }
    echo '</table>';
}

function getmonth($month) {
    $month = intval($month);
    $months = array(1=>_JANUARY, _FEBRUARY, _MARCH, _APRIL, _MAY, _JUNE, _JULY, _AUGUST, _SEPTEMBER, _OCTOBER, _NOVEMBER, _DECEMBER);
    return (array_key_exists($month, $months) ? $months[$month] : '');
}

?>