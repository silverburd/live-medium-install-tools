<?php
include "header.php";
echo "<script src=\"./jquery/status.js\"></script>";
status();
if (!empty($_GET['mgroup'])) {
	$mgroup = $_GET['mgroup'];
} else {
	$mgroup = "";
}
$scripts = scandir("./scripts/$mgroup/");
$count_scripts = count($scripts);
echo "<body>
	<div id=\"main_text\">";
	if (isset($_GET['mgroup'])) {
		echo "<h1>Manage Machines for Group <i>$mgroup</i></h1>";
	} else {
		echo "<h1>Manage Machines</h1>";
	}
	if (!empty($mgroup)) {
	echo "<form action=\"". $_SERVER['PHP_SELF']."?mgroup=$mgroup\" method=\"post\">
		<div id=\"text_input\">
			<input class=\"input_mac\" type=\"text\" name=\"mac\" maxlength=\"17\" placeholder=\"Mac Address\">
			<input class=\"input_id\" type=\"text\" name=\"id\" placeholder=\"Machine ID\">
			<input type=\"text\" name=\"description\" placeholder=\"Machine Description\">
		</div>
		<div id=\"checkbox_script\">";
		for ($x = 0; $x < $count_scripts; $x++) {
			if ($scripts[$x] != "." && $scripts[$x] != "..") {
				if (is_link("./scripts/$mgroup/$scripts[$x]") && $scripts[$x] != 'custom') {
					echo "$scripts[$x]<input type=\"checkbox\" name=\"$scripts[$x]\" value=\"1\"> ";
				}
			}
		}
		echo "</div>
		<input type=\"submit\" name=\"add\" value=\"Add Machine\">
	</form>";

	if (!empty($_POST['mac'])) {
		$mac = strtolower(htmlspecialchars($_POST['mac']));
		$id = strtoupper(htmlspecialchars($_POST['id']));
		$description = htmlspecialchars($_POST['description']);
		$data = "$mac|$id|$description";

		if (is_dir("./machines/$mgroup/$mac") && !is_dir("./machines/ ")) {
			echo "<h3> <font color=\"red\">Machine exist.</font></h3>";
		} else {
			if (!preg_match('/^(?:[0-9a-fA-F]{2}[:;.]?){6}$/', $mac)) {
				echo "<h3><font color='red'>Invalid Mac Address</font></h3>";
			} else {
				mkdir("./machines/$mgroup/$mac/", 0777);
				$fh = fopen("./machines/$mgroup/$mac/info.txt", 'w');
				fwrite($fh,$data);
				fclose($fh);
				for ($x = 0; $x < $count_scripts; $x++) {
					if ($scripts[$x] != "." && $scripts[$x] != "..") {
						$post = $_POST[$scripts[$x]];
						if ($post == "1") {
							// Step back up three dirs for the symlink.
							symlink("../../../scripts/$mgroup/$scripts[$x]", "./machines/$mgroup/$mac/$scripts[$x]");
						}
					}
				}
			}
		}
	}
	manage_table($mgroup);
	log_pop();
} else {
	$group= scandir("./machines/");
	$group_count = count($group);

	for ($g = 0; $g < $group_count; $g++) {
		if ($group[$g]!= "." && $group[$g] != "..") {
			echo "<h1><a class=\"a_h1\" href=\"manage.php?mgroup=$group[$g]\">$group[$g]</a></h1>";
			manage_table($group[$g]);
		}
	}
	log_pop();
}
?>
<script>
function deleteConfirm(e) {
// I need to know which submit button was pressed.
	if (e.value=='X'){
		var answer = confirm("Are you sure you want to delete this machine?")
		if (answer){
			return true;
		} else{
			return false;
		}
	}
}
</script>
<?php
include "footer.php";
?>
