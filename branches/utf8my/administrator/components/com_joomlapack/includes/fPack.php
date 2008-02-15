<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/copyleft/gpl.html GNU/GPL, смотрите LICENSE.php
* Joostina! - свободное программное обеспечение. Эта версия может быть изменена
* в соответствии с Генеральной Общественной Лицензией GNU, поэтому возможно
* её дальнейшее распространение в составе результата работы, лицензированного
* согласно Генеральной Общественной Лицензией GNU или других лицензий свободных
* программ или программ с открытым исходным кодом.
* Для просмотра подробностей и замечаний об авторском праве, смотрите файл COPYRIGHT.php.
*/
require(dirname(__FILE__).'/../../../die.php');

$task = mosGetParam( $_REQUEST, 'task', '' );
$act = mosGetParam( $_REQUEST, 'act', 'default' );

global $JPConfiguration, $option, $JPLang;
global $mosConfig_absolute_path, $mosConfig_live_site;

$siteRoot = $mosConfig_absolute_path;

?>
<table class="adminheading">
	<tr>
		<th class="info" nowrap rowspan="2">
			<?php echo $JPLang['common']['jptitle']; ?>
		</th>
	</tr>
	<tr>
		<td nowrap><h2><?php echo $JPLang['cpanel']['pack']; ?></h2></td>
	</tr>
</table>
	<script type="text/JavaScript">
	/*
	 * (S)AJAX Library code
	 */
<?php
	sajax_show_javascript();
