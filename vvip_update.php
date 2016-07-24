<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml>
      
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Nokia Daily Health Check Portal</title>	
    <link rel='stylesheet' type='text/css' href='css/style.css'/>
</head>

<body>
    <?php
        ini_set("memory_limit", "1000M");
        set_time_limit(0);

        if (isset($_POST['Upload_File'])) {
            $file_name = ($_FILES['file']['name']);
            $file_size = intval($_FILES['file']['size']);
            if ($file_size > 0) {
                move_uploaded_file($_FILES["file"]["tmp_name"], "raw_files/vvip/" . $file_name);

                echo "Successfully Uploaded: " . "<b>" . $file_name . "</b><br/><br/>";
                echo "File Size: " . $file_size;
            } else {
                echo "File Uploading Failed!!!";
            }
        }

        if (isset($_POST['Update_Now'])) {

            include 'include/db.php';

            $del_table = mysql_query("TRUNCATE TABLE sp_case");

            $input = "raw_files/vvip/vvip.csv";
            $lines = file("$input");

            foreach ($lines as $line_num => $line) {
                if ($line_num >= 0) {
                    $arr = explode(",", $line);

                    $object_name = trim((string) $arr[0]);


                    $sql2 = sprintf("INSERT INTO sp_case VALUES ('$object_name')");

                    $result2 = mysql_query($sql2);
                }
            }
            if ($result2) {
                echo "<b><font color=GREEN>SUCCESSFULLY UPDATED VVIP SITE LIST</font></b>";
            } else {
                echo "<b><font color=red>SORRY !!! VVIP SITE LIST UPDATE FAILED</font></b>";
            }
        }
    ?>


    <form method="post" enctype="multipart/form-data" action="vvip_update.php">
        <p> Select File: <input name="file" type="file">
            <input type="submit" name="Upload_File" value="Upload File"> 
        </p>	
    </form>	

    <form method="post" enctype="multipart/form-data" action="vvip_update.php">
        <p>
            <font color="blue"> Note: Insert only the vvip sites in first column of an excel sheet without any column header and save as .CSV </font color> <br/>
            After Successfully Uploaded <b>vvip.csv </b> File Click Below button: <br/>
            <input type="submit" name="Update_Now" value="Update Now">
        </p>
    </form>	

    <div class="top_table">
        <table border=1>
            <h3> List of <font color=red> VVIP Sites:</font></h3>
            <tr>
            <center>
                <th style="background: #91C910">Site ID </th>
            </center>
            </tr>			
            <?php
                include 'include/db.php';
                $query = "SELECT object_name FROM sp_case";
                $result = mysql_query($query);
                $tot_rows = mysql_num_rows($result);
                echo "Total No. of VVIP sites: " . $tot_rows;
                while ($rowsql = mysql_fetch_assoc($result)) {
            ?>

            <tr>  	
                <td class=td1 align="center"><?php echo $rowsql['object_name']; ?></td>															
            </tr>

            <?php
                }
            ?>
        </table>		
</body>
</html>		