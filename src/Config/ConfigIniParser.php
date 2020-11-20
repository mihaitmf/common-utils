<?php

namespace CommonUtils\Config;

use RuntimeException;

class ConfigIniParser
{
    public static function fromFile(string $configFilePath): Config
    {
        if (!is_file($configFilePath)) {
            throw new RuntimeException(sprintf('Invalid config file path! Not a file: %s', $configFilePath));
        }

        $parsedConfig = parse_ini_file($configFilePath, true);
        if ($parsedConfig === false) {
            throw new RuntimeException(sprintf('Could not parse config ini file from path: %s', $configFilePath));
        }
        
        return new Config($parsedConfig);
    }
}