?>
	/*
	 * (S)AJAX Error Trap and Reporting
	 */

	// Variables used by the detection code
	var tElapsed = 0; // Seconds elapsed since timer start
	var tStart  = null; // Time the timer started
	var timerID = 0;
	var CUBEArray = null; // The latest CUBEArray returned
	var LastTimestamp = null; // The latest timestamp the code knows about
	var GUItimerID = null;

	var DoDebug = false;

	// Assign the error handler
	sajax_fail_handle = SAJAXTrap;

	function WriteDebug( myString )
	{
		if(DoDebug) {
			document.getElementById("Debug").innerHTML += myString;
		}
	}

	// Callback triggered when (S)AJAX fails to eval the proxy's response
	function SAJAXTrap( myData ) {
		StopTimer();
		x_errorTrapReport( myData, SAJAXTrap_cb );
	}

	function SAJAXTrap_cb( myRet ) {
		document.getElementById("Timeout").style.display = "block";
	}

	function UpdateTimer() {
		if(timerID) {
			clearTimeout(timerID);
		}

		// If we knew about no timestamp, update the info and set the tStart
		if(!LastTimestamp)
		{
			if ( typeof(CUBEArray) == "object" )
			{
				tStart   = new Date();
				LastTimestamp = CUBEArray['Timestamp'];
			}
		} else {
			// Compare timestamps; if they differ, update tStart and elapsed time
			if ( typeof(CUBEArray) != "object" ) {
				StopTimer(); // We have already finished
			} else {
				if( CUBEArray['Timestamp'] != LastTimestamp ) {
					// Timestamp changed, reset the tStart
					tStart   = new Date();
					LastTimestamp = CUBEArray['Timestamp'];
				} else {
					// Same timestamp. Calculate elapsed time.
					var   tDate = new Date();
					var   tDiff = tDate.getTime() - tStart.getTime();

					tDate.setTime(tDiff);

					tElapsed = tDate.getMinutes() * 60 + tDate.getSeconds();

					// Check if more than 60 seconds elapsed; if so, it's probably dead
					if (tElapsed > 60) {
						// Timeout detected
						StopTimer();
						document.getElementById("Timeout").style.display = "block";
					} else {
						// No timeout, continue
						timerID = setTimeout("UpdateTimer()", 10000);
					}
				}
			}
		}


	}

	function StartTimer() {
		// Make it checks the status of the engine every 10 seconds
		StopTimer();
		LastTimestamp = null;
		document.getElementById("Timeout").style.display = "none";
		tStart   = new Date();
		timerID  = setTimeout("UpdateTimer()", 10000);
	}

	function StopTimer() {
	   if(timerID) {
	      clearTimeout(timerID);
	      timerID  = 0;
	   }

	   tStart = null;
	   LastTimestamp = null;
	}

	function do_Start( onlyDBMode ) {
		WriteDebug("Starting new backup...");
		document.getElementById("Welcome").style.display = "none";
		document.getElementById("Init").style.display = "block";
		x_tick( 1, onlyDBMode, do_Start_cb );
	}

	function do_Start_cb( myRet ) {
		WriteDebug("done<br/>");
		StartTimer();
		CUBEArray = myRet;
		ParseCUBEArray();
		do_tick();
	}

	function do_tick() {
		WriteDebug("Tick()&nbsp;&nbsp;");
		x_tick( 0, do_tick_cb );
	}

	function do_tick_cb( myRet )
	{
		WriteDebug("done tick()<br/>");
		StopGUITimer();
		CUBEArray = myRet;
		ParseCUBEArray();

		if ( typeof(CUBEArray) != "object" ) {
			AllDone();
		} else {
			if( CUBEArray['Domain'] == "finale" )
			{
				AllDone();
			} else {
				do_tick();
			}
		}
	}

	function do_getCUBEArray() {
		StopGUITimer();
		x_getCUBEArray( do_getCUBEArray_cb );
	}

	function do_getCUBEArray_cb( myRet ) {
		WriteDebug("Got CUBE Array<br/>");
		CUBEArray = myRet;
		ParseCUBEArray();
		StartGUITimer();
	}

	function StartGUITimer() {
		// Make it refreshes the screen every 2 seconds
		StopGUITimer();
		GUItimerID = setTimeout("GUITimer()", 2000);
	}

	function StopGUITimer() {
	   if(GUItimerID) {
	      clearTimeout(GUItimerID);
	      GUItimerID = 0;
	   }
	}

	function GUITimer() {
		if(GUItimerID) {
			clearTimeout(GUItimerID);
		}

		do_getCUBEArray();
	}

	function ParseCUBEArray() {
		WriteDebug("Parsing CUBE Array -- " + CUBEArray['Domain'] + " | " + CUBEArray['Step'] + "<br/>");
		if ( typeof( CUBEArray ) != "object" ) {
			AllDone();
		} else {
			if ( CUBEArray['Domain'] == "FileList" ) {
				document.getElementById("JPDomain").innerHTML = "<?php echo $JPLang['pack']['domfilelist'] ?>";
			} else if ( CUBEArray['Domain'] == "InstallerDeployment" ) {
				document.getElementById("JPDomain").innerHTML = "<?php echo $JPLang['pack']['dominstallerdeployment'] ?>";
			} else if ( CUBEArray['Domain'] == "PackDB" ) {
				document.getElementById("JPDomain").innerHTML = "<?php echo $JPLang['pack']['dompackdb'] ?>";
			} else if ( CUBEArray['Domain'] == "Packing" ) {
				document.getElementById("JPDomain").innerHTML = "<?php echo $JPLang['pack']['dompacking'] ?>";
			} else if ( CUBEArray['Domain'] == "finale" ) {
				AllDone();
			}

			document.getElementById("JPStep").innerHTML = CUBEArray['Step'];
			document.getElementById("JPSubstep").innerHTML = CUBEArray['Substep'];
		}
	}

	function AllDone() {
		WriteDebug("All done<br/>");
		StopTimer();
		StopGUITimer();
		document.getElementById("Init").style.display = "none";
		document.getElementById("Timeout").style.display = "none";
		document.getElementById("AllDone").style.display = "block";
	}

	</script>
</body>
<div id="Main">
	<div id="Welcome" class="sitePack">
		<p><?php echo $JPLang['pack']['prompt']; ?></p>
		<p><input type="button" id="bn_Pack" onclick="do_Start( 0 );" value="<?php echo $JPLang['pack']['button']; ?>" /></p>
		<p><input type="button" id="bn_Pack2" onclick="do_Start( 1 );" value="<?php echo $JPLang['pack']['button2']; ?>" /></p>
	</div>

	<div id="Init" style="display:none;" class="sitePack">
		<h4 id="JPDomain"></h4>
		<p><?php echo $JPLang['pack']['pleasewait']; ?></p>
		<p ></p>
		<p id="JPStep"></p>
		<p id="JPSubstep"></p>
	</div>

	<div id="Timeout" style="display: none;" class="sitePack">
		<h4><?php echo $JPLang['pack']['timeouttitle']; ?></h4>
		<p><?php echo $JPLang['pack']['timeout']; ?></p>
	</div>

	<div id="AllDone" style="display: none;" class="sitePack">
		<h4><?php echo $JPLang['pack']['finished']; ?></h4>
		<p><?php echo $JPLang['pack']['finishedtext']; ?></p>
	</div>

	<div id="Debug">
	</div>

</div>
<?php
?>