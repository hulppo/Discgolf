<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<?php foreach ($view['assetic']->stylesheets(
    array('@LupoDiscgolf/Resources/public/css/960.css',
          '@LupoDiscgolf/Resources/public/css/styles.css',
          '@LupoDiscgolf/Resources/public/lib/jqueryUI/css/ui-darkness/jquery-ui-1.8.18.custom.css'
          )) as $url): ?>
    <link rel="stylesheet" href="<?php echo $view->escape($url) ?>" />
<?php endforeach; ?>
<?php foreach ($view['assetic']->javascripts(
    array('@LupoDiscgolf/Resources/public/lib/jqueryUI/js/jquery-1.7.1.min.js',
          '@LupoDiscgolf/Resources/public/lib/jqueryUI/js/jquery-ui-1.8.18.custom.min.js',
          '@LupoDiscgolf/Resources/public/lib/jquery.json-2.3.min.js',
          '@LupoDiscgolf/Resources/public/lib/knockout/knockout-2.1.0.debug.js',
          '@LupoDiscgolf/Resources/public/lib/jshashtable-2.1.js',
          '@LupoDiscgolf/Resources/public/lib/log4javascript.js',
          '@LupoDiscgolf/Resources/public/lib/jquery.blockUI.js',
          '@LupoDiscgolf/Resources/public/lib/flot/jquery.flot.js',
          )) as $url): ?>
    <script src="<?php echo $view->escape($url) ?>"></script>
<?php endforeach; ?>
    <script type="text/javascript">
var Constants = {REST_ROOT: "<?php echo $api_root ?>"};
    </script>
<?php foreach ($view['assetic']->javascripts(
    array('@LupoDiscgolf/Resources/public/js/misc/utils.js',
          '@LupoDiscgolf/Resources/public/js/misc/UILogger.js',
          '@LupoDiscgolf/Resources/public/js/misc/Logger.js',
          '@LupoDiscgolf/Resources/public/js/misc/AjaxRunner.js',

          '@LupoDiscgolf/Resources/public/js/dto/CourseDTO.js',
          '@LupoDiscgolf/Resources/public/js/dto/PlayerDTO.js',
          '@LupoDiscgolf/Resources/public/js/dto/RoundDTO.js',
          '@LupoDiscgolf/Resources/public/js/dto/ResultDTO.js',
          '@LupoDiscgolf/Resources/public/js/dto/HoleDTO.js',

          '@LupoDiscgolf/Resources/public/js/model/DomainModel.js',
          '@LupoDiscgolf/Resources/public/js/model/Course.js',
          '@LupoDiscgolf/Resources/public/js/model/Player.js',
          '@LupoDiscgolf/Resources/public/js/model/Round.js',
          '@LupoDiscgolf/Resources/public/js/model/RoundGroup.js',
          '@LupoDiscgolf/Resources/public/js/model/PlayerResult.js',

          '@LupoDiscgolf/Resources/public/js/service/CourseDAO.js',
          '@LupoDiscgolf/Resources/public/js/service/PlayerDAO.js',
          '@LupoDiscgolf/Resources/public/js/service/RoundDAO.js',
          '@LupoDiscgolf/Resources/public/js/service/DGAPIServiceFacade.js',

          '@LupoDiscgolf/Resources/public/js/view/ViewController.js',
          '@LupoDiscgolf/Resources/public/js/view/PlayerListViewModel.js',
          '@LupoDiscgolf/Resources/public/js/view/RoundListViewModel.js',
          '@LupoDiscgolf/Resources/public/js/view/RoundGrouperViewModel.js',
          '@LupoDiscgolf/Resources/public/js/view/ParGraphViewModel.js',

          '@LupoDiscgolf/Resources/public/js/stats/PowerTable.js',
          '@LupoDiscgolf/Resources/public/js/init.js',
          )) as $url): ?>
    <script src="<?php echo $view->escape($url) ?>"></script>
