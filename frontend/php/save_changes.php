<?php
$mac = $_POST['mac_edit'];
$old_mac = $_POST['old_mac'];
$mgroup = $_POST['mgroup'];
$id = $_POST['id_edit'];
$description = $_POST['description_edit'];
$file = $_POST['file'];
$scripts = scandir("./scripts/$mgroup/");
$count_scripts = count($scripts);
if (!preg_match('/^(?:[0-9a-fA-F]{2}[:;.]?){6}$/', $mac)) {
	$status = "$mac is not a valid mac address.|red";
} else {
	$data = "$mac|$id|$description";
	for ($x = 0; $x < $count_scripts; $x++) {
		if ($scripts[$x] != "." && $scripts[$x] != "..") {
			if ($scripts[$x] != 'custom') {
				$post = $_POST[$scripts[$x]];
			}
			if ($post == "1" && $scripts[$x] != 'custom') {
				// Step back up three dirs for the symlink.
				symlink("../../../scripts/$mgroup/$scripts[$x]", "./machines/$mgroup/$mac/$scripts[$x]");
			} elseif ($scripts[$x] != 'custom') {
				unlink("./machines/$mgroup/$mac/$scripts[$x]");
			}
		}
	}
	$status = "Machine successfully updated.|green";
	$fh = fopen($file, 'w');
	fwrite($fh,$data);
	fclose($fh);
	if ($old_mac != $mac) {
		rename("./machines/$mgroup/$old_mac/", "./machines/$mgroup/$mac/");
	}
}
echo "<form id=\"form\" action=\"manage.php?mgroup=$mgroup\" method=\"POST\">
		<input type=\"hidden\" name=\"status\" value=\"$status\">
	</form>
	<script>document.getElementById(\"form\").submit();</script>";
?>
