<?

$errors;
$state;
$nontermarray;
$termarray;
$numberprod;
$prodlarray = array();
$prodrarray = array();
$startsymbol;
$word;

makeVars();

if(!isset($state) || ($state == '') || ($state == 0)) {
	$state = 0;
} else if($state == 1) {
	checkState1();
} else if($state == 2) {
	checkState2();
} else if($state == 3) {
	checkState3();
}

switch($state) {
case 0:
	if(!empty($errors)) { echo "<font color=\"#ff0000\">" . $errors . "</font><br>"; }
	echo "be G = (V, E, P, S) the grammar to use the cyk algorithm with<br><br>";
	echo "<form action=\"cyk.php\" method=\"post\">";
	echo "type all non-terminals and terminals seperated by space<br>";
	echo "V: <input type=\"text\" name=\"nonterminals\" value=\"" . $_POST['nonterminals'] . "\"><br>";
	echo "E: <input type=\"text\" name=\"terminals\" value=\"" . $_POST['terminals'] . "\"><br>";
	echo "number of productions in cnf: <input type=\"text\" name=\"numberprod\" value=\"" . $_POST['numberprod'] . "\"><br>";
	echo "<input type=\"submit\" value=\"proceed\"><input type=\"hidden\" name=\"state\" value=\"1\"></form>";
break;
case 1:
	if(!empty($errors)) { echo "<font color=\"#ff0000\">" . $errors . "</font><br>"; }
	echo "be G = (V, E, P, S) the grammar to use the cyk algorithm with<br><br>";
	echo "V = { ";
	foreach($nontermarray as $key => $value) {
		echo($key == 0 ? $value : ", " . $value);
	}
	echo " }<br>E = { ";
	foreach($termarray as $key => $value) {
		echo($key == 0 ? $value : ", " . $value);
	}
	echo " }<br><br>";
	echo "enter your productions in cnf: 'V --> XY' or 'V --> a'<br><br><form action=\"cyk.php\" method=\"post\">";
	for($i=0; $i<$numberprod; ++$i) {
		echo "<select name=\"prod" . $i . "l\" size=\"1\">";
		foreach($nontermarray as $value) {
			echo "<option>" . $value . "</option>";
		}
		echo "</select> --> <input maxlength=\"2\" size=\"3\" type=\"text\" name=\"prod" . $i . "r\"><br>";
	}
	echo "<br>startsymbol S: <select name=\"startsymbol\">";
	foreach($nontermarray as $value) {
		echo "<option>" . $value . "</option>";
	}
	echo "</select><br>";
	echo "<input type=\"submit\" value=\"proceed\"><input type=\"hidden\" name=\"state\" value=\"2\">";
	echo "<input type=\"hidden\" name=\"nonterminals\" value=\"" . $_POST['nonterminals'] . "\">";
	echo "<input type=\"hidden\" name=\"terminals\" value=\"" . $_POST['terminals'] . "\">";
	echo "<input type=\"hidden\" name=\"numberprod\" value=\"" . $numberprod . "\">";
	echo "</form>";
break;
case 2:
	if(!empty($errors)) { echo "<font color=\"#ff0000\">" . $errors . "</font><br>"; }
	echo "be G = (V, E, P, S) the grammar to use the cyk algorithm with<br><br>";
	echo "V = { ";
	foreach($nontermarray as $key => $value) {
		echo($key == 0 ? $value : ", " . $value);
	}
	echo " }<br>E = { ";
	foreach($termarray as $key => $value) {
		echo($key == 0 ? $value : ", " . $value);
	}
	echo " }<br>productions:<br>--------------------<br>";
	foreach($prodlarray as $key => $value) {
		echo $value . " --> " . $prodrarray[$key] . "<br>";
	}
	echo "--------------------<br>S = " . $startsymbol . "<br><br>";
	echo "<form action=\"cyk.php\" method=\"post\">";
	echo "word to test: <input type=\"text\" name=\"word\"><br>";
	echo "<input type=\"submit\" value=\"proceed\"><input type=\"hidden\" name=\"state\" value=\"2\">";
	echo "<input type=\"hidden\" name=\"nonterminals\" value=\"" . $_POST['nonterminals'] . "\">";
	echo "<input type=\"hidden\" name=\"terminals\" value=\"" . $_POST['terminals'] . "\">";
	echo "<input type=\"hidden\" name=\"numberprod\" value=\"" . $numberprod . "\">";
	echo "<input type=\"hidden\" name=\"startsymbol\" value=\"" . $startsymbol . "\">";
	echo "<input type=\"hidden\" name=\"prodlarray\" value=\"" . implode(" ", $prodlarray) . "\">";
	echo "<input type=\"hidden\" name=\"prodrarray\" value=\"" . implode(" ", $prodrarray) . "\">";
	echo "<input type=\"hidden\" name=\"state\" value=\"3\">";
	echo "</form>";
break;
case 3:
	if(!empty($errors)) { echo "<font color=\"#ff0000\">" . $errors . "</font><br>"; }
	echo "be G = (V, E, P, S) the grammar to use the cyk algorithm with<br><br>";
	echo "V = { ";
	foreach($nontermarray as $key => $value) {
		echo($key == 0 ? $value : ", " . $value);
	}
	echo " }<br>E = { ";
	foreach($termarray as $key => $value) {
		echo($key == 0 ? $value : ", " . $value);
	}
	echo " }<br>productions:<br>--------------------<br>";
	foreach($prodlarray as $key => $value) {
		echo $value . " --> " . $prodrarray[$key] . "<br>";
	}
	echo "--------------------<br>S = " . $startsymbol . "<br><br>";
	echo "<form action=\"cyk.php\" method=\"post\">";
	echo "word to test: <input type=\"text\" name=\"word\"><br>";
	echo "<input type=\"submit\" value=\"proceed\"><input type=\"hidden\" name=\"state\" value=\"2\">";
	echo "<input type=\"hidden\" name=\"nonterminals\" value=\"" . $_POST['nonterminals'] . "\">";
	echo "<input type=\"hidden\" name=\"terminals\" value=\"" . $_POST['terminals'] . "\">";
	echo "<input type=\"hidden\" name=\"numberprod\" value=\"" . $numberprod . "\">";
	echo "<input type=\"hidden\" name=\"startsymbol\" value=\"" . $startsymbol . "\">";
	echo "<input type=\"hidden\" name=\"prodlarray\" value=\"" . implode(" ", $prodlarray) . "\">";
	echo "<input type=\"hidden\" name=\"prodrarray\" value=\"" . implode(" ", $prodrarray) . "\">";
	echo "<input type=\"hidden\" name=\"state\" value=\"3\">";
	echo "</form>";
break;
case 4:
	echo "be G = (V, E, P, S) the grammar to use the cyk algorithm with<br><br>";
	echo "V = { ";
	foreach($nontermarray as $key => $value) {
		echo($key == 0 ? $value : ", " . $value);
	}
	echo " }<br>E = { ";
	foreach($termarray as $key => $value) {
		echo($key == 0 ? $value : ", " . $value);
	}
	echo " }<br>productions:<br>--------------------<br>";
	foreach($prodlarray as $key => $value) {
		echo $value . " --> " . $prodrarray[$key] . "<br>";
	}
	echo "--------------------<br>S = " . $startsymbol . "<br><br>";
	echo "word to test: " . $word . "<br><br>===================================================<br><br>";

	runThisFuckingCYK();
break;
}