<?php endforeach; ?>
    </head>
    <body>

    <!--  Knockout template for scorecard  -->
    <script type="text/html" id="scorecard-template">
        <table cellspacing=1 cellpadding=2 border=0 style='margin: 15px;'>
            <tbody>
            <tr bgcolor='#669966' ALIGN='CENTER'>
                <th style='white-space: nowrap;'>
                    <span style='font-size:13px;color:#ffffcc;font-family:arial'>HOLE</span>
                </th>
                <!-- ko foreach: course.getHoles().slice(0,9) -->
                <th>
                    <div style='width:18px;'>
                        <span style='font-size:13px;color:#ffffcc;font-family:arial' data-bind="text: getNumber()"></span>
                    </div>
                </th>
                <!-- /ko -->
                <th><span style='font-size:13px;color:#ffffcc;font-family:arial'>OUT</span></th>
                <!-- ko foreach: course.getHoles().slice(9) -->
                <th>
                    <div style='width:18px;'>
                        <span style='font-size:13px;color:#ffffcc;font-family:arial' data-bind="text: getNumber()"></span>
                    </div>
                </th>
                <!-- /ko -->
                <th><span style='font-size:13px;color:#ffffcc;font-family:arial'>IN</span></th>
                <th><span style='font-size:13px;color:#ffffcc;font-family:arial'>TOT</span></th>
                <th><span style='font-size:13px;color:#ffffcc;font-family:arial'>AVG</span></th>
                <th><span style='font-size:13px;color:#ffffcc;font-family:arial'>HCP</span></th>
                <th><span style='font-size:13px;color:#ffffcc;font-family:arial'>NET</span></th>
            </tr>

            <tr bgcolor='#669966' ALIGN='CENTER'>
                <th style='white-space: nowrap;'>
                    <span style='font-size:13px;color:#ffffcc;font-family:arial'>PAR</span>
                </th>
                <!-- ko foreach: course.getHoles().slice(0,9) -->
                <th>
                    <span style='font-size:13px;color:#ffffcc;font-family:arial' data-bind="text: getPar()"></span>
                </th>
                <!-- /ko -->
                <th>
                    <span style='font-size:13px;color:#ffffcc;font-family:arial' data-bind="text: course.parIn()"></span>
                </th>
                <!-- ko foreach: course.getHoles().slice(9) -->
                <th>
                    <span style='font-size:13px;color:#ffffcc;font-family:arial' data-bind="text: getPar()"></span>
                </th>
                <!-- /ko -->
                <th>
                    <span style='font-size:13px;color:#ffffcc;font-family:arial' data-bind="text: course.parOut()"></span>
                </th>
                <th>
                    <span style='font-size:13px;color:#ffffcc;font-family:arial' data-bind="text: course.parIn() + course.parOut()"></span>
                </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>

            <!-- ko foreach: round.getResults() -->
            <tr bgcolor='#F0F0F0' ALIGN='CENTER' style="color: black;">
                <td style='white-space: nowrap;' data-bind="text: $parent.domainModel.getPlayer(getPlayerId()).getName()"></td>
                <!-- ko foreach: getResults().slice(0,9) -->
                <td data-bind="text: $data"></td>
                <!-- /ko -->
                <td data-bind="text: getIn()"></td>
                <!-- ko foreach: getResults().slice(9) -->
                <td data-bind="text: $data"></td>
                <!-- /ko -->
                <td data-bind="text: getOut()"></td>
                <td data-bind="text: (getIn() + getOut())"></td>
                <td></td>
                <td>N/A</td>
                <td>N/A</td>
            </tr>
            <!-- /ko -->
        </tbody>
    </table>
    </script>


    <div class="header">
        <div class="container_12">
            <div class="grid_12">
                <div id="rollingLog"></div>
            </div>
        </div>
    </div>

    <div class="mainContainer">
        <div class="container_12">
            <div class="grid_3" id="playerSelectList">
                <h3>Select players:</h3>
                <ul data-bind="foreach: players" class="selectList">
                    <li data-bind="click: $parent.togglePlayer, css: { selectedItem: selected(), unselectedItem: !selected() }">
                        <span data-bind="text: getName()"></span>
                    </li>
                </ul>
            </div>

            <div class="grid_4">
                <div id="roundsByCourseList">
                    <div data-bind="visible: roundGroups().length != 0" style="display: none;">
                        <h3>Select courses:</h3>
                        <input data-bind="checked: requireAll, click: toggleRequireAll" type="checkbox"/> Require participation by all selected players
                        <ul data-bind="foreach: roundGroups()" class="selectList">
                            <li data-bind="click: $parent.toggleRoundGroup, if: rounds().length > 0, visible: rounds().length > 0, css: { selectedItem: selected(), unselectedItem: !selected() }">
                                <span data-bind="text: rounds()[0].getCourseName()"></span>
                                (par <span data-bind="text: par"></span>)
                                <span class="counter" data-bind="text: rounds().length"></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="grid_5" id="roundList">
                <div id="filteredRoundList">
					<div data-bind="visible: filteredRounds().length != 0" style="display: none">
                        <h3>Rounds included:</h3>
                        <table class="roundlist">
							<tbody data-bind="foreach: filteredRounds">
                                    <tr data-bind="click: $parent.showScoreCard">
                                        <td>
                                            <img src="img/pixelbox/GIF/026.gif"/>
                                        </td>
                                        <td>
                                            <span data-bind="text: getCourseName()"></span>
                                        </td>
                                        <td>
                                            <span data-bind="text: getTimestamp()"></span>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="container_12">
				<div class="grid_12" id="powerTable" class="centeredContainer" style="display: none" data-bind="visible: results().length > 0">
					<div class="centeredContainer"><h3 style="text-align: center;">Power table between selected players</h3></div>
					<table   class="ui-widget ui-widget-content ui-corner-all ui-theme centeredContainer" >
                        <thead>
                            <tr>
                                <th data-bind="click: toggleViewMode">
                                    <!-- ko if: viewMode() == viewModes.VALUES  -->
                                    <span style="color: green;">Wins</span> / Ties / Rounds
                                    <img src="img/pixelbox/GIF/124.gif"/>
                                    <!-- /ko -->

                                    <!-- ko if: viewMode() == viewModes.PERCENT  -->
                                    <span style="color: green;">Win percent</span>
                                    <img src="img/pixelbox/GIF/123.gif"/>
                                    <!-- /ko -->
                                </th>
                                <!-- ko foreach: results -->
                                <th data-bind="text: $data[0].getName()" class="tablesorter-header ui-widget-header ui-corner-all ui-state-default"></th>
                                <!-- /ko -->
                            </tr>
                        </thead>
                        <tbody data-bind="foreach: results">
                            <tr>
                                <td data-bind="text: $data[0].getName()" class="tablesorter-header ui-widget-header ui-corner-all ui-state-default"></td>
                                <!-- ko foreach: $data[1].entries() -->
                                <td data-bind="visible: $root.viewMode() == $root.viewModes.VALUES">
                                     <!-- ko if: !$data[1].againstOneSelf()  -->
                                        <span data-bind="text: $data[1].winCount" style="color: green;"></span>
                                        / <span data-bind="text: $data[1].tieCount"></span>
                                        / <span data-bind="text: $data[1].roundCount"></span>
                                    <!-- /ko -->
                                </td>
                                <td data-bind="visible: $root.viewMode() == $root.viewModes.PERCENT">
                                     <!-- ko if: !$data[1].againstOneSelf()  -->
                                        <span data-bind="text: $data[1].winPercent()" style="color: green;"></span> %
                                    <!-- /ko -->
                                </td>
                                <!-- /ko -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

			<div class="container_12">
				<br/>
				<div class="grid_12" id="parGraph" >
					<div data-bind="visible: isReady" style="display: none">
						<div class="centeredContainer"><h3 style="text-align: center;">Difference to PAR over time</h3></div>
						<div id="distanceToParOverTime" style="width: 600px; height: 300px;" class="centeredContainer">
						</div>
					</div>
				</div>
			</div>

        </div>

    </div>
        <div class="footer">
            <div class="container_12">
                <div class="grid_3">
                    &copy; 2012 &nbsp; Mikko Ravimo
                </div>
                <div class="grid_9">
                    <table style="float: right;">
                        <tr>
                            <td>
                                Powered by:
                            </td>
                            <td>
                                <a target="_blank" href="http://960.gs/"><img class="powered" alt="960grid" src="./img/powered/960grid.gif"></a>
                                <a target="_blank" href="http://jquery.com/"><img class="powered" alt="jquery" src="./img/powered/jquery.gif"></a>
                                <a target="_blank" href="http://jqueryui.com/"><img class="powered" alt="jqueryUI" src="./img/powered/jqueryUI.gif"></a>
                                <a target="_blank" href="http://knockoutjs.com/"><img class="powered" alt="knockout" src="./img/powered/ko-logo.png"></a>
								<a target="_blank" href="http://code.google.com/p/flot/" class="powered"><img class="powered" alt="knockout" src="./img/powered/flot.png"></a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
