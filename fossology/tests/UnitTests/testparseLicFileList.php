    <?php


/***********************************************************
 Copyright (C) 2008 Hewlett-Packard Development Company, L.P.

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 version 2 as published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License along
 with this program; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 ***********************************************************/

/**
 * use/test the parseminimenu class
 *
 * @param
 *
 * @return
 *
 * @version "$Id: testparseBrowseMenus.php 1152 2008-08-21 03:58:31Z rrando $"
 *
 * Created on Aug 20, 2008
 */

require_once ('../testClasses/parseLicFileList.php');
require_once ('../fossologyWebTestCase.php');
require_once ('../TestEnvironment.php');

global $URL;

class testParseLicFileList extends fossologyWebTestCase
{
  public $mybrowser;

  function setUp()
  {
    global $URL;
    print "TestparseLicFileList is running\n";
    $browser = & new SimpleBrowser();
    $page = $browser->get($URL);
    $this->assertTrue($page);
    $this->assertTrue(is_object($browser));
    $this->mybrowser = $browser;
    $cookie = $this->repoLogin($this->mybrowser);
    $this->host = $this->getHost($URL);
    $this->mybrowser->setCookie('Login', $cookie, $host);
  }
  function testPLicFileList()
  {
    /*
     * The test data depends on having the special fossology test
     * archive uploaded and the full fossology sources as well as the
     * Affero-v1. 0 file loaded.
     *
     * First test a medium size table mix of files and directories
     * Second test is single license (affero)
     * Third test is a table of 800+ licenses (full fossology)
     */
    $testPages = array (
    'http://osrb-1.fc.hp.com/repo/?mod=search_file_by_license&item=5066018&lic=LGPL+v2.1%2B',
    'http://osrb-1.fc.hp.com/repo/?mod=search_file_by_license&item=5067367&lic=Public+Domain',
                        );
    /*
     * testCounts is the number of table elements in each test
     *
     * This will make the this test a bit brittle, but needed to ensure
     * proper operation. Think about using mocks in the future.
     */
     $testCounts = array(1,5);

    /* navigate to a page to test*/
    $index = 0;
    foreach ($testPages as $link)
    {
      print "navigating to:\n";
      print "$link\n";
      $page = $this->mybrowser->get($link);
      //print "page is:$page\n";
      $LfileList = new parseLicFileList($page);
      $parsed = $LfileList->parseLicFileList();
      $fileCounts = count($parsed);
      $pathItems = count($parsed[$index]);
      //print "parsed path is:\n"; print_r($parsed) . "\n";
      $this->assertEqual($fileCounts,$testCounts[$index],
                         "FAIL! Number of files found did not match\n");
      /*
       * TODO: Extend this test when you get snape urls (above) and
       * count the number of path elements (includes leaf file) and
       * compare:
      $pathCounts = array(1,2,3);
      $this->assertEqual($pathItems,$pathCounts[$index],
                         "FAIL! number of path items did not match\n");
      */
      $index++;
    }
  }
}
?>