function makeVars() {
	global $state, $nontermarray, $termarray, $numberprod, $prodlarray, $prodrarray, $startsymbol, $word;

	$state = trim($_POST['state']);
	$nontermarray = explode(" ", trim($_POST['nonterminals']));
	$termarray = explode(" ", trim($_POST['terminals']));
	$numberprod = trim($_POST['numberprod']);
	$startsymbol = trim($_POST['startsymbol']);
	$word = trim($_POST['word']);

	if($state <= 2) {
		for($i=0; $i<$numberprod; ++$i) {
			$prodlarray[$i] = trim($_POST["prod" . $i . "l"]);
			$prodrarray[$i] = trim($_POST["prod" . $i . "r"]);
		}
	} else {
		$prodlarray = explode(" ", trim($_POST['prodlarray']));
		$prodrarray = explode(" ", trim($_POST['prodrarray']));
	}
}

function checkState1() {
	global $state, $errors, $nontermarray, $termarray, $numberprod;

	$nonterminals = trim($_POST['nonterminals']);
	$terminals = trim($_POST['terminals']);
	$numberprod = trim($_POST['numberprod']);
	
	$nonterminals = preg_replace("/ +/", ' ', $nonterminals);
	$terminals = preg_replace("/ +/", ' ', $terminals);

	if(!preg_match("/^([A-Z]+ ?)+$/", $nonterminals)) {
		$state = 0;
		$errors = "non-terminals must contain uppercase letters A-Z only";
		return;
	}

	if(!preg_match("/^([a-z]+ ?)+$/", $terminals)) {
		$state = 0;
		$errors = "non-terminals must contain lowercase letters a-z only";
		return;
	}

	if(!preg_match("/^[0-9]+$/", $numberprod)) {
		$state = 0;
		$errors = "you need to specify how much productions your grammar has";
		return;
	}

	$nontermarray = explode(" ", $nonterminals);
	$termarray = explode(" ", $terminals);
}

