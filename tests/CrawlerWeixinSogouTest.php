<?php

use PHPUnit\Framework\TestCase;

require 'common.php';

/**
 * @covers CrawlerWeixinSogou
 */
final class CrawlerWeixinSogouTest extends TestCase
{
    public function testCanBeCreatedFromWeixinSogou()
    {
        $this->assertInstanceOf(
            CrawlerWeixinSogou::class,
            new CrawlerWeixinSogou()
        );
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage keywords required for weixin sogou
     */
    public function testKeywordsCannotBeNull()
    {
        $crawler = new CrawlerWeixinSogou();
        $crawler->setConfig([
         'keyword_check' => ['魔兽争霸'],
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage keywords cannot be empty for weixin sogou
     */
    public function testKeywordsCannotBeEmpty()
    {
        $crawler = new CrawlerWeixinSogou();
        $crawler->setConfig([
            'keywords' => [],
            'keyword_check' => ['魔兽争霸'],
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage invalid page setting for weixin sogou
     */
    public function testPageCannotBeZero()
    {
        $crawler = new CrawlerWeixinSogou();
        $crawler->setConfig([
            'keywords' => ['大兴区区长辞职'],
            'page' => 0,
            'keyword_check' => ['魔兽争霸'],
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage invalid page setting for weixin sogou
     */
    public function testPageCannotBeNegative()
    {
        $crawler = new CrawlerWeixinSogou();
        $crawler->setConfig([
            'keywords' => ['大兴区区长辞职'],
            'page' => -1,
            'keyword_check' => ['DOTA'],
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();
    }

    public function testCanGetEnoughMessageWithPageConfig()
    {
        $crawler = new CrawlerWeixinSogou();
        $crawler->setConfig([
            'keywords' => ['dota2'],
            'keyword_check' => [],
            'page' => 2,
            // 'debug' => true,
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();

        $this->assertGreaterThanOrEqual(20,count($crawler->getMessage()));
        foreach ($crawler->getMessage() as $key => $value) {
            $this->assertArrayHasKey('url_author',$value);
            $this->assertArrayHasKey('author_link',$value);
            $this->assertArrayHasKey('pics',$value);
            $this->assertArrayHasKey('link',$value);
            $this->assertArrayHasKey('title',$value);
        }
    }

    public function testCannotGetEnoughMessageWithKeywordcheckConfig()
    {
        $crawler = new CrawlerWeixinSogou();
        $crawler->setConfig([
            'keywords' => ['大兴区区长辞职'],
            'page' => 1,
            'keyword_check' => ['崔志成'],
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();

        $this->assertLessThanOrEqual(10,count($crawler->getMessage()));
    }

}
