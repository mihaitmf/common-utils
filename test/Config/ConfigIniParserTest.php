<?php

namespace CommonUtils\Tests\Config;

use CommonUtils\Config\Config;
use CommonUtils\Config\ConfigIniParser;
use PHPUnit\Framework\TestCase;

class ConfigIniParserTest extends TestCase
{
    public function testParseIniFileSuccess(): void
    {
        $config = ConfigIniParser::fromFile(__DIR__ . DIRECTORY_SEPARATOR . 'test_config_sample.ini');

        self::assertSame('localhost', $config->database->host);
        self::assertSame('my_db', $config['database']['name']);
        self::assertInstanceOf(
            Config::class,
            $config->database,
            'When reading just the config ini section, it should return an object'
        );

        self::assertSame('Shiny new headphones', $config->prod01->name);
        self::assertSame('https://www.eshop.com/shiny-new-headphones', $config->prod01->url);
        self::assertSame('100.89', $config->prod01->price);

        self::assertSame('1', $config->{123}->setting->{0});
        self::assertSame('2', $config[123]->setting[1]);

        self::assertSame('john', $config->nested->{'user.name'});
        self::assertSame('john.doe@gmail.com', $config->nested['user.email']);
    }
}
