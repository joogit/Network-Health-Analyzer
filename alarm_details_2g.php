<?php
		session_start();

		$host = 'localhost';
		$username = 'root';
		$password = '';
		$db = 'aska_db_test';
		$res = mysql_connect($host, $username, $password);
		
		if (!$res) die('Could not connect to the server, mysql error: '.mysql_error($res));
		$res = mysql_select_db($db);
		if (!$res) die('Could not connect to the database, mysql error: '.mysql_error($res));

		//$query="SELECT * FROM flexi_cs";	

		$object_name='$_REQUEST[object_name]';			
				if (strlen($object_name)==10){
					$x=(str_split($object_name,3));	
					$object_name=$x[0]."_".$x[1].substr(chunk_split($x[2],3,'_'),0,-2);	
				}	
		
		
?>

<!DOCTYPE HTML>
<head>
	<meta charset="utf-8"/>
	<title>ASKA | Arafat's Smart KPI Analyzer</title>	
	<script language="javascript" type="text/javascript" src="tablefilter.js"></script>		
	<link rel='stylesheet' type='text/css' href='style.css'/>
	
<!--[if IE]><script type="text/javascript" src="excanvas.js"></script><![endif]-->
<script type="text/javascript" src="coolclock.js"></script>
<script type="text/javascript" src="moreskins.js"></script>	

<!-- for panel -->
<script type="text/javascript" src="utils.js"></script>	
	
	
<script language="JavaScript">
function loadPage(list) {
  location.href=list.options[list.selectedIndex].value
}
</script>

<style type="text/css" media="screen">
/*====================================================
	- HTML Table Filter stylesheet
=====================================================*/
@import "filtergrid.css";

.textarea-table {
	width:130px;
	padding:4px;
	font: normal 12px Verdana, Sans-serif;
	/*border:1px solid #eee;*/
	height: 25px;
	display:block;
	color:#777;
	background-color : #e7edf2;
	}
</style>	
</head>
<body>
<div class="wrapper">
<header>
	<div class=logo>
		<img src=images/net_diag.png>		
	</div>
	<div class=banner>
			<table class="bsc-selection">
				<tr>
				<td>
				<form>
					<select name="file" size="1"
					  onchange="loadPage(this.form.elements[0])"
					  target="_parent._top"
					  onmouseclick="this.focus()"
					  style="background-color:#91C910">
						<option value="">:: Select BSC to See Alarms :: </option>
						<?php
						$query="select DISTINCT object_name FROM net_diag WHERE (object_name LIKE 'BS%'  OR object_name LIKE 'OMC%' OR object_name LIKE 'PLMN%') GROUP BY object_name ORDER BY object_name ";
						$result=mysql_query($query);						
						while($rowsql=mysql_fetch_assoc($result)){
							$x=$rowsql['sum(bss_drop)'];
								if ($x >'500'){
								?>
								<option value="alarm_details.php?object_name=<?php echo $rowsql['object_name']; ?>"><?php echo $rowsql['object_name']; ?></option>
								<?php
								}
						}?>
					</select>
				</form>
				</td>
				
				<td>
				<form>
					<select name="URL"
					  onchange="window.open(this.form.URL.options[this.form.URL.selectedIndex].value)"
					  style="background-color:#91C910"
					  >
						<option value="">:: Select BTS to See Alarms::</option>
						<?php
						$query="SELECT DISTINCT object_name FROM net_diag WHERE object_name='$object_name' ORDER BY object_name";
						$result=mysql_query($query);						
						while($rowsql=mysql_fetch_assoc($result)){

								?>
								<option value="alarm_details.php?object_name=<?php echo $rowsql['object_name'];?>" ><?php echo $rowsql['object_name']; ?></option>
								<?php
						}
						?>
					</select>
				</form>
				</td>				
				</tr>
				<tr>
				<td>
					<form action='XLExport1.php' method='POST'>						
						<input type='submit' name='export2excel' value='Export To Excel'/>
					</form>
				</td>
				</tr>		
			</table>	
	</div>
	<nav>
	</nav>
