<?php
session_start();

$object_name = '$_REQUEST[object_name]';
if (strlen($object_name) == 10) {
    $x = (str_split($object_name, 3));
    $object_name = $x[0] . "_" . $x[1] . substr(chunk_split($x[2], 3, '_'), 0, -2);
}
?>

<?php
    include 'include/header.php';
?>

<div class="panel">
<h2>Health Check Dashboard for Alarms</h2>
<div class="panelcontent">
<div class="top-portion">
<div class="top_table">
    <table border=1>
        <h3> Alarm Summary of <font color=red><?php echo $_REQUEST['text']; ?></font></h3>
        <tr>
        <center>
            <th style="background: #91C910">Object Name </th>
            <th style="background: #91C910">No. of Occurrence</th>
        </center>
        </tr>			
        <?php
            if ($_REQUEST['object_type'] == 'bsc') {
                $query = "SELECT object_name, COUNT(*) FROM net_diag WHERE (text='$_REQUEST[text]' AND (dn NOT LIKE '%BCF%' AND object_name NOT LIKE '%_C' AND DN NOT LIKE '%RNC-%' AND dn NOT LIKE '%NETACT%')) GROUP BY object_name ORDER BY COUNT(*) DESC";
            } else if ($_REQUEST['object_type'] == 'omc') {
                $query = "SELECT object_name, COUNT(*) FROM net_diag WHERE (text='$_REQUEST[text]' AND object_name LIKE 'OMC%') GROUP BY object_name ORDER BY COUNT(*) DESC";
            } else if ($_REQUEST['object_type'] == 'bts') {
                $query = "SELECT object_name, COUNT(*) FROM net_diag WHERE (text LIKE '$_REQUEST[text]' AND (dn LIKE '%BCF%')) GROUP BY object_name ORDER BY COUNT(*) DESC";
            } else if ($_REQUEST['object_type'] == 'vvip') {
                $query = "SELECT object_name, COUNT(*) FROM net_diag WHERE (text LIKE '$_REQUEST[text]%' AND vip_site='Y' AND (object_name LIKE 'DHK%' OR object_name LIKE 'KHU%' OR object_name LIKE 'RAJ%' OR object_name LIKE 'SYL%') ) GROUP BY object_name ORDER BY COUNT(*) DESC";
            } else if ($_REQUEST['object_type'] == 'rnc') {
                $query = "SELECT object_name, COUNT(*) FROM net_diag WHERE ((DN like '%RNC%' OR DN like '%OMS%') AND text LIKE '$_REQUEST[text]%') GROUP BY object_name ORDER BY COUNT(*) DESC";
            } else if ($_REQUEST['object_type'] == 'juniper') {
                $query = "SELECT object_name, COUNT(*) FROM net_diag WHERE (dn like '%JMBH%' AND text LIKE '$_REQUEST[text]%') GROUP BY object_name ORDER BY COUNT(*) DESC";
            }
            $result = mysql_query($query);
            $row = mysql_num_rows($result);

            while ($rowsql = mysql_fetch_assoc($result)) {
        ?>

            <tr>  					
                <td class=td1 align="center"><a href="alarm_details_bts.php?object_type=bts&object_name=<?php echo $rowsql['object_name']; ?>" target="_blank"><font color="blue"><?php echo $rowsql['object_name']; ?></font></a></td>						
                <td class=td2 align="center"><?php echo $rowsql['COUNT(*)']; ?></td>		
            </tr>

        <?php
            }
        ?>
    </table>
