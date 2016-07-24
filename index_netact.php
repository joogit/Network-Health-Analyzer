
<?php
    include 'include/header.php';
?>

<table class="dash-table" border=1>
    <tr>
        <th colspan=3 style="background:#990000"><font color="#fff"> Nokia Network OMC/NetAct Related Alarm Summary </font></th>
    </tr>	
    <tr>
    <center>
        <th style="background: #91C910">Alarm ID </th>	
        <th style="background: #91C910">Alarm Text </th>			
        <th style="background: #91C910">Count </th>
    </center>
</tr>		

    <?php
        $query = "SELECT alarm_number, text, COUNT(*) FROM net_diag 
        WHERE (dn LIKE '%NETACT%' AND alarm_number!=30000 AND alarm_number!=30001 AND alarm_number!=30002)			
        GROUP BY text ORDER BY text";
        $result = mysql_query($query);
        $row = mysql_num_rows($result);

        while ($rowsql = mysql_fetch_assoc($result)) {
    ?>

    <tr>  
        <td class=td1 align="center"><font color=black><?php echo $rowsql['alarm_number']; ?></color></td>						
        <td class=td1 align="left"><a href="alarm_details.php?object_type=omc&text=<?php echo $rowsql['text']; ?>" target="_blank"><font color=black><?php echo $rowsql['text']; ?> </color></a></td>						
        <td class=td2 align="center"><font color=black><?php echo $rowsql['COUNT(*)']; ?><color></td>		
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