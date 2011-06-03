<?php
class VisitorsControllerTest extends CakeTestCase {
  function startCase() {
    echo '<h1>Starting Visitors Test Case</h1>';
  }
  function endCase() {
    echo '<h1>Ending Visitors Test Case</h1>';
  }
  function startTest($method) {
    echo '<h3>Starting test method "' . $method . '".</h3>';
  }
  function endTest($method) {
    echo '<hr/>';
  }
  function testIndex() {
    $result = $this->testAction('/visitors/index', array('return'=>'view'));
    echo print_r($result, TRUE);
    $this->assertPattern('/^Current IP Address: \d+\.\d+\.\d+\.\d+$/', $result);
  }
}
?>