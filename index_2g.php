<?php
    include 'include/header.php';
?>

<table class="dash-table" border=1>
    <tr>
        <th colspan=3 style="background:#990000"><font color="#fff"> Nokia Network BSC Alarm Summary </font></th>
    </tr>	
    <tr>
        <center>
            <th style="background: #91C910">Alarm ID </th>	
            <th style="background: #91C910">Alarm Text </th>			
            <th style="background: #91C910">Count </th>
        </center>
    </tr>

    <?php
        $query = "SELECT alarm_number, text, COUNT(*) 
        FROM net_diag 
        WHERE (dn NOT LIKE '%BCF%' AND dn NOT LIKE '%JUNIPER%' AND dn NOT LIKE '%OMS%' AND object_name NOT LIKE '%_C' AND DN NOT LIKE '%RNC%' AND dn NOT LIKE '%NETACT%' AND alarm_number!='ALARM_NUMBER' AND alarm_number!='')
        GROUP BY text ORDER BY text";
        $result = mysql_query($query);
        $row = mysql_num_rows($result);

        while ($rowsql = mysql_fetch_assoc($result)) {

            if ($rowsql['COUNT(*)'] > 100) {
                $color = '#cc0033';
                $bg = 'background:#990000';
            } else if ($rowsql['COUNT(*)'] > 50) {
                $color = '#0066cc';
            } else {
                $color = 'black';
            }

            if ($rowsql['text'] == 'BTS AND TC UNSYNCHRONIZATION CLEAR CALLS ON ABIS I') {
                $color = 'red';
            }
    ?>

    <tr>  
        <td class=td1 align="center"><font color=<?php echo $color . ">"; ?><?php echo $rowsql['alarm_number']; ?></color></td>						
        <td class=td1 align="left"><a href="alarm_details.php?object_type=bsc&text=<?php echo $rowsql['text']; ?>" target="_blank"><font color=<?php echo $color . ">"; ?><?php echo $rowsql['text']; ?> </color></a></td>						
        <td class=td2 align="center"><font color=<?php echo $color . ">"; ?><?php echo $rowsql['COUNT(*)']; ?><color></td>		
    </tr>

    <?php
        }
    ?>
</table>

<table class="dash-table" border=1>
    <tr>
        <th colspan=3 style="background:#990000"><font color="#fff"> Nokia Network BTS Alarm Summary <font></th>
    </tr>	
    <tr>
    <center>
        <th style="background: #91C910">Alarm ID </th>	
        <th style="background: #91C910">Alarm Text </th>			
        <th style="background: #91C910">Count</th>
    </center>
    </tr>		

    <?php
        $query2 = "SELECT alarm_number, text, COUNT(*) 
        FROM net_diag 
        WHERE (dn LIKE '%BCF%' AND alarm_number!='' AND text!='')
        AND alarm_number!='7401' AND alarm_number!='7402' AND alarm_number!='7403' AND alarm_number!='7404' AND alarm_number!='7405'
        AND alarm_number!='7406' AND alarm_number!='7407' AND alarm_number!='7408' AND alarm_number!='7409' AND alarm_number!='7410' AND alarm_number!='7411' AND alarm_number!='7490'
        AND alarm_number!='7461' AND alarm_number!='7462' AND alarm_number!='7465' AND alarm_number!='7466' AND alarm_number!='7486' AND alarm_number!='7487' AND alarm_number!='7489' AND alarm_number!='7492' AND alarm_number!='7493' AND alarm_number!='7488' AND alarm_number!='7499'
        AND alarm_number!='7622' AND alarm_number!='7995' AND alarm_number!='8112'
        AND alarm_number!='61006'
        GROUP BY text ORDER BY COUNT(*) DESC";
        $result2 = mysql_query($query2);
        $row2 = mysql_num_rows($result2);

        while ($rowsql2 = mysql_fetch_assoc($result2)) {
            if ($rowsql2['COUNT(*)'] > 100) {
                $color = '#cc0033';
            } else if ($rowsql2['COUNT(*)'] > 50) {
                $color = '#0066cc';
            } else {
                $color = 'black';
            }

            if ($rowsql2['text'] == 'BTS AND TC UNSYNCHRONIZATION CLEAR CALLS ON ABIS I') {
                $color = 'red';
            }
    ?>

    <tr>  
        <td class=td1 align="center"><font color=<?php echo $color . ">"; ?><?php echo $rowsql2['alarm_number']; ?></color></td>														
        <td class=td1 align="left"><a href="alarm_details.php?object_type=bts&text=<?php echo $rowsql2['text']; ?>" target="_blank"><font color=<?php echo $color . ">"; ?><?php echo $rowsql2['text']; ?> </color></a></td>						
        <td class=td2 align="center"><font color=<?php echo $color . ">"; ?><?php echo $rowsql2['COUNT(*)']; ?><color></td>		
    </tr>

    <?php
        }
    ?>