</div>
<div class="top_table">
    <table border=1>
        <tr>
        <center>
            <th style="background: #91C910">Object Name </th>
            <th style="background: #91C910">DN </th>							
            <th style="background: #91C910">No. of Occurence</th>
        </center>
        </tr>			
        <?php
            if ($_REQUEST['object_type'] == 'vvip') {
                $query_vip = "SELECT object_name, dn, count(*) AS 'cnt' from net_diag where (text LIKE '%$_REQUEST[text]%' AND vip_site='Y') GROUP BY dn ORDER BY COUNT(*) DESC";
            } else if ($_REQUEST['object_type'] == 'bsc') {
                $query_vip = "SELECT object_name, dn, count(*) AS 'cnt' from net_diag where (text LIKE '%$_REQUEST[text]%' AND (dn NOT LIKE '%BCF%' AND object_name NOT LIKE '%_C' AND DN NOT LIKE '%RNC-%' AND dn NOT LIKE '%NETACT%')) GROUP BY dn ORDER BY COUNT(*) DESC";
            } else if ($_REQUEST['object_type'] == 'bts') {
                $query_vip = "SELECT object_name, dn, count(*) AS 'cnt' from net_diag where (text LIKE '%$_REQUEST[text]%' AND dn LIKE '%BCF%') GROUP BY dn ORDER BY COUNT(*) DESC";
            } else if ($_REQUEST['object_type'] == 'rnc') {
                $query_vip = "SELECT object_name, dn, count(*) AS 'cnt' from net_diag where ((DN like '%RNC%' OR DN like '%OMS%') AND text LIKE '%$_REQUEST[text]%') GROUP BY dn ORDER BY COUNT(*) DESC";
            } else if ($_REQUEST['object_type'] == 'juniper') {
                $query_vip = "SELECT object_name, dn, count(*) AS 'cnt' from net_diag where (dn like '%JMBH%' AND text LIKE '%$_REQUEST[text]%') GROUP BY dn ORDER BY COUNT(*) DESC";
            } else if ($_REQUEST['object_type'] == 'omc') {
                $query_vip = "SELECT object_name, dn, count(*) AS 'cnt' from net_diag where (text LIKE '%$_REQUEST[text]%') GROUP BY dn ORDER BY COUNT(*) DESC";
            }
            $result_vip = mysql_query($query_vip);

            while ($rowsql = mysql_fetch_assoc($result_vip)) {
        ?>

            <tr> 
                <td align="center"><?php echo $rowsql['object_name'] ?></td>		
                <td align="center"><?php echo $rowsql['dn']; ?></td>						
                <td align="center"><font color="red"><?php echo $rowsql['cnt']; ?></font></td>		
            </tr>

        <?php
            }
        ?>
    </table>
</div>				
</div>
</div>
</div>					
    <table class="data_table1" width='100%' id="table1">
        <tr>
        <center>
            <th>Alarm Text</th>
            <th>Alarm ID</th>
            <th>Object Name</th>
            <th>DN</th>		
            <th>Alarm Time</th>
            <th>Cancel Time</th>
            <th>Supply Info</th>						
        </center>
        </tr>
        <?php
            $text = $_REQUEST['text'];
            if ($_REQUEST['object_type'] == 'vvip') {
                $query1 = "SELECT text, alarm_number, object_name, dn, alarm_time, cancel_time, sup_info, ack_status FROM net_diag WHERE text LIKE '$text%' AND vip_site='Y' ORDER BY alarm_time";
            } else if ($_REQUEST['object_type'] == 'rnc') {
                $query1 = "SELECT text, alarm_number, object_name, dn, alarm_time, cancel_time, sup_info, ack_status FROM net_diag WHERE ((DN like '%RNC%' OR DN like '%OMS%') AND text LIKE '$text%') ORDER BY alarm_time";
            } else if ($_REQUEST['object_type'] == 'juniper') {
                $query1 = "SELECT text, alarm_number, object_name, dn, alarm_time, cancel_time, sup_info, ack_status FROM net_diag WHERE (dn like '%JMBH%' AND text LIKE '$text%') ORDER BY alarm_time";
            } else {
                $query1 = "SELECT text, alarm_number, object_name, dn, alarm_time, cancel_time, sup_info, ack_status FROM net_diag WHERE text LIKE '$text%' AND (dn NOT LIKE '%BCF%' AND object_name NOT LIKE '%_C' AND DN NOT LIKE '%RNC-%' AND dn NOT LIKE '%NETACT%') ORDER BY alarm_time";
            }

            $_SESSION[query_data] = $query1;
            $result1 = mysql_query($query1);
            while ($rowsql1 = mysql_fetch_assoc($result1)) {
        ?>
            <tr>
                <td align="center"><?php echo $rowsql1['text']; ?></td> 
                <td align="center"><?php echo $rowsql1['alarm_number']; ?></td>  
                <td align="center"><?php echo $rowsql1['object_name']; ?></td>						
                <td align="center"><?php echo $rowsql1['dn']; ?></td>
                <td align="center"><?php echo $rowsql1['alarm_time']; ?></td>	
                <td align="center"><?php
                    if (strlen(trim($rowsql1['cancel_time'])) != 0) {
                        echo $rowsql1['cancel_time'];
                    } else {
                        echo "Active";
                    }
                    ?>
                </td>		

                <td align="center"><?php echo $rowsql1['sup_info']; ?></td>			
            </tr>
        <?php
        }
        ?>
    </table>
        <script language="javascript" type="text/javascript">
            var table1_Props = {
                col_0: "select",
                col_1: "select",
                col_5: "select",
                col_operation: "sum",
                alternate_rows: true,
                rows_counter: true,
                rows_counter_text: "Total No. of Rows: ",
                btn_reset: true,
                bnt_reset_text: "Clear all "
            };
            setFilterGrid("table1", table1_Props);
        </script>
    </div>		
</body>
</html>