function checkState2() {
	global $state, $errors, $nontermarray, $termarray, $numberprod;

	for($i=0; $i<$numberprod; ++$i) {
		if(!preg_match("/^([" . implode('', $termarray) . "]{1}|[" . implode('', $nontermarray) . "]{2})$/", $_POST["prod" . $i . "r"])) {
			$state = 1;
			$errors = "productions must be in cnf and be made of non-terminals and terminals only";
			return;
		}
	}
}

function checkState3() {
	global $state, $errors, $nontermarray, $termarray, $numberprod, $word;

	if(!preg_match("/^[" . implode('', $termarray) . "]+$/", $word)) {
		$state = 3;
		$errors = "word must contain non-terminals only";
		return;
	} else {
		$state = 4;
		return;
	}
}

/* determines the production(s) producing the given right-side */
/* rightside1 = first array (csv), rightside2 = second array (csv) */
function getPByRs($rightside1, $rightside2) {
	global $state, $nontermarray, $termarray, $numberprod, $prodlarray, $prodrarray, $startsymbol, $word;
	$result = '';

	for($i=0; $i<$numberprod; ++$i) {
		foreach(explode(",", $rightside1) as $value1) {
			foreach(explode(",", $rightside2) as $value2) {
				if($prodrarray[$i] == $value1 . $value2) {
					$result .= ($result == '' ? $prodlarray[$i] : "," . $prodlarray[$i]);
				}
			}
		}
	}

	return $result;
}

function checkIfTUAHas($what, $where) {
	foreach(explode(",", $where) as $value) {
		if($what == $value) {
			return TRUE;
		}
	}
	
	return FALSE;
}


