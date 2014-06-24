<?php
include "header.php";
if (isset($_GET['action'])) {
	header('Location: group.php');
}
?>
<script src="./jquery/checkall.js"></script>
<script src="./jquery/code_pop.js"></script>
<script src="./jquery/new_script.js"></script>
<script src="./jquery/tabs.js"></script>
<body>
	<div id="main_text">
		<div id="script_pannel">
			<h3 class="pannel_head">Admin</h3>
			<ul>
				<?php
				$group_script = scandir("./library/");
				$count = count($group_script);
				for ($x = 0; $x < $count; $x++) {
					if ($group_script[$x] != "." && $group_script[$x] != "..") {
						echo "<li class=\"admin_li\"><a class=\"admin_a\" href=\"#$group_script[$x]\" tabIndex=\"$x\">$group_script[$x]</a></li>";
					}
				}
				?>
			</ul>
		</div>
		<?php
		// scan library folder for groups
		$script_group = scandir("./library/");
		$count_group = count($script_group);
		// $x represents the array number for each group in library
		for ($x = 0; $x < $count_group; $x++) {
			if ($script_group[$x] != "." && $script_group[$x] != "..") {
				echo "<div id=\"$script_group[$x]\" class=\"tabs\">
						<div id=\"library\">
							<h1>$script_group[$x]</h1>
							<form action=\"admin_changes.php\" method=\"POST\">
								<input type=\"hidden\" name=\"group\" value=\"$script_group[$x]\">
								<table>
									<tr>
										<th><input type=\"checkbox\" id=\"select_all\"></th>
										<th>Script</th>
										<th>Code</th>
										<th>Action</th>
									</tr>";
				// scan group folder in library to populate all possible scripts
				$scripts = scandir("./library/$script_group[$x]");
				$count_scripts = count($scripts);
				for ($s = 0; $s < $count_scripts; $s++) {
					if ($scripts[$s] != "." && $scripts[$s] != "..") {
						// $script_content gets the content of each script
						$script_content = file_get_contents("./library/$script_group[$x]/$scripts[$s]");
						echo "<tr>";
						if (is_link("./scripts/$script_group[$x]/$scripts[$s]")) {
							echo "<td class=\"td_center\"><input class=\"checkbox1\" type=\"checkbox\" name=\"$scripts[$s]\" value=\"1\" checked></td>";
						} else {
							echo "<td class=\"td_center\"><input class=\"checkbox1\" type=\"checkbox\" name=\"$scripts[$s]\" value=\"1\"></td>";
						}
						echo "<td>$scripts[$s]</td>";
						if (strlen($script_content) > 40) {
							echo "<td><a class=\"a_code\" rowid=\"$s\" href=\"#$scripts[$s]\">".substr($script_content,0,40)."....</a></td>";
						} else {
							echo "<td><a class=\"a_code\" rowid=\"$s\" href=\"#$scripts[$s]\">$script_content</a></td>";
						}
						echo "<td><button rowid=\"edit$s\" class=\"edit_button\">&#9998;</button></td>
						</tr>";
						// replaces each newline call with a html break tag
						$script_content_html = str_replace("\n","<br />", $script_content);
						echo "<div rowid=\"$s\" class=\"backlight\">
								<div class=\"code_box\">
									<div id=\"header_pop\">
										<h2>Script</h2>
										<p class=\"exit\">X</p>
									</div>
									<div id=\"content_pop\">
										$script_content_html
									</div>
								</div>
							</div>";
						// this is the edit form pop up
						echo "<div id=\"edit_wrap\">
								<div class=\"edit\" rowid=\"edit$s\">
									<h1 class=\"custom_h1\">Edit Script</h1>
										<form action=\"admin_changes.php\" method=\"POST\">
											<textarea name=\"edit_script\">$script_content</textarea>
											<input type=\"hidden\" name=\"edit_file\" value=\"$script_group[$x]/$scripts[$s]\">
											<input class=\"submit\" type=\"submit\" name=\"submit\" value=\"Submit Changes\">
											<input class=\"delete\" type=\"submit\" name=\"submit\" value=\"Delete Script\">
											<button class='cancel'>Cancel</button>
										</form>
								</div>
							</div>";
					}
				}
				echo "</table>
						<button rowid=\"form$x\" class=\"new_script_button\">+</button>
						<input type=\"submit\" name=\"submit\" value=\"Submit\">
					</form>
				</div>
			</div>";
			echo "<div id=\"add_script\" rowid=\"form$x\">
					<div id=\"new_script\">
						<h1 class=\"custom_h1\">New Script</h1>
						<form action=\"new_script.php\" method=\"POST\">
							<input class=\"script_name\" type=\"text\" name=\"file\" placeholder=\"Script Name\">
							<textarea name=\"new_script\" class=\"textarea_new_script\"></textarea>
							<input type=\"hidden\" name=\"dir\" value=\"./library/$script_group[$x]/\">
							<input class=\"add_script_submit\" type=\"submit\" value=\"Add Script\">
							<button class=\"cancel\" rowid=\"form$x\">Cancel</button>
						</form>
					</div>
				</div>";
			// end of if statment that excludes . and ..
			}
		// end of library group scan
		}
		?>
	</div>
<?php
include "footer.php";
?>
