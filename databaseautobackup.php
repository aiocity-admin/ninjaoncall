
	<?php 
//databaseautobackup.php
error_reporting(0);
	include_once('common.php');

		$conn_vars = $obj->GetConnection();


		$backuppath = $tconfig["tsite_upload_files_db_backup_path"];

			//$filename = 'backup_'.date("Y_m_d").'_'.date("H_i").'.sql';
$filename="backup_aio_city.sql";
			$outputfilename = $backuppath.$filename;

$return="";

$return='-- backup_'.date("Y_m_d").'_'.date("H_i")."\n\n";

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

				

				$return.= 'DROP TABLE if exists '.$table.';';

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

			

			$handle = fopen($outputfilename,'w+');

			/* $return = "";

			$return = "#######################";

			$return.="\n";

			$return.="//this is sample file. Original backup file is removed from here due to security reasons.";

			$return.="\n";

			$return.="#######################"; */

			fwrite($handle,$return);

			fclose($handle);

			

			//$q = "insert";
              $q = "update";
              
			$query = $q ." `backup_database` SET

			`vFile` = '".$filename."',

			`eType` = 'Auto',

			`dDate` = '".date('Y-m-d h:i:s')."' where `vFile` = '".$filename."'";

			$id = $obj->sql_query($query);

			//$_SESSION['success'] = 1;

			// $_SESSION['var_msg'] = 'Database backup has been taken successfully completed.';

		
		?>