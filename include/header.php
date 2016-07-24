<?php
    include 'include/db.php';
?>

<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Nokia Daily Health Check Portal</title>	
        <link rel='stylesheet' type='text/css' href='css/style.css'/>
        <script language="javascript" type="text/javascript" src="js/tablefilter.js"></script>		
        <script language="JavaScript">
            function loadPage(list) {
                location.href = list.options[list.selectedIndex].value
            }
        </script>
        <link rel='stylesheet' type='text/css' href='css/filtergrid.css'/>
        <script type="text/javascript" src="js/prototype.lite.js"></script>
        <script type="text/javascript" src="js/moo.fx.js"></script>
        <script type="text/javascript" src="js/moo.fx.pack.js"></script>
        <script type="text/javascript">
            function init() {
            var stretchers = document.getElementsByClassName('box');
            var toggles = document.getElementsByClassName('tab');
            var myAccordion = new fx.Accordion(
                    toggles, stretchers, {opacity: false, height: true, duration: 600}
            );
            var found = false;
            toggles.each(function (h3, i) {
                var div = Element.find(h3, 'nextSibling');
                if (window.location.href.indexOf(h3.title) > 0) {
                    myAccordion.showThisHideOpen(div);
                    found = true;
                }
            });
            if (!found)
                myAccordion.showThisHideOpen(stretchers[0]);
            }
        </script>
    </head>

<body onload="CoolClock.findAndCreateClocks()">
    <header>
        <script>
            function open_win()
            {
                window.open("file_upload.php", "_blank", "toolbar=yes, location=yes, directories=no, status=no, menubar=yes, scrollbars=yes, resizable=no, copyhistory=yes, width=650, height=410, left=700px, top=200px ");
            }

            function open_win_vvip()
            {
                window.open("vvip_update.php", "_blank", "toolbar=yes, location=yes, directories=no, status=no, menubar=yes, scrollbars=yes, resizable=no, copyhistory=yes, width=650, height=910, left=700px, top=80px ");
            }
        </script>

    <nav>
        <?php
            $name = "alarm_all.csv";
            $link = "raw_files/" . $name;
        ?>	
        <font size="2" color="orange" face="Trebuchet MS">
        <a href="<?php echo $link; ?>" type="application/octet-stream"><b><?php echo "Download "; ?></b></a><?php echo $name; ?>
        </font>

        <input type="button" class="myButton" value="VVIP Sites" onclick="open_win_vvip()">		
        <input type="button" class="myButton" value="Update Database" onclick="open_win()">
    </nav>

    <div class=logo>
        <a href="index.php"><img src=images/net_diag.png></a>		
    </div>
    <div class=banner>
        <table class="bsc-selection">
            <tr>
                <td>
                    <form>
                        <select name="URL"
                                onchange="window.open(this.form.URL.options[this.form.URL.selectedIndex].value)"
                                style="background-color:#91C910">
                            <option value="">:: Select BSC to See Alarms :: </option>
                            <?php
                                $query = "SELECT DISTINCT object_name FROM net_diag WHERE (dn NOT LIKE '%BCF%' AND object_name NOT LIKE '%_C' AND DN NOT LIKE '%RNC-%' AND dn NOT LIKE '%NETACT%') ORDER BY object_name";
                                $result = mysql_query($query);
                                while ($rowsql = mysql_fetch_assoc($result)) {
                            ?>
                                <option value="alarm_details_bsc.php?object_name=<?php echo $rowsql['object_name']; ?>"><?php echo $rowsql['object_name']; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </form>
                </td>				
            </tr>
        </table>	
    </div>
    </header>

<div id="wrapper">
    <div id="content">
        <div class="boxholder">
            <div class="box">			
                <p><strong><font color=blue> Health Check Dashboard:</font></strong><font color=#A00000>  Find the overall summary for Nokia BSCs of Banglalink Network. Click on 'Alarm Text' to get more info...</font>   <font color="blue">Last updated on: 
                    <?php
                        $res = mysql_query("SELECT alarm_update FROM update_date");
                        while ($row = mysql_fetch_assoc($res)) {
                            echo $row['alarm_update'];
                        }
                    ?> </font></p>