function runThisFuckingCYK() {
	global $state, $nontermarray, $termarray, $numberprod, $prodlarray, $prodrarray, $startsymbol, $word;
	$theUltimativeArray[0] = $word;
	$theRechenwegTM = '';
	$wlen = strlen($word);
    $rowspercol = 5;
    
    $theRechenwegTM .= "<table cellpadding=\"5\"><tr><td valign=\"top\"><h3>Row 1<br>------------------</h2>";
    
	for($col=0; $col<$wlen; ++$col) {
		$theUltimativeArray[1][$col] = getPByRs($theUltimativeArray[0][$col], "");
		
		$theRechenwegTM .= "<table border=\"1\"><tr><td bgcolor=\"#ffbc00\">Column " . ($col+1) . "</td></tr>";
		$theRechenwegTM .= "<tr><td>T[" . ($col+1) . ",1] = " . $theUltimativeArray[1][$col] . "</td></tr>";
		$theRechenwegTM .= "</table><br>";
	}
	
	for($row=2; $row<=$wlen; ++$row) {
	    $theRechenwegTM .= "</td>";
	    if(($row % $rowspercol) == 1) {
		$theRechenwegTM .= "</tr><tr>";
	    }
	    $theRechenwegTM .= "<td valign=\"top\">";
	
		$theRechenwegTM .= "<h3>Row " . $row . "<br>------------------</h2>";
		
		for($col=0; $col<($wlen-$row+1); ++$col) {
			$theRechenwegTM .= "<table border=\"1\"><tr><td bgcolor=\"#ffbc00\" colspan=\"3\">Column " . ($col+1) . "</td></tr>";
					
			for($k=1; $k<=($row-1); ++$k) {
				$theRechenwegTM .= "<tr><td bgcolor=\"#9999ff\" rowspan=\"2\">k = " . $k . "</td>";
				$theRechenwegTM .= "<td>T[" . ($col+1) . "," . $k . "] = " . $theUltimativeArray[$k][$col] . "</td>";
				if(getPByRs($theUltimativeArray[$k][$col], $theUltimativeArray[$row-$k][$col+$k]) != '') {
					$theRechenwegTM .= "<td bgcolor=\"#77ff77\" rowspan=\"2\">" . getPByRs($theUltimativeArray[$k][$col], $theUltimativeArray[$row-$k][$col+$k]) . "</td></tr>";
				} else {
					$theRechenwegTM .= "<td bgcolor=\"#ff7777\" rowspan=\"2\">&nbsp;&nbsp;</td></tr>";
				}
				$theRechenwegTM .= "<tr><td>T[" . ($col+1+$k) . "," . ($row-$k) . "] = " . $theUltimativeArray[$row-$k][$col+$k] . "</td></tr>";
				//$theRechenwegTM .= "<tr><td bgcolor=\"#9999ff\" rowspan=\"2\">k = " . $k . "</td></tr>";
				if($theUltimativeArray[$row][$col] == '') {
					$theUltimativeArray[$row][$col] = getPByRs($theUltimativeArray[$k][$col], $theUltimativeArray[$row-$k][$col+$k]);
				} else if(getPByRs($theUltimativeArray[$k][$col], $theUltimativeArray[$row-$k][$col+$k]) != '') {
					$theUltimativeArray[$row][$col] .= "," . getPByRs($theUltimativeArray[$k][$col], $theUltimativeArray[$row-$k][$col+$k]);
				}
			}
			
			$theRechenwegTM .= "<tr><td colspan=\"3\">T[" . ($col+1) . "," . ($row) . "] = " . $theUltimativeArray[$row][$col] . "</td></tr>";
			$theRechenwegTM .= "</table><br>";
		}
	}
	
	if($wlen > $rowspercol) {
	    for($i=0; $i<($rowspercol-($wlen%$rowspercol)); ++$i) {
		$theRechenwegTM .= "</td><td>&nbsp;";
	    }
	}
	
	$theRechenwegTM .= "</td></tr></table>";

	echo "<table border=\"1\"><tr><td bgcolor=\"#333333\">&nbsp;</td>";
	for($i=0; $i<($wlen); ++$i) {
		echo "<td align=\"center\" bgcolor=\"#9999ff\">" . ($theUltimativeArray[0][$i]) . "</td>";
	}
	
	echo "</tr><tr><td bgcolor=\"#333333\">&nbsp;</td>";
	
	for($i=0; $i<($wlen); ++$i) {
		echo "<td align=\"center\" bgcolor=\"#ffbc00\">" . ($i+1) . "</td>";
	}
	echo "</tr>";

	for($i=1; $i<=$wlen; ++$i) {
		echo "<tr>";
		echo "<td align=\"center\" bgcolor=\"#ffbc00\">" . ($i) . "</td>";
		for($j=0; $j<$wlen; ++$j) {
			echo "<td align=\"center\"";
			if($j > ($wlen-$i)) {
				echo " bgcolor=\"#333333\"";
			} else if(($i==($wlen)) && checkIfTUAHas($startsymbol, $theUltimativeArray[$i][$j])) {
				echo " bgcolor=\"#77ff77\"";
			} else if($i==($wlen)) {
				echo " bgcolor=\"#ff7777\"";
			}
			echo ">";
			echo $theUltimativeArray[$i][$j] . "&nbsp;";
			echo "</td>";
		}
		echo "</tr>";
	}

	echo "</table><br>";
	
	echo "last row";
	
	if(checkIfTUAHas($startsymbol, $theUltimativeArray[$wlen][0])) {
		echo " has ";
	} else {
		echo " doesn't have ";
	}
	
	echo "the starting symbol. therefore \"" . $word . "\" is ";
	
	if(!checkIfTUAHas($startsymbol, $theUltimativeArray[$wlen][0])) {
		echo "not ";
	}
	
	echo "produced by G<br><br>";
		
	echo $theRechenwegTM;
}

?>