</header>

<div class="panel">
  <h2>ASKA Dashboard for Alarms</h2>
  <div class="panelcontent">
			<div class="top-portion">
				<div class="top_table">
					<table border=1>
						<h3> Alarm Summary of <font color=red><?php echo $_REQUEST['text']; ?>:</font></h3>
						<tr>
						<center>
							<th style="background: #91C910">Object Name </th>
							<th style="background: #91C910">No. of Occurence</th>
						</center>
						</tr>			
						<?php
						
						
						if($_REQUEST['object_type']=='bsc'){
						//$query="SELECT text, count(text) FROM net_diag WHERE object_name='$_REQUEST[bsc_name]' ORDER BY text";
						$query="SELECT object_name, COUNT(*) FROM net_diag WHERE (text='$_REQUEST[text]' AND object_name LIKE 'BS%') GROUP BY object_name ORDER BY COUNT(*) DESC";
						}						
						else if($_REQUEST['object_type']=='omc'){
						//$query="SELECT text, count(text) FROM net_diag WHERE object_name='$_REQUEST[bsc_name]' ORDER BY text";
						$query="SELECT object_name, COUNT(*) FROM net_diag WHERE (text='$_REQUEST[text]' AND object_name LIKE 'OMC%') GROUP BY object_name ORDER BY COUNT(*) DESC";
						}
						else if($_REQUEST['object_type']=='bts'){
						$query="SELECT object_name, COUNT(*) FROM net_diag WHERE (text LIKE '$_REQUEST[text]' AND vip_site='N' AND (object_name LIKE 'DHK%' OR object_name LIKE 'KHU%' OR object_name LIKE 'RAJ%' OR object_name LIKE 'CTG%' OR object_name LIKE 'SYL%') ) GROUP BY object_name ORDER BY COUNT(*) DESC";
						}
						else if($_REQUEST['object_type']=='vvip'){
						//$query="SELECT net_diag.object_name, COUNT(net_diag.object_name) FROM net_diag, sp_case WHERE ((sp_case.object_name=net_diag.object_name) AND (text='$_REQUEST[text]' AND (object_name LIKE 'DHK%' OR object_name LIKE 'KHU%' OR object_name LIKE 'RAJ%' OR object_name LIKE 'SYL%') )) GROUP BY object_name ORDER BY COUNT(net_diag.object_name) DESC";
						$query="SELECT object_name, COUNT(*) FROM net_diag WHERE (text LIKE '$_REQUEST[text]%' AND vip_site='Y' AND (object_name LIKE 'DHK%' OR object_name LIKE 'KHU%' OR object_name LIKE 'RAJ%' OR object_name LIKE 'SYL%') ) GROUP BY object_name ORDER BY COUNT(*) DESC";
						}	
						
						$result=mysql_query($query);
						$row=mysql_num_rows($result);	
						
						while($rowsql=mysql_fetch_assoc($result))
						{
						?>
							
							<tr>  					
								<td class=td1 align="center"><a href="alarm_details_bts.php?object_type=bts&object_name=<?php echo $rowsql['object_name'];?>" target="_blank"><font color="blue"><?php echo $rowsql['object_name']; ?></font></a></td>						
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
						
						if($_REQUEST['object_type']=='vvip'){
						//$query="SELECT net_diag.object_name, COUNT(net_diag.object_name) FROM net_diag, sp_case WHERE ((sp_case.object_name=net_diag.object_name) AND (text='$_REQUEST[text]' AND (object_name LIKE 'DHK%' OR object_name LIKE 'KHU%' OR object_name LIKE 'RAJ%' OR object_name LIKE 'SYL%') )) GROUP BY object_name ORDER BY COUNT(net_diag.object_name) DESC";
						//$query="SELECT object_name, dn, COUNT(*) from net_diag WHERE (text LIKE '$_REQUEST[text]%' AND vip_site='Y' AND (object_name LIKE 'DHK%' OR object_name LIKE 'KHU%' OR object_name LIKE 'RAJ%' OR object_name LIKE 'SYL%')) GROUP BY dn";
						 $query_vip="SELECT object_name, dn, count(*) AS 'cnt' from net_diag where (text LIKE '%$_REQUEST[text]%' AND vip_site='Y') GROUP BY dn ORDER BY COUNT(*) DESC"; 
						 }	

						else if($_REQUEST['object_type']=='bts'){
						//$query="SELECT net_diag.object_name, COUNT(net_diag.object_name) FROM net_diag, sp_case WHERE ((sp_case.object_name=net_diag.object_name) AND (text='$_REQUEST[text]' AND (object_name LIKE 'DHK%' OR object_name LIKE 'KHU%' OR object_name LIKE 'RAJ%' OR object_name LIKE 'SYL%') )) GROUP BY object_name ORDER BY COUNT(net_diag.object_name) DESC";
						//$query="SELECT object_name, dn, COUNT(*) from net_diag WHERE (text LIKE '$_REQUEST[text]%' AND vip_site='Y' AND (object_name LIKE 'DHK%' OR object_name LIKE 'KHU%' OR object_name LIKE 'RAJ%' OR object_name LIKE 'SYL%')) GROUP BY dn";
						 $query_vip="SELECT object_name, dn, count(*) AS 'cnt' from net_diag where (text LIKE '%$_REQUEST[text]%' AND vip_site='N') GROUP BY dn ORDER BY COUNT(*) DESC"; 
						 }
						 
						$result_vip=mysql_query($query_vip);
						//$row=mysql_num_rows($result_vip);	
						
						while($rowsql=mysql_fetch_assoc($result_vip))
						{
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

		$text=$_REQUEST['text'];	
		if($_REQUEST['object_type']=='vvip'){
			$query1="SELECT text, alarm_number, object_name, dn, alarm_time, cancel_time, sup_info, ack_status FROM net_diag WHERE text LIKE '$text%' AND vip_site='Y' ORDER BY alarm_time";
		}		
		else{
			$query1="SELECT text, alarm_number, object_name, dn, alarm_time, cancel_time, sup_info, ack_status FROM net_diag WHERE text LIKE '$text%' ORDER BY alarm_time";
		}
		
		//$result1=mysql_query($query1);	
		$_SESSION[query_data] = $query1;		
		$result1=mysql_query($query1);
		while($rowsql1=mysql_fetch_assoc($result1))
		{
		?>
		<tr>
			<td align="center"><?php echo $rowsql1['text']; ?></td> 
			<td align="center"><?php echo $rowsql1['alarm_number']; ?></td>  
			<td align="center"><?php echo $rowsql1['object_name']; ?></td>						
			<td align="center"><?php echo $rowsql1['dn']; ?></td>
			<td align="center"><?php echo $rowsql1['alarm_time']; ?></td>	
			<td align="center"><?php 
									if($rowsql1['cancel_time']!=''){ 
										echo $rowsql1['cancel_time']; 
									} 
									else{
										echo "Active";
									} ?>
			</td>		
		
			<td align="center"><?php echo $rowsql1['sup_info']; ?></td>			
		</tr>
		<?php
		}
		?>
		</table>
		<script language="javascript" type="text/javascript">
		var table1_Props = 	{
					//exact_match: true,
					col_0:"select",
					col_1:"select",
					//col_2:"select",
					//col_3:"select",
					col_5:"select",						
					col_operation: "sum",
					alternate_rows: true,
					//col_width: ["150px","100px"],//prevents column width variations
					rows_counter: true,
					rows_counter_text: "Total No. of Rows: ",
					btn_reset: true,
					bnt_reset_text: "Clear all "
				};
		setFilterGrid( "table1",table1_Props );
		</script>
</div>		
</body>
</html>