<?php
class VisitorsController extends Controller {
  function index() {
    
    // Get the IP address of the visitor
    $remote_ip = $_SERVER['REMOTE_ADDR'];
    
    // Check XFF to see fi a proxy is being used by the visitor
    // and extract the client IP from the XFF header.
    $headers = apache_request_headers();
    if(isset($headers['X-Forwarded-For']) && !empty($headers['X-Forwarded-For'])) {
      $xff_ips = explode(',',$headers['X-Forwarded-For']);
      $remote_ip = trim($xff_ips[0]);
    }
    
    // Save the IP addresses to the database.
    $this->Visitor->create();
    $this->Visitor->set('ip', $remote_ip);
    $this->Visitor->set('user_agent', $_SERVER['HTTP_USER_AGENT']);
    $this->Visitor->save();
    
    // Send the IP address to the view
    $this->set('ip', $remote_ip);
    $this->set('title_for_layout', 'Current IP Check');
  }
}
?>
