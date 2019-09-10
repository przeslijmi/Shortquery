<?php declare(strict_types=1);

/**
 * Tool used to download all data (dump) from POPF MsSql into JSON files.
 *
 * File are then used by Reader (read.php) to transform them from POPF clear JSON files into data warehouse
 * specific JSON files (in different data construction).
 *
 * Basic usage:
 *
 * ```
 * cd c:\Dev\php\ws\stolem
 * php bin/fetchPopf.php
 * php bin/fetchPopf.php help
 * php bin/fetchPopf.php fetch -d "\Dev\data\dw\source.popf"
 * ```
 *
 * Where:
 * -d, --destination Directory to which all JSON file have to be put (use `\` at the beginning, do not use `\` at
 *                   the end). As dir separator use only backslashes.
 */

// Change dir to one with this file.
chdir(dirname($argv[0]));
chdir('../');

// Now leave vendors up to main application dir.
chdir('../../../');

// Require bootstrap.
require('bootstrap.php');

use Przeslijmi\Shortquery\Creator;
use Przeslijmi\Sexceptions\Handler;

try {

    // Define CLI Application.
    $creator = new Creator();
    $creator->getParams()->setAliases('config', 'c');
    $creator->getParams()->setAliases('schema', 's');
    $creator->getParams()->set($argv);

    // Start performing operations.
    $creator->start();

} catch (Exception $e) {
    Handler::handle($e);
}

