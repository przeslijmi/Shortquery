<?php declare(strict_types=1);

/**
 * Tool used to download all data (dump) from POPF MsSql into JSON files.
 *
 * File are then used by Reader (read.php) to transform them from POPF clear JSON files into data warehouse
 * specific JSON files (in different data construction).
 *
 * Basic usage:
 *
 */

// Change dir to one with this file.
$dirOfBinFile = dirname(__FILE__);
chdir($dirOfBinFile);

// Look for bootstrap param.
if (($bootstrap = array_search('--bootstrap', $argv)) !== false) {

    // Find.
    $bootstrap = ( $argv[(++$bootstrap)] ?? null );

    // Continue.
    if ($bootstrap !== null) {

        // Lvd.
        $bootstrapUri  = dirname($bootstrap);
        $bootstrapFile = basename($bootstrap);

        // Require.
        chdir($bootstrapUri);
        require $bootstrapFile;
    }
}

use Przeslijmi\Shortquery\Creator;
use Przeslijmi\Sexceptions\Handler;

try {

    // Define CLI Application.
    $creator = new Creator();
    $creator->getParams()->setAliases('config', 'c');
    $creator->getParams()->setAliases('schema', 's');
    $creator->getParams()->set($argv);
    $creator->getParams()->setParam('baseDir', $dirOfBinFile);

    // Start performing operations.
    $creator->start();

} catch (Exception $e) {
    Handler::handle($e);
}

