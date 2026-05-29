<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Helpers\VideoUrlHelper;

class VideoUrlHelperTest extends TestCase
{
    /** @test */
    public function it_identifies_allowed_video_domains()
    {
        $this->assertTrue(VideoUrlHelper::isAllowed('https://www.youtube.com/watch?v=dQw4w9WgXcQ'));
        $this->assertTrue(VideoUrlHelper::isAllowed('https://youtu.be/dQw4w9WgXcQ'));
        $this->assertTrue(VideoUrlHelper::isAllowed('https://vimeo.com/8437000'));
        $this->assertTrue(VideoUrlHelper::isAllowed('https://player.vimeo.com/video/8437000'));
        
        $this->assertFalse(VideoUrlHelper::isAllowed('https://google.com'));
        $this->assertFalse(VideoUrlHelper::isAllowed('https://malicious.com/youtube.com'));
        $this->assertFalse(VideoUrlHelper::isAllowed(null));
        $this->assertFalse(VideoUrlHelper::isAllowed(''));
    }

    /** @test */
    public function it_converts_urls_to_embed_urls()
    {
        // YouTube Standard
        $this->assertEquals(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            VideoUrlHelper::toEmbedUrl('https://www.youtube.com/watch?v=dQw4w9WgXcQ')
        );

        // YouTube Short Url
        $this->assertEquals(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            VideoUrlHelper::toEmbedUrl('https://youtu.be/dQw4w9WgXcQ')
        );

        // YouTube Shorts
        $this->assertEquals(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            VideoUrlHelper::toEmbedUrl('https://www.youtube.com/shorts/dQw4w9WgXcQ')
        );

        // Vimeo Standard
        $this->assertEquals(
            'https://player.vimeo.com/video/8437000',
            VideoUrlHelper::toEmbedUrl('https://vimeo.com/8437000')
        );

        // Invalid Url
        $this->assertNull(VideoUrlHelper::toEmbedUrl('https://google.com'));
        $this->assertNull(VideoUrlHelper::toEmbedUrl(''));
        $this->assertNull(VideoUrlHelper::toEmbedUrl(null));
    }

    /** @test */
    public function it_detects_providers()
    {
        $this->assertEquals('youtube', VideoUrlHelper::getProvider('https://youtube.com/watch?v=123'));
        $this->assertEquals('youtube', VideoUrlHelper::getProvider('https://youtu.be/123'));
        $this->assertEquals('vimeo', VideoUrlHelper::getProvider('https://vimeo.com/123'));
        
        $this->assertNull(VideoUrlHelper::getProvider('https://google.com'));
        $this->assertNull(VideoUrlHelper::getProvider(null));
    }

    /** @test */
    public function it_generates_thumbnail_urls_for_youtube()
    {
        $this->assertEquals(
            'https://img.youtube.com/vi/dQw4w9WgXcQ/mqdefault.jpg',
            VideoUrlHelper::getThumbnailUrl('https://www.youtube.com/watch?v=dQw4w9WgXcQ')
        );
        $this->assertEquals(
            'https://img.youtube.com/vi/dQw4w9WgXcQ/mqdefault.jpg',
            VideoUrlHelper::getThumbnailUrl('https://youtu.be/dQw4w9WgXcQ')
        );
        
        $this->assertNull(VideoUrlHelper::getThumbnailUrl('https://vimeo.com/123'));
        $this->assertNull(VideoUrlHelper::getThumbnailUrl(null));
    }
}
