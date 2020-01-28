<?php
include_once('../common.php');

if($BACKUP_ENABLE == 'Yes') {
	if(date('H') == $BACKUP_TIME) {
		$conn_vars = $obj->GetConnection();

		$tables = array();
		$result = mysqli_query($conn_vars,'SHOW TABLES');
		while($row = mysqli_fetch_row($result))
		{
			$tables[] = $row[0];
		}

		foreach($tables as $table)
		{
			$result = mysqli_query($conn_vars,'SELECT * FROM '.$table);
			$num_fields = mysqli_num_fields($result);
			
			$return.= 'DROP TABLE '.$table.';';
			$row2 = mysqli_fetch_row(mysqli_query($conn_vars,'SHOW CREATE TABLE '.$table));
			$return.= "\n\n".$row2[1].";\n\n";
			
			for ($i = 0; $i < $num_fields; $i++) 
			{
				while($row = mysqli_fetch_row($result))
				{
					$return.= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<$num_fields; $j++) 
					{
						$row[$j] = addslashes($row[$j]);
						$row[$j] = ereg_replace("\n","\\n",$row[$j]);
						if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
						if ($j<($num_fields-1)) { $return.= ','; }
					}
					$return.= ");\n";
				}
			}
			$return.="\n\n\n";
		}

		//save file
		$backuppath = $tconfig["tsite_upload_files_db_backup_path"];
		$filename = 'backup_'.date("Y_m_d").'_'.date("H_i").'.sql';
		$outputfilename = $backuppath.$filename;
		$handle = fopen($outputfilename,'w+');
		fwrite($handle,$return);
		fclose($handle);

		$q = "insert";
		$query = $q ." `".$tbl_name."` SET
			`vFile` = '".$filename."',
			`eType` = 'Manual',
			`dDate` = '".date('Y-m-d h:i:s')."'";
		$id = $obj->sql_query($query);
		exit();
	}
}