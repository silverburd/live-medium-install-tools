<?php
/* scans_th
Scans the $dir and prints each item formated into table header,
and excludes . and .. file.
*/
function scan_th($dir) {
	$dir = scandir($dir);
	$count = count($dir);
	for ($x = 0; $x < $count; $x++) {
		if ($dir[$x] != "." && $dir[$x] != "..") {
			echo "<th class=\"e\">".$dir[$x]."</th>";
		}
	}
}

/* check_box
Scans $file for files it contains and scans $dir to print out each item with
a check or no check, excludes . and .. file.
*/
function check_box($file, $dir) {
	$dir = scandir($dir);
	$count = count($dir);
	for ($loop_count = 0; $loop_count < $count; $loop_count++) {
		if ($dir[$loop_count] != "." && $dir[$loop_count] != "..") {
			if (is_link("$file/".$dir[$loop_count]) || is_file("$file/".$dir[$loop_count])) {
				echo "<td class=\"e\">&#10003;</td>";
			} else {
				echo "<td class=\"e\"></td>";
			}
		}
	}
}

/* view_table

*/
function view_table($mgroup) {
	$dir = "./machines/$mgroup/";
	# read dir and put dirs in array
	$files = scandir($dir);
	$count = count($files);
	echo "<table style=\"float:none\">
				<tr>
					<th>Mac Adress</th>
					<th>Machine ID</th>
					<th>Description</th>";
					scan_th("./scripts/$mgroup");
			echo "</tr>";

			$dir = "./machines/$mgroup/";
			# read dir and put dirs in array
			$files = scandir($dir);
			# count number of dirs
			$count = count($files);

			for ($i = 0; $i < $count; $i++) {
				if ($files[$i] != "." && $files[$i] != "..") {
					$file = "./machines/$mgroup/".$files[$i];
					$file_array = file("$file/info.txt");
					$dir_array = explode("|", $file_array[0]);

					echo "<tr>";
					echo "<td class=\"a\">".$dir_array[0]."</td>
						<td class=\"b\">".$dir_array[1]."</td>
						<td class=\"d\">".$dir_array[2]."</td>";
					check_box("./machines/$mgroup/$files[$i]", "./scripts/$mgroup/");
					echo "</tr>";
				}
			}
			echo "</table>";
}

/* manage_table

*/
function manage_table($mgroup) {
	echo "<script src=\"./jquery/log.js\"></script>";
	echo "<table>
		<tr>
			<th>Mac Adress</th>
			<th>Machine ID</th>
			<th>Description</th>
			<th>Phone Home</th>";
			scan_th("./scripts/$mgroup");
			echo "<th>Edit</th>
		</tr>";

	$dir = "./machines/$mgroup/";
	# read dir and put dirs in array
	$files = scandir($dir);
	# count number of dirs
	$count = count($files);

	for ($i = 0; $i < $count; $i++) {
		if ($files[$i] != "." && $files[$i] != "..") {
			$file = "./machines/$mgroup/".$files[$i];
			$file_array = file("$file/info.txt");
			$dir_array = explode("|", $file_array[0]);
			$count_array = count($dir_array);
			echo "<tr>";
			echo "<td class=\"a\">".$dir_array[0]."</td>
				<td class=\"b\">".$dir_array[1]."</td>
				<td class=\"d\">".$dir_array[2]."</td>";
				if (is_file("$file/log.txt")) {
					$log_array = file("$file/log.txt");
					$log_last = $log_array[count($log_array)-1];
					$log = explode("\t", "$log_last");
					echo "<td><a class=\"log_link\" href=\"$file/log.txt\">$log[0]</a></td>";
				} else {
					echo "<td><center>???????<center></td>";
				}
				check_box("./machines/$mgroup/$files[$i]","./scripts/$mgroup/");
				echo "<td>
					<form class='edit_form' action='edit.php' method='POST'>
						<input type='hidden' name='mac' value='".$dir_array[0]."'>
						<input type='hidden' name='id' value='".$dir_array[1]."'>
						<input type='hidden' name='description' value='".$dir_array[2]."'>
						<input type='hidden' name='file' value='".$file."'>
						<input type='hidden' name='mgroup' value='".$mgroup."'>
						<input type='submit' name='submit' value='&#9998;'>
						<input type='submit' name='submit' onclick='return deleteConfirm(this)' value='X'>
					</form>
				</td>
			</tr>";
		}
	}
	echo "</table>";
}
function log_pop() {
	echo "<div class=\"backlight\">
				<div class=\"code_box\">
					<div id=\"header_pop\">
						<h2>Log</h2>
						<p class=\"exit\">X</p>
					</div>
					<div id=\"content_pop\" style=\"word-wrap: break-word; white-space: pre-wrap\">
					</div>
				</div>
			</div>";
}
function status() {
	if (isset($_POST['status'])) {
		$status = $_POST['status'];
		$status = explode("|", $status);
		if ($status[1] == "red") {
			echo "<div id=\"status_red\">$status[0]</div>";
		} else {
			echo "<div id=\"status_green\">$status[0]</div>";
		}
	}
}
?>
