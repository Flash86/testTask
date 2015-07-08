<?php
if (FLOW_PATH_ROOT !== '/var/www/test/test_task/' || !file_exists('/var/www/test/test_task/Data/Temporary/Production/Configuration/ProductionConfigurations.php')) {
	unlink(__FILE__);
	return array();
}
return require '/var/www/test/test_task/Data/Temporary/Production/Configuration/ProductionConfigurations.php';