</table>

<table class="dash-table" border=1>
    <tr>
        <th colspan=3 style="background:#990000"><font color="#fff"> VVIP SITES Alarm Summary <font></th>
    </tr>	
    <tr>
    <center>
        <th style="background: #91C910">Alarm ID </th>	
        <th style="background: #91C910">Alarm Text </th>			
        <th style="background: #91C910">Count</th>
    </center>
    </tr>		

    <?php
        $query2 = "SELECT alarm_number, text, COUNT(*) 
        FROM net_diag 
        WHERE (vip_site='Y' AND (dn LIKE '%BCF%' AND alarm_number!='' AND text!=''
        AND alarm_number!='7401' AND alarm_number!='7402' AND alarm_number!='7403' AND alarm_number!='7404' AND alarm_number!='7405'
        AND alarm_number!='7406' AND alarm_number!='7407' AND alarm_number!='7408' AND alarm_number!='7409' AND alarm_number!='7410' AND alarm_number!='7411' AND alarm_number!='7490'
        AND alarm_number!='7461' AND alarm_number!='7462' AND alarm_number!='7465' AND alarm_number!='7466' AND alarm_number!='7486' AND alarm_number!='7487' AND alarm_number!='7489' AND alarm_number!='7492' AND alarm_number!='7493' AND alarm_number!='7488' AND alarm_number!='7499'
        AND alarm_number!='7622' AND alarm_number!='7995' AND alarm_number!='8112'
        AND alarm_number!='61006'))
        GROUP BY text ORDER BY COUNT(*) DESC";

        $result2 = mysql_query($query2);
        $row2 = mysql_num_rows($result2);

        while ($rowsql2 = mysql_fetch_assoc($result2)) {

            if ($rowsql2['COUNT(*)'] > 100) {
                $color = '#cc0033';
            } else if ($rowsql2['COUNT(*)'] > 50) {
                $color = '#0066cc';
            } else {
                $color = 'black';
            }

            if ($rowsql2['text'] == 'BTS AND TC UNSYNCHRONIZATION CLEAR CALLS ON ABIS I') {
                $color = 'red';
            }
    ?>

    <tr>  
        <td class=td1 align="center"><font color=<?php echo $color . ">"; ?><?php echo $rowsql2['alarm_number']; ?></color></td>														
        <td class=td1 align="left"><a href="alarm_details.php?object_type=vvip&text=<?php echo $rowsql2['text']; ?>" target="_blank"><font color=<?php echo $color . ">"; ?><?php echo $rowsql2['text']; ?> </color></a></td>						
        <td class=td2 align="center"><font color=<?php echo $color . ">"; ?><?php echo $rowsql2['COUNT(*)']; ?><color></td>		
    </tr>

    <?php
        }
    ?>
</table>

</div>	
</div>
</div>
</div>
<script type="text/javascript">
    Element.cleanWhitespace('content');
    init();
</script>


<div class=top-portion>
</div>	

</body>
</html>