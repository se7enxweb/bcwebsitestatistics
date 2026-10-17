<?php
/**
 * The templates of BC Website Statistics carry no inline event handler, so their pages work under a Content Security
 * Policy without 'unsafe-inline'. Reads the files only, without a kernel or a database:
 *
 *   php vendor/bin/phpunit extension/bcwebsitestatistics/tests/bcWebsiteStatisticsTemplatesTest.php
 *
 *  WS-01 - No template has an event handler attribute, a javascript: URL or a call of the retired urchinTracker()
 *  WS-02 - The binary file view still links each file to content/download, with the file name escaped
 *
 * @copyright Copyright (C) 1998 - 2026 7x & Exponential Foundation. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @package tests
 */

use PHPUnit\Framework\TestCase;

class bcWebsiteStatisticsTemplatesTest extends TestCase
{
    private static function templates(): array
    {
        $files = array();
        $iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( dirname( __DIR__ ) . '/design', FilesystemIterator::SKIP_DOTS ) );
        foreach ( $iterator as $file )
        {
            if ( substr( $file->getFilename(), -4 ) === '.tpl' )
            {
                $files[] = $file->getPathname();
            }
        }
        sort( $files );
        return $files;
    }

    private static function source( string $path ): string
    {
        // template comments are not output
        return (string)preg_replace( '/\{\*.*?\*\}/s', '', (string)file_get_contents( $path ) );
    }

    /** WS-01 */
    public function testNoInlineHandler()
    {
        $templates = self::templates();
        $this->assertNotEmpty( $templates );
        foreach ( $templates as $path )
        {
            $source = self::source( $path );
            $this->assertDoesNotMatchRegularExpression( '/\son[a-z]+\s*=/i', $source, $path );
            $this->assertStringNotContainsStringIgnoringCase( 'javascript:', $source, $path );
            $this->assertStringNotContainsString( 'urchinTracker(', $source, $path );
        }
    }

    /** WS-02 */
    public function testBinaryFileViewLinksTheDownload()
    {
        $source = self::source( dirname( __DIR__ ) . '/design/standard/templates/content/datatype/view/ezbinaryfile.tpl' );
        $this->assertSame( 2, substr_count( $source, '<a href={concat("content/download/",$attribute.contentobject_id,"/",$attribute.id,"/file/",$attribute.content.original_filename)|ezurl}>' ) );
        $this->assertSame( 2, substr_count( $source, '{$attribute.content.original_filename|wash( xhtml )}</a>' ) );
    }
}
