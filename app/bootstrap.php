<?php

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

$params = [
  'tempDir' => __DIR__ . '/../temp',
  'wwwDir' => __DIR__ . '/../www',
  'logDir' => __DIR__ . '/../log',
  'logEmail' => 'lawondyss@gmail.com',
];

$configurator->addParameters($params);

$configurator->setDebugMode(array_filter([($_SERVER['SERVER_ID'] ?? '') === 'DOCKERTEST' ? ('enable-debugger@' . $_SERVER['REMOTE_ADDR']) : '']));
$configurator->enableDebugger($params['logDir'], $params['logEmail']);

$configurator->setTempDirectory($params['tempDir']);

$configurator->createRobotLoader()
             ->addDirectory(__DIR__)
             ->register();


// Loading configuration
$files = [];

// basic configuration
foreach (glob(__DIR__ . '/config/*.neon') as $file) {
  $files[] = $file;
}

// modules configuration
foreach (glob(__DIR__ . '/module/*/*.neon') as $file) {
  $files[] = $file;
}

$configListFile = sprintf('nette.safe://%s/%s-include_configs.php', $params['tempDir'], md5(var_export($files, true)));

if (!file_exists($configListFile)) {
  // store to file
  $phpCode = "<?php\n";
  foreach ($files as $file) {
    $phpCode .= "\$configurator->addConfig('{$file}');\n";
  }
  file_put_contents($configListFile, $phpCode);
}

include $configListFile;


return $configurator->createContainer();
