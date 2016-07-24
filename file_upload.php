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
        date_default_timezone_set('Asia/Dhaka');

        if (isset($_POST['Upload_File'])) {
            $file_name = ($_FILES['file']['name']);
            $file_size = intval($_FILES['file']['size']);
            if ($file_size > 0) {
                move_uploaded_file($_FILES["file"]["tmp_name"], "raw_files/" . $file_name);

                echo "Successfully Uploaded: " . "<b>" . $file_name . "</b><br/><br/>";
                echo "File Size: " . $file_size;
            } else {
                echo "File Uploading Failed!!!";
            }
        }

        if (isset($_POST['Update_Alarm'])) {

            include 'include/db.php';

            $cs_date = date("m/d/y G.i:s<br>", time());
            $update_date = mysql_query("UPDATE update_date SET alarm_update='$cs_date' ");

            $del_table = mysql_query("TRUNCATE TABLE net_diag");

            $input = "raw_files/alarm_all.csv";


            $lines = file("$input");

            foreach ($lines as $line_num => $line) {
                if ($line_num > 0) {
                    $arr = explode(",", $line);
                    $dn = trim((string) $arr[1]);
                    $original_severity = trim((string) $arr[4]);
                    $alarm_time = trim((string) $arr[5]);
                    $cancel_time = trim((string) $arr[6]);
                    $ack_status = trim((string) $arr[8]);
                    $ack_time = trim((string) $arr[9]);
                    $acked_by = trim((string) $arr[10]);
                    $alarm_number = trim((string) $arr[11]);
                    $text = trim((string) $arr[15]);

                    $sup_info = trim((string) $arr[22]);

                    $object_name = trim((string) $arr[46]);
                    $vip_site = 'N';
                    $sql2 = sprintf("INSERT INTO net_diag  VALUES ('$dn', '$original_severity', '$alarm_time', '$cancel_time', '$ack_status', '$ack_time', '$acked_by', '$alarm_number', '$text', '$sup_info', '$object_name', '$vip_site')");
                    $result2 = mysql_query($sql2);
                }
            }
            if ($result2) {
                $query_vip = "UPDATE net_diag, sp_case SET vip_site='Y' WHERE sp_case.object_name=net_diag.object_name";
                $result_vip = mysql_query($query_vip);

                echo "<b><font color=GREEN>SUCCESSFULLY UPDATED ALARM DATABASE</font></b>";
            } else {
                echo "<b><font color=red>SORRY !!! ALARM DATABASE UPDATE FAILED</font></b>";
            }
        }
    ?>


    <form method="post" enctype="multipart/form-data" action="file_upload.php">
        <p> Select File: <input name="file" type="file">
            <input type="submit" name="Upload_File" value="Upload File"> 
        </p>	
    </form>	

    <form method="post" enctype="multipart/form-data" action="file_upload.php">
        <p> After Successfully Uploaded <b>alarm_all.csv </b> Files Click Below button: <br/>
            <input type="submit" name="Update_Alarm" value="Update Alarm">
        </p>
    </form>		

</body>
</html>		