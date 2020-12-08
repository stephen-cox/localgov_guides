<?php

namespace Drupal\Tests\localgov_guides\Functional;

use Drupal\node\NodeInterface;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\node\Traits\NodeCreationTrait;

/**
 * Tests page header block.
 *
 * @group localgov_guides
 */
class PageHeaderBlockTest extends BrowserTestBase {

  use NodeCreationTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'block',
    'path',
    'options',
    'localgov_guides',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'classy';

  /**
   * A user with the 'administer blocks' permission.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->adminUser = $this->drupalCreateUser(['administer blocks']);
    $this->drupalLogin($this->adminUser);
    $this->drupalPlaceBlock('localgov_page_header_block');
    $this->drupalLogout($this->adminUser);
  }

  /**
   * Tests that the page header block displays the overview title.
   *
   * This applies to Guide overview pages and Guide pages that are part of a
   * guide, with the fallback to the guide page title if no parent.
   */
  public function testGuidePageHeaderBlock() {
    $overview_title = 'Guide overview - ' . $this->randomMachineName(8);
    $overview_lede = 'Guide overview summary - ' .  $this->randomMachineName(8);
    $overview = $this->createNode([
      'title' => $overview_title,
      'localgov_guides_description' => $overview_lede,
      'type' => 'localgov_guides_overview',
      'status' => NodeInterface::PUBLISHED,
    ]);

    $page_title = 'Guide page - ' . $this->randomMachineName(8);
    $page_summary = 'Guide page summary - ' . $this->randomMachineName(8);
    $page = $this->createNode([
      'title' => $page_title,
      'body' => ['value' => $page_summary . ' - body', 'summary' => $page_summary],
      'type' => 'localgov_guides_page',
      'status' => NodeInterface::PUBLISHED,
      'localgov_guides_parent' => ['target_id' => $overview->id()],
    ]);

    $orphan_title = 'Guide page - ' . $this->randomMachineName(8);
    $orphan_summary = 'Guide orphan summary - ' . $this->randomMachineName(8);
    $orphan = $this->createNode([
      'title' => $orphan_title,
      'body' => ['value' => $orphan_summary . ' - body', 'summary' => $orphan_summary],
      'type' => 'localgov_guides_page',
      'status' => NodeInterface::PUBLISHED,
    ]);

    $this->drupalGet($overview->toUrl()->toString());
<<<<<<< HEAD
    $this->assertSession()->responseContains('<h1 class="header">' . $overview_title . '</h1>');
    $this->assertRaw('<div class="subheader">' . $overview_lede . '</div>');

    $this->drupalGet($page->toUrl()->toString());
    $this->assertSession()->responseContains('<h1 class="header">' . $overview_title . '</h1>');
    $this->assertSession()->responseNotContains('<h1 class="header">' . $page_title . '</h1>');
    $this->assertRaw('<div class="subheader">' . $overview_lede . '</div>');
    $this->assertNoRaw('<div class="subheader">' . $page_summary . '</div>');

    $this->drupalGet($orphan->toUrl()->toString());
    $this->assertSession()->responseNotContains('<h1 class="header">' . $overview_title . '</h1>');
    $this->assertSession()->responseContains('<h1 class="header">' . $orphan_title . '</h1>');
    $this->assertRaw('<div class="subheader">' . $orphan_summary . '</div>');
=======
    $this->assertRaw('<h1 class="header">' . $overview_title . '</h1>');
    $this->assertRaw('<p>' . $overview_lede . '</p>');

    $this->drupalGet($page->toUrl()->toString());
    $this->assertRaw('<h1 class="header">' . $overview_title . '</h1>');
    $this->assertNoRaw('<h1 class="header">' . $page_title . '</h1>');
    $this->assertRaw('<p>' . $overview_lede . '</p>');
    $this->assertNoRaw('<p>' . $page_summary . '</p>');

    $this->drupalGet($orphan->toUrl()->toString());
    $this->assertRaw('<h1 class="header">' . $orphan_title . '</h1>');
    $this->assertRaw('<p>' . $orphan_summary . '</p>');
>>>>>>> 9e8f0c7... Use the guide overview description field for the guide lede
  }

}